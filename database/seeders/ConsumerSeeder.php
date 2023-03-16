<?php

namespace Database\Seeders;

use App\Models\Consumer;
use Illuminate\Database\Seeder;

class ConsumerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Consumer::factory()
            ->count(30)
            ->create();
    }
}
