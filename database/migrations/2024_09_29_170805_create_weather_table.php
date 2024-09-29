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
            $table->string('city_name');
            $table->string('day');
            $table->string('night_temperature');
            $table->string('day_temperature');
            $table->string('wind_condition');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('weather');
    }
}
