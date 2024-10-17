<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\NamazTime;
use App\Models\Channel;
use App\Models\News;
use App\Models\City;
use App\Helpers\Seo;
use App\Models\Ad;
use App\Models\Category;

class SiteController extends Controller
{
    public function index()
    {

        $data = Seo::index();
        $data['usdRate'] = Currency::getUsdRate();
        $data['bakuWeatherInfo'] = $this->getWeatherInfo();
        $data['todayNamaz'] = NamazTime::getTodayTimes();
        $data['region'] = session('region', 16);

        $data['channelList'] = Channel::getTopChannels(10);
        $data['newsList'] = News::where("status", 1)
                        ->orderBy('publish_time', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->paginate(15);

        $data['cityList1'] = City::getMainCities();
        $data['cityList2'] = City::getSecondaryCities();


        return view('site.index', $data);
    }

    public function contact()
    {
        return view('site.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        // Save or send the contact message here
        return redirect()->route('site.contact')->with('success', 'Your message has been sent successfully!');
    }

    public function cat($name)
    {
        $category = Category::where('name', $name)->first();

        if ($category) {
            $categoryId = $category->id;

            $data['newsList'] = News::where('category_id', $categoryId)
                ->where("status", 1)
                ->orderBy('publish_time', 'DESC')
                ->orderBy('id', 'DESC')
                ->paginate(24);
            $data['cat_name'] = News::getCatName($categoryId);

            $data['metaTitle'] = __($data['cat_name']) . ' Xəbərləri, ' . __($data['cat_name']) . ' xeberleri';
            $data['metaKeywords'] = __($data['cat_name']) . ' Xəbərləri, ' . __($data['cat_name']) . ' xeberleri';
            $data['metaDescription'] = __($data['cat_name']) . ' Xəbərləri, ' . __($data['cat_name']) . ' xeberleri';

            return view('site.cat', $data);
        } else {
            return abort(404);
        }
    }


    public function tags($name)
    {
        $name = urldecode($name);
        $data = [
            'title' => "{$name} Xəbərləri, {$name} xeberleri",
            'keywords' => "{$name} Xəbərləri, {$name} xeberleri",
            'description' => "{$name} Xəbərləri, {$name} xeberleri",
            'list' => News::getListByTag($name)->paginate(24),
            'cat_name' => $name,
        ];

        return view('site.tags', $data);
    }

    public function city($id)
    {
        $cityName = City::getName($id);
        $data = Seo::city($cityName);
        $data['list'] = News::getListByCity($id)->paginate(24);
        $data['name'] = $cityName;

        return view('site.city', $data);
    }

    public function newsInner($slug_part_1, $slug_part_2)
    {
        $slug = "$slug_part_1/$slug_part_2";
        $newsItem = News::where('slug', $slug)->firstOrFail();

        $data = [
            'item' => $newsItem,
            'meta_img' => $newsItem->image,
            'title' => $newsItem->title,
            'keywords' => $newsItem->title,
            'description' => $newsItem->title,
            'next_item' => News::navigate($newsItem->id, 'next'),
            'previous_item' => News::navigate($newsItem->id, 'previous'),
            'list' => News::getSimilarNews($newsItem->id, 5),
            'ad' => Ad::getItem(),
        ];

        return view('site.news_inner', $data);
    }

    public function about()
    {
        $data = Seo::general();
        return view('site.about', $data);
    }

    public function privacy()
    {
        $data = Seo::general();
        return view('site.privacy', $data);
    }

    public function dataDeletion()
    {
        $data = Seo::general();
        return view('site.data_deletion', $data);
    }

    public function refund()
    {
        $data = Seo::general();
        return view('site.refund', $data);
    }

    public function createChannel()
    {
        $data = Seo::create_channel();
        return view('help.create_channel', $data);
    }

    private function getWeatherInfo()
    {
        // Placeholder for weather API integration
        return '25°C';
    }
}
