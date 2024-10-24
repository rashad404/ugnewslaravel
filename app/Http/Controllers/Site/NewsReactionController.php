<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsReaction;
use Illuminate\Http\Request;

class NewsReactionController extends Controller
{
    public function toggleReaction(News $news, string $type)
    {
        $user = auth()->user();
        $currentTime = time();
        
        // Get existing reaction (if any)
        $reaction = NewsReaction::where('user_id', $user->id)
            ->where('news_id', $news->id)
            ->first();
            
        if (!$reaction) {
            // No existing reaction - create new one
            NewsReaction::create([
                'user_id' => $user->id,
                'news_id' => $news->id,
                'likes' => ($type === 'like'),
                'dislikes' => ($type === 'dislike'),
                'time' => $currentTime
            ]);
            
            $news->increment($type . 's');
            $count = $news[$type.'s'];
            $message = 'added';
        } else {
            if ($reaction->{$type.'s'}) {
                // Clicking same reaction - remove it
                $reaction->delete();
                $news->decrement($type . 's');
                $count = $news[$type.'s'];
                $message = 'removed';
            } else {
                // Clicking different reaction - remove old, add new
                $oppositeType = $type === 'like' ? 'dislikes' : 'likes';
                
                // If there was an opposite reaction, decrement it
                if ($reaction->$oppositeType) {
                    $news->decrement($oppositeType);
                }
                
                // Delete old reaction
                $reaction->delete();
                
                // Create new reaction
                NewsReaction::create([
                    'user_id' => $user->id,
                    'news_id' => $news->id,
                    'likes' => ($type === 'like'),
                    'dislikes' => ($type === 'dislike'),
                    'time' => $currentTime
                ]);
                
                $news->increment($type . 's');
                $count = $news[$type.'s'];
                $message = 'changed';
            }
        }
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count,
                'likes' => $news->likes,
                'dislikes' => $news->dislikes
            ]);
        }
        
        return back();
    }
}
