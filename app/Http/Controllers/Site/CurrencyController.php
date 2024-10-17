<?php

namespace App\Http\Controllers\Site;

use App\Helpers\Seo;
use App\Http\Controllers\Controller;
use App\Models\Currency;

class CurrencyController extends Controller
{

    public function index()
    {
        $data = Seo::currencies();
        $data['currencies'] = Currency::all();
        return view('site.currency.index', $data);
    }

}
