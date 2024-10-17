<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

use App\Models\News;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    public function subscribe($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $user = Auth::user();
        $channel = Channel::findOrFail($id);

        if ($user->subscriptions()->where('channel_id', $id)->exists()) {
            $user->subscriptions()->detach($id);
            $channel->decrement('subscribers');
            return __('Subscribe');
        } else {
            $user->subscriptions()->attach($id);
            $channel->increment('subscribers');
            return __('Subscribed');
        }
    }

    public function unSubscribe($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $user = Auth::user();
        $channel = Channel::findOrFail($id);

        $user->subscriptions()->detach($id);
        $channel->decrement('subscribers');
        return __('Subscribe');
    }

    public function like($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $user = Auth::user();
        $news = News::findOrFail($id);

        if ($user->likes()->where('news_id', $id)->exists()) {
            $user->likes()->updateExistingPivot($id, ['liked' => 1, 'disliked' => 0]);
        } else {
            $user->likes()->attach($id, ['liked' => 1]);
        }

        $news->increment('likes');
        return __('Liked');
    }

    public function removeLike($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $user = Auth::user();
        $news = News::findOrFail($id);

        $user->likes()->updateExistingPivot($id, ['liked' => 0]);
        $news->decrement('likes');
        return __('Like');
    }

    public function dislike($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $user = Auth::user();
        $news = News::findOrFail($id);

        if ($user->likes()->where('news_id', $id)->exists()) {
            $user->likes()->updateExistingPivot($id, ['liked' => 0, 'disliked' => 1]);
        } else {
            $user->likes()->attach($id, ['disliked' => 1]);
        }

        $news->increment('dislikes');
        return __('Disliked');
    }

    public function removeDislike($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $user = Auth::user();
        $news = News::findOrFail($id);

        $user->likes()->updateExistingPivot($id, ['disliked' => 0]);
        $news->decrement('dislikes');
        return __('Dislike');
    }
}