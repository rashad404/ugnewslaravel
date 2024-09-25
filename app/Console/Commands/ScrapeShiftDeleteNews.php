<?php

namespace App\Console\Commands;

use App\Helpers\Url;
use App\Models\Channel;
use App\Models\News;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ScrapeShiftDeleteNews extends Command
{
    protected $signature = 'scrape:shiftdelete-news';
    protected $description = 'Scrapes one news from ShiftDelete.Net and inserts into the database if not already added';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $client = new Client();
        $url = 'https://en.shiftdelete.net/news/';

        // Step 1: Make the HTTP request to fetch news list
        $response = $client->request('GET', $url);
        $htmlContent = $response->getBody()->getContents();
        $crawler = new Crawler($htmlContent);

        // Step 2: Use the DomCrawler to filter and get the first news post that is not already in the database
        $newsNode = $crawler->filter('.post.style1.format-standard')->first();
        $title = $newsNode->filter('.post-title a')->text();
        $newsUrl = $newsNode->filter('.post-title a')->attr('href');
        $excerpt = $newsNode->filter('.post-excerpt p')->text();
        $image = $newsNode->filter('img')->attr('src');
        $category = $newsNode->filter('.post-category a')->text();

        // Step 3: Check if the news is already in the database by checking the slug or title
        $channelId = 1; // Assuming a default channel for now
        $slug = $this->generateSlug($title, $channelId);

        if (!News::where('slug', $slug)->exists()) {
            // Step 4: If the news doesn't exist, fetch full news details by visiting the news URL
            $newsDetailsResponse = $client->request('GET', $newsUrl);
            $newsDetailsContent = $newsDetailsResponse->getBody()->getContents();
            $newsDetailsCrawler = new Crawler($newsDetailsContent);

            // Extract full news text (assuming it's inside a <div> with a specific class or ID, adjust this as needed)
            $fullText = $newsDetailsCrawler->filter('.post-content')->text();

            // Step 5: Prepare and insert the news data into the database
            $newsData = [
                'title' => $title,
                'title_extra' => $category,
                'text' => $fullText,
                'tags' => $category,
                'image' => $image,
                'thumb' => $image,
                'position' => 0,
                'cat' => 1,
                'channel' => $channelId,
                'source' => 'ShiftDelete.Net',
                'country' => 16,
                'city' => 0,
                'language' => 1,
                'status' => 1,
                'time' => Carbon::now()->timestamp,
                'publish_time' => Carbon::now()->timestamp,
                'view' => 1,
                'likes' => 0,
                'dislikes' => 0,
                'partner_id' => 1,
                'slug' => $slug,
            ];

            News::create($newsData);
            $this->info("Inserted news: {$title}");
        } else {
            $this->info("No new news to insert.");
        }
    }

    /**
     * Get the channel's name_url from the channels table
     */
    private function getChannelNameUrl($channelId)
    {
        $channel = Channel::find($channelId);
        return $channel ? $channel->name_url : 'default-channel';
    }

    /**
     * Generate a slug from the title using Str::slug()
     */
    private function generateSlug($title, $channelId)
    {
        $channelNameUrl = $this->getChannelNameUrl($channelId);
        return $channelNameUrl . '/' . Url::generateSafeSlug($title);
    }
}
