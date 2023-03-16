<?php

namespace Database\Seeders;

use App\Models\Advertiser;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class AdvertiserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Advertiser::factory()
            ->count(30)
            ->has(Schedule::factory()->sequenceWeekday()->count(7))
            ->create();
    }
}
