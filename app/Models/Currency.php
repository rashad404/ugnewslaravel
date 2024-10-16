<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';

    protected $guarded = [];

    public static function getAllCurrencies()
    {
        return self::orderBy('name', 'asc')->get();
    }

    public static function getUSDRate()
    {
        return self::where('code', 'USD')
                   ->latest('date')
                   ->first()
                   ->value ?? null;
    }
}