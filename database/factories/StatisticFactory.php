<?php

namespace Database\Factories;

use App\Models\Statistic;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatisticFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Statistic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $worked = $this->faker->randomNumber(1);
        return [
            'date' => $this->faker->date(),
            'earnings' => $worked  * 150,
            'worked_hours' => $worked,
            'performed_services' => $worked,
        ];
    }
}
