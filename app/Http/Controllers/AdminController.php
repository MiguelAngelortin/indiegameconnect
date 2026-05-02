<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Game;
use App\Models\GamePost;
use Barryvdh\DomPDF\Facade\Pdf;
class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalGames = Game::count();
        $totalPosts = GamePost::count();
        $newUsersThisMonth = User::where('created_at', '>=', now()->subDays(30))->count();
        $newGamesThisMonth = Game::where('created_at', '>=', now()->subDays(30))->count();

        return view('admin.index', compact('totalUsers', 'totalGames', 'totalPosts', 'newUsersThisMonth', 'newGamesThisMonth'));
    }

       public function users(Request $request)
{
    $sortBy = $request->sort ?? 'id';
    $sortDir = $request->direction ?? 'asc';

    $allowedSorts = ['id', 'name', 'email', 'role', 'created_at'];
    if (!in_array($sortBy, $allowedSorts)) {
        $sortBy = 'id';
    }

    $users = User::when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        })
        ->orderBy($sortBy, $sortDir)
        ->paginate(20);

    return view('admin.users', compact('users', 'sortBy', 'sortDir'));
}

public function editUser($user_id)
{
    $user = User::findOrFail($user_id);
    return view('admin.edit-user', compact('user'));
}

public function updateUser(Request $request, $user_id)
{
    $user = User::findOrFail($user_id);
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email,' . $user_id],
        'role' => ['required', 'in:user,developer,admin'],
    ]);
    $user->update($request->only('name', 'email', 'role'));
    return redirect('/admin/users')->with('success', 'User updated successfully.');
}

public function banUser($user_id)
{
    $user = User::findOrFail($user_id);
    if ($user->role === 'admin') {
        return redirect('/admin/users');
    }
    $user->update(['is_banned' => !$user->is_banned]);
    return redirect('/admin/users');
}

public function destroyUser($user_id)
{
    $user = User::findOrFail($user_id);
    if ($user->role === 'admin') {
        return redirect('/admin/users');
    }
    $user->delete();
    return redirect('/admin/users');
}

public function games(Request $request)
{
    $sortBy = $request->sort ?? 'id';
    $sortDir = $request->direction ?? 'asc';

    $allowedSorts = ['id', 'title', 'status', 'created_at'];
    if (!in_array($sortBy, $allowedSorts)) {
        $sortBy = 'id';
    }

    $games = Game::with(['user', 'genres'])
        ->when($request->search, function ($query) use ($request) {
            $query->where('title', 'like', '%' . $request->search . '%');
        })
        ->orderBy($sortBy, $sortDir)
        ->paginate(20);

    return view('admin.games', compact('games', 'sortBy', 'sortDir'));
}

public function destroyGame($game_id)
{
    $game = Game::findOrFail($game_id);
    $game->delete();
    return redirect('/admin/games');
}

public function posts(Request $request)
{
    $sortBy = $request->sort ?? 'id';
    $sortDir = $request->direction ?? 'asc';

    $allowedSorts = ['id', 'title', 'created_at'];
    if (!in_array($sortBy, $allowedSorts)) {
        $sortBy = 'id';
    }

    $posts = GamePost::with(['game', 'user'])
        ->when($request->search, function ($query) use ($request) {
            $query->where('title', 'like', '%' . $request->search . '%');
        })
        ->orderBy($sortBy, $sortDir)
        ->paginate(20);

    return view('admin.posts', compact('posts', 'sortBy', 'sortDir'));
}

public function destroyPost($post_id)
{
    $post = GamePost::findOrFail($post_id);
    $post->delete();
    return redirect('/admin/posts');
}

public function report()
{
    $totalUsers = User::count();
    $totalGames = Game::count();
    $totalPosts = GamePost::count();
    $newUsersThisMonth = User::where('created_at', '>=', now()->subDays(30))->count();
    $newGamesThisMonth = Game::where('created_at', '>=', now()->subDays(30))->count();
    $topGames = Game::withCount('follows')->orderBy('follows_count', 'desc')->take(5)->get();
    $topDevelopers = User::where('role', 'developer')->orWhere('role', 'admin')->withCount('follows')->orderBy('follows_count', 'desc')->take(5)->get();

    $pdf = Pdf::loadView('admin.report', compact(
        'totalUsers', 'totalGames', 'totalPosts',
        'newUsersThisMonth', 'newGamesThisMonth',
        'topGames', 'topDevelopers'
    ));

    return $pdf->download('indiegameconnect-report.pdf');
}

}