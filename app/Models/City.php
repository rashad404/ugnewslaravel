<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;

class City extends Model
{
    protected $table = 'cities';

    public $timestamps = false;  // Since the table doesn't have timestamp columns

    protected $guarded = [];

    public static function getList($limit = 100)
    {
        $countryId = Cookie::get('country', config('app.default_country'));
        return self::where('country_id', $countryId)
                   ->where('status', 1)
                   ->orderBy('name', 'asc')
                   ->limit($limit)
                   ->get();
    }

    public static function getMainCities()
    {
        $countryId = Cookie::get('country', config('app.default_country'));
        return self::where('country_id', $countryId)
                   ->where('status', 1)
                   ->orderBy('id', 'asc')
                   ->limit(6)  // Assuming the first 6 cities are "main" cities
                   ->get();
    }

    public static function getSecondaryCities()
    {
        $countryId = Cookie::get('country', config('app.default_country'));

        return self::where('country_id', $countryId)
                   ->where('status', 1)
                   ->orderBy('id', 'asc')
                   ->skip(6)  // Skip the first 6 "main" cities
                   ->take(100)  // Get the next 100 cities as "secondary"
                   ->get();
    }

    public static function getName($id)
    {
        $city = self::find($id);
        return $city ? $city->name : '';
    }
}