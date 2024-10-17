<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\NamazTime;

class NamazTimeController extends Controller
{

    public function index()
    {
        $namaz_times = NamazTime::all();
        return view('site.namaz_time.index', ['namaz_times' => $namaz_times]);
    }

}
