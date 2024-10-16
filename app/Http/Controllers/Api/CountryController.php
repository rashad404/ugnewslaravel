<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return response()->json($countries);
    }
}