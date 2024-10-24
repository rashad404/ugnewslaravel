<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;


class UniqueView extends Model
{
    // Define the table name if different from default
    protected $table = 'unique_views';

    // Disable timestamps if not used
    public $timestamps = false;

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'user_id',
        'ip',
        'browser',
        'cookie',
        'visit_count',
        'create_time',
        'update_time',
        'news_id'
    ];

    /**
     * Check if the browser is allowed based on the user agent.
     *
     * @return bool
     */
    private static function isBrowserAllowed()
    {
        $userAgent = request()->header('User-Agent');
        $skipList = ['Wget', 'SemrushBot', 'Barkrowler', 'AhrefsBot', 'YandexBot', 'MJ12bot', 'DotBot', 'ImagesiftBot', 'ClaudeBot', 'bingbot', 'Googlebot', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'testadd'];

        foreach ($skipList as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate and track a unique view for a given news ID.
     *
     * @param int $newsId
     * @return bool
     */
    public static function calculateUniqueView($newsId)
    {
        // Check if the browser is allowed
        if (!self::isBrowserAllowed()) {
            return false;
        }
    
        // Check if the user has a cookie, or generate one
        $cookieIdentifier = request()->cookie('ugnews_uv1');
        
        if (!$cookieIdentifier) {
            // Generate a unique identifier and set the cookie for 24 hours
            $cookieIdentifier = uniqid();
            Cookie::queue(Cookie::make('ugnews_uv1', $cookieIdentifier, 1440)); // 1440 minutes = 24 hours
        }
    
        $userIP = request()->ip();
        $userAgent = request()->header('User-Agent');
        $currentTime = time();
    
        // Check if a recent record exists with the same IP, user agent, and cookie identifier
        $previousView = self::where('news_id', $newsId)
            ->where('ip', $userIP)
            ->where('browser', $userAgent)
            ->where('cookie', $cookieIdentifier)
            ->where('create_time', '>', $currentTime - 3600)
            ->first();
    
        if (!$previousView) {
            // If no recent views, create a new unique view record
            self::create([
                'ip' => $userIP,
                'browser' => $userAgent,
                'cookie' => $cookieIdentifier,
                'visit_count' => 1,
                'create_time' => $currentTime,
                'news_id' => $newsId
            ]);
    
            // Increment the news view count
            News::where('id', $newsId)->increment('view');
    
            // Increment the channel view count (assuming channel_id is a field in news)
            if ($news = News::find($newsId)) {
                Channel::where('id', $news->channel_id)->increment('view');
            }
    
            return true;
        } else {
            // Update the existing record's visit count
            $previousView->update([
                'visit_count' => $previousView->visit_count + 1,
                'update_time' => $currentTime
            ]);
    
            return false;
        }
    }
    
}
