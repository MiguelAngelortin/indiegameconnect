<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserFollow;

class DeveloperController extends Controller
{
    public function index(Request $request)
    {
        $developers = User::where('role', 'developer')
            ->orWhere('role', 'admin')
            ->withCount('follows')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate(12);

        $topDevelopers = User::where('role', 'developer')
            ->orWhere('role', 'admin')
            ->withCount(['follows' => function ($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            }])
            ->withCount(['ratings as positive_ratings' => function ($query) {
                $query->where('rating', 1)
                    ->where('created_at', '>=', now()->subDays(30));
            }])
            ->get()
            ->map(function ($dev) {
                $dev->score = ($dev->follows_count * 2) + $dev->positive_ratings;
                return $dev;
            })
            ->sortByDesc('score')
            ->take(3)
            ->values();
        return view("developers", compact('developers', 'topDevelopers'));
    }
}
