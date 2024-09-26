<?php

namespace App\Console\Commands;

use Symfony\Component\DomCrawler\Crawler;

class ScrapeHaberlerNews extends BaseNewsScraper
{
    protected $signature = 'scrape:haberler-news';
    protected $description = 'Scrapes news from haberler.com';

    public function __construct()
    {
        parent::__construct();
        $this->sourceName = 'Haberler.com';
        $this->sourceUrl = 'https://www.haberler.com';
    }

    protected function getNewsListUrl()
    {
        return $this->sourceUrl . '/dunya/';
    }

    protected function parseNewsList(Crawler $crawler)
    {
        $newsNode = $crawler->filter('div.p12-col')->first();

        if (!$newsNode->count()) {
            throw new \Exception('No news items found on the page.');
        }

        $newsLink = $newsNode->filter('a.boxStyle');
        $title = $newsLink->attr('title');
        $relativeNewsUrl = $newsLink->attr('href');

        if (strpos($relativeNewsUrl, 'http') === 0) {
            $newsUrl = $relativeNewsUrl;
        } else {
            $newsUrl = $this->sourceUrl . $relativeNewsUrl;
        }

        // Handle lazy-loaded images
        if ($newsLink->filter('img')->attr('data-src')) {
            $imageSrc = $newsLink->filter('img')->attr('data-src');
        } else {
            $imageSrc = $newsLink->filter('img')->attr('src');
        }

        if (strpos($imageSrc, 'http') === 0) {
            $image = $imageSrc;
        } else {
            $image = $this->sourceUrl . $imageSrc;
        }

        $excerpt = $newsLink->filter('p.hbBoxText')->text();

        return [
            'title'   => $title,
            'newsUrl' => $newsUrl,
            'excerpt' => $excerpt,
            'image'   => $image,
            'category'=> 2,
        ];
    }

    protected function parseNewsDetails(Crawler $crawler)
    {
        if ($crawler->filter('div#icerikAlani main.hbptContent.haber_metni')->count()) {
            $contentNode = $crawler->filter('div#icerikAlani main.hbptContent.haber_metni');
        } else {
            throw new \Exception('Cannot find the news content on the details page.');
        }

        $fullText = $contentNode->html();

        $fullText = $this->cleanContent($fullText);

        return $fullText;
    }

    protected function cleanContent($htmlContent)
    {
        $crawler = new Crawler($htmlContent);

        $crawler->filter('script, noscript, style')->each(function (Crawler $node) {
            $node->getNode(0)->parentNode->removeChild($node->getNode(0));
        });

        $unwantedSelectors = [
            '#div-gpt-ad-1723542782926-0',
            '.orderFlex',
            '.hbContainer',
            '#taboola-below-article-widget',
            '#detay_native_desktop',
            '#detay_native_mobile',
            '#inpage_reklam',
            // Add other selectors as needed
        ];

        foreach ($unwantedSelectors as $selector) {
            $crawler->filter($selector)->each(function (Crawler $node) {
                $node->getNode(0)->parentNode->removeChild($node->getNode(0));
            });
        }

        return $crawler->html();
    }
}
