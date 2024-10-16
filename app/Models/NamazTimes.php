<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NamazTimes extends Model
{
    protected $table = 'namaz_times';

    protected $fillable = [
        'day', 'hijri_day', 'week_day', 'imsak', 'fajr', 'sunrise',
        'dhuhr', 'asr', 'maghrib', 'isha', 'midnight'
    ];

    public static function getAllTimes()
    {
        return self::orderBy('day', 'asc')->get();
    }

    public static function getTodayTimes()
    {
        $today = now()->day;
        return self::where('day', $today)->first();
    }
}