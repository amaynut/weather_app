<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\WeatherData;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Http;

class FetchCurrentWeather extends Command
{
    const BASE_URL = "http://api.weatherapi.com/v1/current.json";
    const API_KEY = "ffa3962c2e24428fae7174522221301";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:store {location}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the current weather from an API and store it in the DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function fetch()
    {
        $locationParam = $this->argument('location');

        try {
            // validate location
            $location = Location::where('name', '=', $locationParam)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            echo "location doesn't exist in the database";
            exit(1);
        }

        // fetch the data from the API
        $response = Http::get(self::BASE_URL, [
            'key' => self::API_KEY,
            'q' => $locationParam,
        ]);

        $currentWeather = json_decode($response->body(), true)['current'];

        // build and  save the model
        $weatherData = new WeatherData();
        $weatherData->last_updated_epoch = DateTime::createFromFormat('U', $currentWeather['last_updated_epoch'])->format('c');
        $weatherData->location_id = $location->id;
        $weatherData->last_updated = $currentWeather['last_updated'];
        $weatherData->temp_c = $currentWeather['temp_c'];
        $weatherData->temp_f = $currentWeather['temp_f'];
        $weatherData->is_day = $currentWeather['is_day'];
        $weatherData->condition_text = $currentWeather['condition']['text'];
        $weatherData->condition_icon = $currentWeather['condition']['icon'];
        $weatherData->condition_code = $currentWeather['condition']['code'];
        $weatherData->wind_mph = $currentWeather['wind_mph'];
        $weatherData->wind_kph = $currentWeather['wind_kph'];
        $weatherData->wind_degree = $currentWeather['wind_degree'];
        $weatherData->wind_dir = $currentWeather['wind_dir'];
        $weatherData->pressure_mb = $currentWeather['pressure_mb'];
        $weatherData->pressure_in = $currentWeather['pressure_in'];
        $weatherData->precip_mm = $currentWeather['precip_mm'];
        $weatherData->precip_in = $currentWeather['precip_in'];
        $weatherData->humidity = $currentWeather['humidity'];
        $weatherData->cloud = $currentWeather['cloud'];
        $weatherData->feelslike_c = $currentWeather['feelslike_c'];
        $weatherData->feelslike_f = $currentWeather['feelslike_f'];
        $weatherData->uv = $currentWeather['uv'];
        $weatherData->gust_mph = $currentWeather['gust_mph'];
        $weatherData->gust_kph = $currentWeather['gust_kph'];

        $weatherData->save();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->fetch();

        echo 'OK';
    }
}
