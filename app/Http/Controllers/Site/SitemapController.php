<?php

namespace App\Http\Controllers\Site;

use App\Helpers\Sitemap;
use App\Http\Controllers\Controller;

class SitemapController extends Controller
{

    public function update()
    {
        $sitemapModel = new Sitemap();
        $sitemapModel->update();
        // echo 'Done!';
    }

}
