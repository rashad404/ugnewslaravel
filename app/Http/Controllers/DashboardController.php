<?php

namespace App\Http\Controllers;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $recentNews = $user->news()->orderBy('id', 'desc')->take(5)->get();
        $channelCount = $user->channels()->count();
    
        return view('user.dashboard', compact('recentNews', 'channelCount'));
    }
}