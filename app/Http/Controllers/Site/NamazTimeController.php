<?php

namespace App\Http\Controllers\Site;

use App\Helpers\Seo;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\NamazTime;

class NamazTimeController extends Controller
{

    public function index()
    {
        $data = Seo::namaz_times();
        $data['namaz_times'] = NamazTime::all();
        return view('site.namaz_time.index', $data);
    }

}
