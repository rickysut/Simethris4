<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\DataAdministrator;
use Illuminate\Foundation\Inspiring;
use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{
	

	public function index()
    {
        $posts = Post::all();
        $users = User::all();
        $user = User::with('post')->get();
        $module_name = 'Beranda Mobile';
        $page_title = 'Beranda Mobile';
        $page_heading = 'Welcome Mobile';
        $heading_class = 'fal fa-ballot-check';
        $quote = Inspiring::quote();

        if (Auth::user()->roleaccess != '1') {
            $posts = Post::latest()
                ->limit(5)
                ->whereNotNull('published_at')
                ->get();
        } else {
            $posts = Post::orderBy('created_at', 'desc')->limit(5)->get();
        }

        $me = Auth::user();
        $profile = DataAdministrator::where('user_id', $me->id)->first() ?? new DataAdministrator();

        return view('t2024.landing.indexusermobile', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'quote', 'posts', 'user', 'users', 'profile'));
    }
}
