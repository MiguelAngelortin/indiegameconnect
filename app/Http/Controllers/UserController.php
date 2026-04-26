<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\User;
use App\Models\UserFollow;
use App\Models\UserRating;


class UserController extends Controller
{
    public function show($user_id)
    {
        $user = User::findOrFail($user_id);
        $games = null;
        $followersCount = 0;
        $ratingPercent = null;
        $isFollowing = false;
        $userRating = null;
        $followedGames = $user->followedGames()->with('genres')->take(6)->get();
        $followedGamesCount = $user->followedGames()->count();

        $followedDevelopers = $user->following()->with('developer')->take(6)->get();
        $followedDevelopersCount = $user->following()->count();

        if ($user->role === 'developer' || $user->role === 'admin') {
            $games = Game::with('genres')->where('user_id', $user->id)->paginate(6);
            $followersCount = $user->follows()->count();
            $ratingsPositive = $user->ratings()->where('rating', 1)->count();
            $ratingsTotal = $user->ratings()->count();
            $ratingPercent = $ratingsTotal > 0 ? round(($ratingsPositive / $ratingsTotal) * 100) : null;
            $isFollowing = auth()->check() ? UserFollow::where('user_id', auth()->user()->id)->where('developer_id', $user->id)->exists() : false;
            $userRating = auth()->check() ? UserRating::where('user_id', auth()->user()->id)->where('developer_id', $user->id)->first() : null;
        }

        return view('users.show', compact('user', 'games', 'followersCount', 'ratingPercent', 'isFollowing', 'userRating', 'followedGames', 'followedGamesCount', 'followedDevelopers', 'followedDevelopersCount'));
    }

    public function follow($user_id)
    {
        $follow = UserFollow::where('user_id', auth()->user()->id)
            ->where('developer_id', $user_id)
            ->first();

        if ($follow) {
            $follow->delete();
        } else {
            UserFollow::create([
                'user_id' => auth()->user()->id,
                'developer_id' => $user_id,
            ]);
        }

        return redirect()->back();
    }

    public function rate(Request $request, $user_id)
    {
        $request->validate([
            'rating' => ['required', 'in:1,-1'],
        ]);

        UserRating::updateOrCreate(
            ['user_id' => auth()->user()->id, 'developer_id' => $user_id],
            ['rating' => $request->rating]
        );

        return redirect()->back();
    }
}
