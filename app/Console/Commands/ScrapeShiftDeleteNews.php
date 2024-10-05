<?php

namespace App\Console\Commands;

use Symfony\Component\DomCrawler\Crawler;

class ScrapeShiftDeleteNews extends BaseNewsScraper
{
    protected $signature = 'scrape:shiftdelete-news';
    protected $description = 'Scrapes news from ShiftDelete.Net';

    public function __construct()
    {
        parent::__construct();
        $this->sourceName = 'ShiftDelete.Net';
        $this->sourceUrl = 'https://en.shiftdelete.net/news/';
    }

    /**
     * Get the news list URL.
     */
    protected function getNewsListUrl()
    {
        return $this->sourceUrl;
    }

    /**
     * Parse the news list and return the news data.
     */
    protected function parseNewsList(Crawler $crawler)
    {
        $newsNode = $crawler->filter('.post.style1.format-standard')->first();
        $title = $newsNode->filter('.post-title a')->text();
        $newsUrl = $newsNode->filter('.post-title a')->attr('href');
        $excerpt = $newsNode->filter('.post-excerpt p')->text();
        $image = 'defaults/news.jpg'; // Adjust if needed
        // $category = $newsNode->filter('.post-category a')->text();
        $category_id = 8;

        return [
            'title'   => $title,
            'newsUrl' => $newsUrl,
            'excerpt' => $excerpt,
            'image'   => $image,
            'category_id'=> $category_id,
        ];
    }

    /**
     * Parse the news details page and return the full text.
     */
    protected function parseNewsDetails(Crawler $crawler)
    {
        $fullText = $crawler->filter('.post-content')->text();
        return $fullText;
    }
}
