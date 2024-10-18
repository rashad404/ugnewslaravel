<?php

namespace App\Helpers;

use App\Models\News;
use App\Models\Channel;
use App\Models\Weather;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class Sitemap
{
    private $limit = 50000; // Maximum URLs per sitemap file
    private $website;
    
    const SITEMAP_NS = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    public function __construct()
    {
        $host = $_SERVER['HTTP_HOST'];
        $this->website = 'https://' . $host;
    }

    /**
     * Main method to update the sitemap
     */
    public function update()
    {
        // Retrieve static and dynamic links
        $staticLinks = $this->getStaticLinks();
        $channelLinks = $this->fetchChannelLinks();
        $newsLinks = $this->fetchNewsLinks();
        $weatherLinks = $this->fetchWeatherLinks();
        
        // Merge all links into one array
        $allLinks = array_merge($staticLinks, $weatherLinks, $channelLinks, $newsLinks);

        // Ensure uniqueness based on URL
        $allLinks = collect($allLinks)->unique('url')->values()->toArray();

        $totalLinks = count($allLinks);
        $sitemapCount = ceil($totalLinks / $this->limit);
        $sitemaps = [];

        for ($i = 0; $i < $sitemapCount; $i++) {
            $chunk = array_slice($allLinks, $i * $this->limit, $this->limit);
            $sitemapFilename = 'sitemap' . ($i + 1) . '.xml';
            $filePath = 'sitemaps/' . $sitemapFilename;

            $this->generateSitemapFile($chunk, $filePath);
            $sitemaps[] = [
                'loc'     => $this->website . '/sitemaps/' . $sitemapFilename,
                'lastmod' => Carbon::now()->toW3cString(),
            ];
        }

        $this->generateSitemapIndex($sitemaps);

        // Optional: Notify search engines
        // $this->pingSearchEngines($this->website . '/sitemaps/sitemap_index.xml');
    }

    /**
     * Get static links with custom changefreq and priority
     *
     * @return array
     */
    private function getStaticLinks()
    {
        return [
            [
                'url'        => $this->website,
                'changefreq' => 'hourly',
                'priority'   => '1.0',
            ],
            [
                'url'        => $this->website . '/valyuta',
                'changefreq' => 'daily',
                'priority'   => '0.8',
            ],
            [
                'url'        => $this->website . '/namaz-vaxti',
                'changefreq' => 'daily',
                'priority'   => '0.8',
            ],
            [
                'url'        => $this->website . '/hava-haqqinda',
                'changefreq' => 'daily',
                'priority'   => '0.8',
            ],
        ];
    }

    /**
     * Fetch dynamic news links from the database with default changefreq and priority
     *
     * @return array
     */
    private function fetchNewsLinks()
    {
        return News::where('status', 1)
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($news) {
                return [
                    'url'        => $this->website . '/' . htmlspecialchars($news->slug, ENT_QUOTES, 'UTF-8'),
                    'changefreq' => 'daily',
                    'priority'   => '0.80',
                    'lastmod' => Carbon::createFromTimestamp($news->time)->toW3cString(),
                ];
            })
            ->toArray();
    }

    /**
     * Fetch dynamic weather links from the database with default changefreq and priority
     *
     * @return array
     */
    private function fetchWeatherLinks()
    {
        return Weather::orderBy('id', 'asc')
            ->get()
            ->map(function ($weather) {
                return [
                    'url'        => $this->website . '/hava-haqqinda/' . htmlspecialchars($weather->slug, ENT_QUOTES, 'UTF-8'),
                    'changefreq' => 'hourly',
                    'priority'   => '0.90',
                    'lastmod'    => $weather->updated_at->toW3cString(),
                ];
            })
            ->toArray();
    }

    /**
     * Fetch dynamic channel links from the database with default changefreq and priority
     *
     * @return array
     */
    private function fetchChannelLinks()
    {
        return Channel::where('status', 1)
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($channel) {
                return [
                    'url'        => $this->website . '/' . htmlspecialchars($channel->name_url, ENT_QUOTES, 'UTF-8'),
                    'changefreq' => 'weekly',
                    'priority'   => '0.70',
                    'lastmod' => Carbon::createFromTimestamp($channel->time)->toW3cString(),

                ];
            })
            ->toArray();
    }

    /**
     * Generate a single sitemap XML file
     *
     * @param array  $links    Array of links with their attributes
     * @param string $filePath Path to save the sitemap XML
     */
    private function generateSitemapFile(array $links, string $filePath)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns', self::SITEMAP_NS);
        $dom->appendChild($urlset);

        foreach ($links as $linkData) {
            $url = $dom->createElement('url');

            // <loc>
            $loc = $dom->createElement('loc', $linkData['url']);
            $url->appendChild($loc);

            // <changefreq>
            if (isset($linkData['changefreq'])) {
                $changefreq = $dom->createElement('changefreq', $linkData['changefreq']);
                $url->appendChild($changefreq);
            }

            // <priority>
            if (isset($linkData['priority'])) {
                $priority = $dom->createElement('priority', $linkData['priority']);
                $url->appendChild($priority);
            }

            $urlset->appendChild($url);
        }

        $this->saveXmlFile($dom, $filePath);
    }

    /**
     * Generate the sitemap index XML file
     *
     * @param array $sitemaps Array of sitemap files with their locations and last modification dates
     */
    private function generateSitemapIndex(array $sitemaps)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $sitemapindex = $dom->createElement('sitemapindex');
        $sitemapindex->setAttribute('xmlns', self::SITEMAP_NS);
        $dom->appendChild($sitemapindex);

        foreach ($sitemaps as $sitemap) {
            $sitemapElement = $dom->createElement('sitemap');

            // <loc>
            $loc = $dom->createElement('loc', htmlspecialchars($sitemap['loc'], ENT_QUOTES, 'UTF-8'));
            $sitemapElement->appendChild($loc);

            // <lastmod>
            $lastmod = $dom->createElement('lastmod', $sitemap['lastmod']);
            $sitemapElement->appendChild($lastmod);

            $sitemapindex->appendChild($sitemapElement);
        }

        $filePath = 'sitemaps/sitemap_index.xml';
        $this->saveXmlFile($dom, $filePath);
    }

    /**
     * Save the DOMDocument to a file
     *
     * @param \DOMDocument $dom
     * @param string $filePath
     */
    private function saveXmlFile(\DOMDocument $dom, string $filePath)
    {
        try {
            Storage::put($filePath, $dom->saveXML());
        } catch (\Exception $e) {
            Log::error('Error saving XML file: ' . $e->getMessage());
        }
    }
}
