<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DeveloperController extends Controller
{
    public function index(){
        $developers = User::where('role', 'developer')
        ->orWhere('role', 'admin')
        ->withCount('follows')
        ->paginate(12);
        return view("developers",compact('developers'));
    }
}
