<?php

namespace Tests\Commands;

use App\Models\Advertiser;
use App\Models\Appointment;
use App\Models\Consumer;
use App\Models\Statistic;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;

class GenerateDailyStatisticsCommandTest extends TestCase
{
    public function test_it_generate_statistics()
    {
        $advertiser = Advertiser::factory()->create();

        $appointments = Appointment::factory()
            ->state(new Sequence(
                [
                    'date' => now()->format('Y-m-d'),
                    'start_time' => now()->startOfDay()->addHours(10)->format('H:i:s'),
                    'finish_time' => now()->startOfDay()->addHours(11)->format('H:i:s'),
                    'duration' => 1,
                    'price' => 150,
                    'status' => Appointment::STATUS_COMPLETED,
                ],
                [
                    'date' => now()->format('Y-m-d'),
                    'start_time' => now()->startOfDay()->addHours(12)->format('H:i:s'),
                    'finish_time' => now()->startOfDay()->addHours(14)->format('H:i:s'),
                    'duration' => 2,
                    'price' => 280,
                    'status' => Appointment::STATUS_COMPLETED,
                ],
                [
                    'date' => now()->format('Y-m-d'),
                    'start_time' => now()->startOfDay()->addHours(14)->format('H:i:s'),
                    'finish_time' => now()->startOfDay()->addHours(17)->format('H:i:s'),
                    'duration' => 3,
                    'price' => 410,
                    'status' => Appointment::STATUS_COMPLETED,
                ]
            ))
            ->for(Consumer::factory())
            ->for($advertiser)
            ->count(3)
            ->create();
            
        $this->travelTo(now()->tomorrow());

        $this->artisan('statistics:generate');

        $this->travelBack();

        $statistic = $advertiser->statistics()->where('date', now()->format('Y-m-d'))->first();
                    
        $this->assertEquals(840, $statistic->earnings);
        $this->assertEquals(6,  $statistic->worked_hours);
        $this->assertEquals(3, $statistic->performed_services);
    }
}
