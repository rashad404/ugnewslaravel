<?php

namespace App\Console\Commands;

use App\Helpers\Url;
use App\Models\Channel;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Support\Str; // Import Str class

class ScrapeShiftDeleteNews extends Command
{
    protected $signature = 'scrape:shiftdelete-news';
    protected $description = 'Scrapes news from ShiftDelete.Net and inserts into the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $client = new Client();
        $url = 'https://en.shiftdelete.net/news/';
        
        // Make the HTTP request using Guzzle
        $response = $client->request('GET', $url);
        
        // Get the HTML content of the response
        $htmlContent = $response->getBody()->getContents();
        
        // Create a new DomCrawler instance
        $crawler = new Crawler($htmlContent);

        // Use the DomCrawler to filter and iterate over news posts
        $crawler->filter('.post.style1.format-standard')->each(function (Crawler $node) {
            // Extract data
            $title = $node->filter('.post-title a')->text();
            $url = $node->filter('.post-title a')->attr('href');
            $excerpt = $node->filter('.post-excerpt p')->text();
            $image = $node->filter('img')->attr('src');
            $category = $node->filter('.post-category a')->text();
            $date = $node->filter('.thb-date')->text();

            $channelId = 1;
            // Prepare the data for insertion into the `news` table
            $newsData = [
                'title' => $title,
                'title_extra' => $category, // Assuming the category as title extra
                'text' => $excerpt,
                'tags' => $category, // Using category as tags, you can customize this
                'image' => $image,
                'thumb' => $image, // Assuming the same image as thumbnail, you can customize this
                'position' => 0, // Default value
                'cat' => 1, // Assuming category ID 1, customize according to your needs
                'channel' => $channelId, // Assuming channel ID 1, customize according to your needs
                'source' => 'ShiftDelete.Net',
                'country' => 16, // Assuming country ID 1, customize as needed
                'city' => 0, // Assuming city ID 1, customize as needed
                'language' => 1, // Assuming language ID 1, customize as needed
                'status' => 1, // Published status
                'time' => Carbon::now()->timestamp,
                'publish_time' => Carbon::now()->timestamp,
                'view' => 1,
                'likes' => 0,
                'dislikes' => 0,
                'partner_id' => 1, // Assuming partner ID 1, customize as needed
                'slug' => $this->generateSlug($title, $channelId),
            ];

            // Insert into the database
            News::create($newsData);
        });

        $this->info('News scraping and insertion completed successfully!');
    }

    /**
     * Get the channel's name_url from the channels table
     */
    private function getChannelNameUrl($channelId)
    {
        // Fetch the channel by its ID and return its name_url
        $channel = Channel::find($channelId);
        
        if ($channel) {
            return $channel->name_url;
        }

        // Fallback to a default name if not found
        return 'default-channel';
    }

    /**
     * Generate a slug from the title using Str::slug()
     */
    private function generateSlug($title, $channelId)
    {
        // Fetch the channel name_url from the channels table
        $channelNameUrl = $this->getChannelNameUrl($channelId);


        return $channelNameUrl . '/' . Url::generateSafeSlug($title);
    
    }
}
