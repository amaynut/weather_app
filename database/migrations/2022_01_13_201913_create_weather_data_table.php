<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained();
            $table->timestamp("last_updated_epoch");
            $table->dateTime("last_updated");
            $table->float("temp_c");
            $table->float("temp_f");
            $table->boolean("is_day");
            $table->string("condition_text");
            $table->text("condition_icon");
            $table->integer("condition_code")->unsigned();
            $table->float("wind_mph");
            $table->float("wind_kph");
            $table->integer("wind_degree");
            $table->string("wind_dir");
            $table->float("pressure_mb");
            $table->float("pressure_in");
            $table->float("precip_mm");
            $table->float("precip_in");
            $table->integer("humidity")->unsigned();
            $table->integer("cloud")->unsigned();
            $table->float("feelslike_c");
            $table->float("feelslike_f");
            $table->float("uv");
            $table->float("gust_mph");
            $table->float("gust_kph");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weather_data');
    }
}
