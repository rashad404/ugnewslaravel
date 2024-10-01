<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherTable extends Migration
{
    public function up()
    {
        Schema::create('weather', function (Blueprint $table) {
            $table->id();
            $table->string('city_name');                 // City name
            $table->float('temp');                       // Current temperature
            $table->float('feels_like');                 // Feels like temperature
            $table->float('temp_min');                   // Minimum temperature
            $table->float('temp_max');                   // Maximum temperature
            $table->integer('pressure');                 // Pressure in hPa
            $table->integer('humidity');                 // Humidity in percentage
            $table->integer('visibility')->nullable();   // Visibility in meters
            $table->float('wind_speed');                 // Wind speed in meters/sec
            $table->integer('wind_deg');                 // Wind direction in degrees
            $table->integer('cloudiness')->nullable();   // Cloudiness in percentage
            $table->string('weather_main');              // Main weather condition (e.g., "Clouds")
            $table->string('weather_description');       // Description of weather (e.g., "few clouds")
            $table->string('weather_icon');              // Icon ID for the weather condition
            $table->timestamp('sunrise')->nullable();    // Sunrise time
            $table->timestamp('sunset')->nullable();     // Sunset time
            $table->string('country', 2);                // Country code
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('weather');
    }
}
