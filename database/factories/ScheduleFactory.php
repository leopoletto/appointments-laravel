<?php

namespace Database\Factories;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

class ScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startTime = $this->faker->numberBetween(0, 22);
        $finishTime = min(23, $startTime + $this->faker->numberBetween(0, 22));

        return [
            'weekday' => $this->faker->numberBetween(0, 6),
            'start_time' => sprintf("%s:00:00", str_pad($startTime, 2, "0", STR_PAD_LEFT)),
            'finish_time' => sprintf("%s:00:00", str_pad($finishTime, 2, "0", STR_PAD_LEFT)),
        ];
    }

    /**
     * Use a sequence to populate weekdays
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function sequenceWeekday(): Factory
    {
        return $this->state(new Sequence(
            ['weekday' => Carbon::SUNDAY],
            ['weekday' => Carbon::MONDAY],
            ['weekday' => Carbon::TUESDAY],
            ['weekday' => Carbon::WEDNESDAY],
            ['weekday' => Carbon::THURSDAY],
            ['weekday' => Carbon::FRIDAY],
            ['weekday' => Carbon::SATURDAY],
        ));
    }
}
