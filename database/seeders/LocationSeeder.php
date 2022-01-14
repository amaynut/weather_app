<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $location = new Location([
            'name' => 'Montreal',
            'region' => 'Quebec',
            'country' => 'Canada',
            'lat' => 45.5,
            'lon' => -73.58,
            'tz_id' => 'America/Toronto',
        ]);

        $location->save();
    }
}
