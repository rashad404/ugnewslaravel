<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNamazTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('namaz_times', function (Blueprint $table) {
            $table->id();
            $table->integer('day');
            $table->string('hijri_day');
            $table->string('week_day');
            $table->string('imsak');  // Storing time as string
            $table->string('fajr');   // Storing time as string
            $table->string('sunrise');  // Storing time as string
            $table->string('dhuhr');   // Storing time as string
            $table->string('asr');   // Storing time as string
            $table->string('maghrib');   // Storing time as string
            $table->string('isha');   // Storing time as string
            $table->string('midnight');   // Storing time as string
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('namaz_times');
    }
}
