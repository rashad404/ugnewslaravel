<?php
namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;

use App\Models\Channel;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function toggle(Channel $channel)
    {
        $user = auth()->user();
        
        if ($user->isSubscribedTo($channel)) {
            $user->subscribers()->detach($channel);
            $channel->decrement('subscribers');
            $message = __('Subscribe');
        } else {
            $user->subscribers()->attach($channel, ['time' => time()]);
            $channel->increment('subscribers');
            $message = __('Subscribed');
        }
        
        if (request()->ajax()) {
            return response()->json([
                'message' => $message,
                'isSubscribed' => !$user->isSubscribedTo($channel),
                'subscriberCount' => $channel->subscribers()->count()
            ]);
        }
        
        return back();
    }
}