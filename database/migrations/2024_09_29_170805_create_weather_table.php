<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    use HasFactory;

    protected $table = 'weather';

    protected $fillable = [
        'city_name',
        'day',
        'night_temperature',
        'day_temperature',
        'wind_condition',
    ];
}
