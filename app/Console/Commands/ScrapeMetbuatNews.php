<?php

namespace App\Console\Commands;

use Symfony\Component\DomCrawler\Crawler;

class ScrapeMetbuatNews extends BaseNewsScraper
{
    protected $signature = 'scrape:metbuat-news';
    protected $description = 'Scrapes news from metbuat.az';

    public function __construct()
    {
        parent::__construct();
        $this->sourceName = 'Metbuat.az';
        $this->sourceUrl = 'https://metbuat.az';
    }

    /**
     * Get the news list URL.
     */
    protected function getNewsListUrl()
    {
        // Assuming the main page lists the latest news
        return $this->sourceUrl;
    }

    /**
     * Parse the news list and return the news data.
     */
    protected function parseNewsList(Crawler $crawler)
    {
        // Find the first news item
        $newsNode = $crawler->filter('div.col-sm-4.col-md-4.col-lg-3')->first();

        // Check if the newsNode exists
        if (!$newsNode->count()) {
            throw new \Exception('No news items found on the page.');
        }

        // Extract data
        $newsLink = $newsNode->filter('a.news_box');
        $title = $newsLink->attr('title');
        $relativeNewsUrl = $newsLink->attr('href');
        $newsUrl = $this->sourceUrl . $relativeNewsUrl;

        $imageSrc = $newsLink->filter('img')->attr('src');
        // Ensure full image URL
        $image = $this->sourceUrl . $imageSrc;

        $category = ''; // Not specified in the given HTML

        // Excerpt is not explicitly provided; you may choose to extract a short part of the content
        $excerpt = $newsLink->filter('h4.news_box_ttl')->text();

        return [
            'title'   => $title,
            'newsUrl' => $newsUrl,
            'excerpt' => $excerpt,
            'image'   => $image,
            'category'=> $category,
        ];
    }

    /**
     * Parse the news details page and return the full text.
     */
    protected function parseNewsDetails(Crawler $crawler)
    {
        // Check if the article content exists
        if ($crawler->filter('article#maincontent')->count()) {
            $contentNode = $crawler->filter('article#maincontent');
        } else {
            throw new \Exception('Cannot find the news content on the details page.');
        }

        // Extract the HTML content
        $fullText = $contentNode->html();

        // Optional: Clean up the content if necessary
        $fullText = $this->cleanContent($fullText);

        return $fullText;
    }

    /**
     * Clean up the content by removing unwanted elements.
     */
    protected function cleanContent($htmlContent)
    {
        $crawler = new Crawler($htmlContent);

        // Remove script, noscript, style tags
        $crawler->filter('script, noscript, style')->each(function (Crawler $node) {
            $node->getNode(0)->parentNode->removeChild($node->getNode(0));
        });

        // Remove any elements with specific classes or ids that are ads or irrelevant content
        $unwantedSelectors = [
            '.fb-like',        // Facebook like button
            '#fb-root',        // Facebook root div
            '.alert',          // Alert messages
            '.news_in_details',// News details (if not required)
            '.share_btns',     // Share buttons
            '.d-mobile',       // Mobile ads
            '.d-desktop',      // Desktop ads
            '.scroll-top-wrapper', // Scroll to top button
            // Add any other selectors that are not part of the main content
        ];

        foreach ($unwantedSelectors as $selector) {
            $crawler->filter($selector)->each(function (Crawler $node) {
                $node->getNode(0)->parentNode->removeChild($node->getNode(0));
            });
        }

        // Return the cleaned HTML
        return $crawler->html();
    }
}
