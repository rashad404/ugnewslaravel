<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Currency;

class CurrencyController extends Controller
{

    public function index()
    {
        $currencies = Currency::all();
        return view('site.currency.index', ['currencies' => $currencies]);
    }

}
