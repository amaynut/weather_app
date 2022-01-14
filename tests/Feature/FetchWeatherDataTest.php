<?php

namespace Tests\Feature;

use App\Models\WeatherData;
use Tests\TestCase;

class FetchWeatherDataTest extends TestCase
{

    public function test_fetch_data_with_existing_location(): void
    {
        $currentCount = WeatherData::all()->count();
        $this->artisan('weather:store Montreal');
        // make sure one extra record was inserted
        $this->assertDatabaseCount(WeatherData::class, $currentCount + 1);
    }

    public function test_fetch_data_with_missing_location(): void
    {
        $currentCount = WeatherData::all()->count();
        $this->artisan('weather:store Toronto');
        // make sure no new record was added
        $this->assertDatabaseCount(WeatherData::class, $currentCount);
    }
}
