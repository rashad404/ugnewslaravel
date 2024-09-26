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

abstract class BaseNewsScraper extends Command
{
    protected $sourceName; // e.g., 'ShiftDelete.Net'
    protected $sourceUrl;  // e.g., 'https://en.shiftdelete.net/news/'

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $client = new Client();
        $url = $this->getNewsListUrl();

        // Step 1: Make the HTTP request to fetch news list
        $response = $client->request('GET', $url);
        $htmlContent = $response->getBody()->getContents();
        $crawler = new Crawler($htmlContent);

        // Step 2: Get the news data
        $newsData = $this->parseNewsList($crawler);

        $title = $newsData['title'];
        $newsUrl = $newsData['newsUrl'];
        $excerpt = $newsData['excerpt'];
        // $image = $newsData['image'] ?? 'defaults/news.jpg';
        $image = 'defaults/news.jpg';
        $category = $newsData['category'];

        $channelId = 1;
        $source = $this->sourceName;

        // We will use to check news uniqueness
        $uniqueness = substr(md5($title . $source . date("Y-m")), 0, 15);

        // Step 3: Check if the news is already in the database
        if (!News::where('uniqueness', $uniqueness)->exists()) {
            // Step 4: Fetch full news details by visiting the news URL
            $newsDetailsResponse = $client->request('GET', $newsUrl);
            $newsDetailsContent = $newsDetailsResponse->getBody()->getContents();
            $newsDetailsCrawler = new Crawler($newsDetailsContent);

            $fullText = $this->parseNewsDetails($newsDetailsCrawler);

            // Step 5: Translate and rewrite using Google Gemini API
            $generatedData = $this->getDataFromGemini($title, $fullText);
            $title = $generatedData['title'];
            $fullText = $generatedData['text'];
            $tags = $generatedData['tags'];
            $slug = $this->generateSlug($title, $channelId);

            // Step 6: Prepare and insert the news data into the database
            $newsEntryData = [
                'title'         => $title,
                'title_extra'   => '',
                'text'          => $fullText,
                'tags'          => $tags,
                'image'         => $image,
                'thumb'         => $image,
                'position'      => 0,
                'cat'           => 8,
                'channel'       => $channelId,
                'source'        => $source,
                'country'       => 16,
                'city'          => 0,
                'language'      => 1,
                'status'        => 1,
                'time'          => Carbon::now()->timestamp,
                'publish_time'  => Carbon::now()->timestamp,
                'view'          => 1,
                'likes'         => 0,
                'dislikes'      => 0,
                'partner_id'    => 1,
                'slug'          => $slug,
                'uniqueness'    => $uniqueness,
            ];

            News::create($newsEntryData);
            $this->info("Inserted translated and rewritten news: {$title}");
        } else {
            $this->info("No new news to insert.");
        }
    }

    /**
     * Get the news list URL.
     */
    abstract protected function getNewsListUrl();

    /**
     * Parse the news list and return the news data.
     */
    abstract protected function parseNewsList(Crawler $crawler);

    /**
     * Parse the news details page and return the full text.
     */
    abstract protected function parseNewsDetails(Crawler $crawler);

    /**
     * Get the channel's name_url from the channels table.
     */
    protected function getChannelNameUrl($channelId)
    {
        $channel = Channel::find($channelId);
        return $channel ? $channel->name_url : 'default-channel';
    }

    /**
     * Generate a slug from the title using Url::generateSafeSlug().
     */
    protected function generateSlug($title, $channelId)
    {
        $channelNameUrl = $this->getChannelNameUrl($channelId);
        return $channelNameUrl . '/' . Url::generateSafeSlug($title);
    }

    /**
     * Sends data to Google Gemini API and retrieves rewritten and translated text with HTML formatting and related tags.
     */
    protected function getDataFromGemini($title, $fullText)
    {
        $client = new Client();
        $geminiApiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent'; // Google Gemini API endpoint
        $apiKey = 'AIzaSyAlabvTPjW-cjXdBTBTBE7pae_whJoiFtM'; // Replace with your actual API key

        // Prepare the request payload with a custom prompt
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        [
'text' => "Translate to Azerbaijani:
- Convey meaning, not word-for-word
- Use Azerbaijani-specific language and cultural references
- Avoid confusion with Turkish
- Format with HTML tags (<p>, <b>, <strong>)
- After each closing </p> tag, add two line breaks: </p><br/>
- No unrelated content or comments
- No reference to other websites, or comments section etc, this should be pure news text, nothing else.

Return:
newTitle: [plain text]
newText: [HTML formatted]
newTags: [comma-separated list in Azerbaijani, letters, space, dash, and numbers only]

Original:
Title: {$title}
Text: {$fullText}"
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
                    'text'  => $newFullText,
                    'tags'  => $newTags,
                ];
            }

            // Fallback in case the API returns unexpected content
            return [
                'title' => $title,
                'text'  => $fullText,
                'tags'  => '',
            ];
        } catch (\Exception $e) {
            $this->error('Error communicating with Google Gemini API: ' . $e->getMessage());

            // Fallback: return original title and fullText in case of error
            return [
                'title' => "error",
                'text'  => "error",
                'tags'  => '',
            ];
        }
    }
}
