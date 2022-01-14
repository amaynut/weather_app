<?php

namespace App\Http\Controllers;

use App\Models\Location;
use DateTime;
use Illuminate\Http\JsonResponse;
use App\Models\WeatherData;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class WeatherDataController extends Controller
{

    const BASE_URL = "http://api.weatherapi.com/v1/current.json";
    const API_KEY = "ffa3962c2e24428fae7174522221301";

    /**
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $locationParam = $request->get('location');
        // validate location
        $location = Location::where('name', '=',$locationParam)->firstOrFail();

        // fetch the data from the API
        $response = Http::get(self::BASE_URL, [
            'key' => self::API_KEY,
            'q' => $locationParam,
        ]);

        $currentWeather = json_decode($response->body(), true)['current'];

        // build and  save the model
        $weatherData = new WeatherData();
        $weatherData->last_updated_epoch = DateTime::createFromFormat( 'U', $currentWeather['last_updated_epoch'])->format('c');
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

        // return the saved data
        return new JsonResponse($weatherData, JsonResponse::HTTP_OK);
    }
}
