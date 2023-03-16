<?php

namespace Database\Factories;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startTime = $this->faker->numberBetween(0, 20);
        $finishTime = min(23, $startTime + $this->faker->numberBetween(1, 3));

        $duration = $finishTime - $startTime;

        return [
            'date' => $this->faker->dateTimeBetween('now', '+1 week')->format('Y-m-d'),
            'start_time' => sprintf("%s:00:00", str_pad($startTime, 2, "0", STR_PAD_LEFT)),
            'finish_time' => sprintf("%s:00:00", str_pad($finishTime, 2, "0", STR_PAD_LEFT)),
            'duration' => $duration,
            'price' => $duration * 150,
            'status' => Appointment::STATUS_PENDING,
            'consumer_zip' => $this->faker->randomNumber(8),
        ];
    }

     /**
     * Starts the appointment
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function started(): Factory
    {
        return $this->state([
            'started_at' => now(),
        ]);
    }

    /**
     * Finishes the appointment
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function finished(): Factory
    {
        return $this->state([
            'finished_at' => now(),
        ]);
    }
}
