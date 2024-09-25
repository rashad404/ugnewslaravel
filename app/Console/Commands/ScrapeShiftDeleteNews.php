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
    protected $description = 'Scrapes one news from ShiftDelete.Net, translates and rewrites using Google Gemini API, and inserts into the database if not already added';

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

        // Step 2: Get the first news post
        $newsNode = $crawler->filter('.post.style1.format-standard')->first();
        $title = $newsNode->filter('.post-title a')->text();
        $newsUrl = $newsNode->filter('.post-title a')->attr('href');
        $excerpt = $newsNode->filter('.post-excerpt p')->text();
        // $image = $newsNode->filter('img')->attr('src');
        $image = 'defaults/news.jpg';
        $category = $newsNode->filter('.post-category a')->text();

        $channelId = 1; // Assuming a default channel for now
        $slug = $this->generateSlug($title, $channelId);

        // Step 3: Check if the news is already in the database
        if (!News::where('slug', $slug)->exists()) {
            // Step 4: Fetch full news details by visiting the news URL
            $newsDetailsResponse = $client->request('GET', $newsUrl);
            $newsDetailsContent = $newsDetailsResponse->getBody()->getContents();
            $newsDetailsCrawler = new Crawler($newsDetailsContent);
            $fullText = $newsDetailsCrawler->filter('.post-content')->text();

            // Step 5: Translate and rewrite using Google Gemini API
            $generatedData = $this->getDataFromGemini($title, $fullText);
            $title = $generatedData['title'];
            $fullText = $generatedData['text'];
            $tags = $generatedData['tags'];

            // Step 6: Prepare and insert the news data into the database
            $newsData = [
                'title' => $title,
                'title_extra' => '',
                'text' => $fullText,
                'tags' => $tags,
                'image' => $image,
                'thumb' => $image,
                'position' => 0,
                'cat' => 8,
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
            $this->info("Inserted translated and rewritten news: {$title}");
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

    /**
     * Sends data to Google Gemini API and retrieves rewritten and translated text with HTML formatting and related tags.
     */
    private function getDataFromGemini($title, $fullText)
    {
        $client = new \GuzzleHttp\Client();
        $geminiApiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent'; // Google Gemini API endpoint
        $apiKey = 'AIzaSyAlabvTPjW-cjXdBTBTBE7pae_whJoiFtM'; // Replace with your actual API key

        // Prepare the request payload with a custom prompt
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Translate the title and text to Azerbaijani. Rewrite to avoid duplication but keep the original meaning. Format with HTML tags (<p>, <b>, <strong>, etc.) for readability and SEO. Add <br/> after each </p>. Provide SEO-friendly tags in Azerbaijani at the end.
                            There should be no unrelated content in the translation, such as add your comment etc.
Return in this format:
newTitle: [plain text]
newText: [HTML formatted]
newTags: [comma-separated list]
Here is the original content:
Title: {$title}
Text: {$fullText}
"
                        ]
                    ]
                ]
            ]
        ];

        try {
            // Send the request to Google Gemini API
            $response = $client->post($geminiApiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'key' => $apiKey,
                ],
                'json' => $payload,
            ]);

            // Parse the response
            $responseBody = json_decode($response->getBody(), true);
            // dd($responseBody); // Debugging: See what the API returned
            
            // Assuming the response returns a single string with "newTitle:...", "newText:...", and "newTags:..."
            if (isset($responseBody['candidates'][0]['content']['parts'][0]['text'])) {
                $responseText = $responseBody['candidates'][0]['content']['parts'][0]['text'];

                // Use regex to extract newTitle, newText, and newTags from the response
                $newTitlePattern = '/newTitle:(.*?)newText:/s';
                $newTextPattern = '/newText:(.*?)newTags:/s';
                $newTagsPattern = '/newTags:(.*)/s';

                // Extract title
                preg_match($newTitlePattern, $responseText, $titleMatches);
                $newTitle = isset($titleMatches[1]) ? trim($titleMatches[1]) : $title;

                // Extract text
                preg_match($newTextPattern, $responseText, $textMatches);
                $newFullText = isset($textMatches[1]) ? trim($textMatches[1]) : $fullText;

                // Extract tags
                preg_match($newTagsPattern, $responseText, $tagsMatches);
                $newTags = isset($tagsMatches[1]) ? trim($tagsMatches[1]) : '';

                // Return the rewritten and translated data
                return [
                    'title' => $newTitle,
                    'text' => $newFullText,
                    'tags' => $newTags,
                ];
            }

            // Fallback in case the API returns unexpected content
            return [
                'title' => $title,
                'text' => $fullText,
                'tags' => '',
            ];

        } catch (\Exception $e) {
            $this->error('Error communicating with Google Gemini API: ' . $e->getMessage());

            // Fallback: return original title and fullText in case of error
            return [
                'title' => $title,
                'text' => $fullText,
                'tags' => '',
            ];
        }
    }




}
