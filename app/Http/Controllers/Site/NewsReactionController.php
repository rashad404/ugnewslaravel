<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsReactionController extends Controller
{
    public function toggleReaction(News $news, string $type)
    {
        $user = auth()->user();
        $reactionType = $type === 'like' ? 'likes' : 'dislikes';
        $oppositeType = $type === 'like' ? 'dislikes' : 'likes';
        
        // Check if user has already reacted
        $existingReaction = $user->newsReactions()
            ->where('news_id', $news->id)
            ->where($reactionType, true)
            ->first();
            
        // Remove opposite reaction if exists
        $user->newsReactions()
            ->where('news_id', $news->id)
            ->where($oppositeType, true)
            ->delete();
            
        if ($existingReaction) {
            $existingReaction->delete();
            $news->decrement($reactionType);
            $count = $news->$reactionType;
        } else {
            $user->newsReactions()->create([
                'news_id' => $news->id,
                $reactionType => true
            ]);
            $news->increment($reactionType);
            $count = $news->$reactionType;
        }
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        }
        
        return back();
    }
}