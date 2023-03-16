<?php

namespace App\Console\Commands;

use App\Models\Advertiser;
use App\Models\Appointment;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class GenerateDailyStatisticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate statistics for each advertiser appointments done on the last day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = now()->yesterday()->format('Y-m-d');

        $query = Advertiser::whereHas('appointments', function (Builder $query) use ($date) {
            $query->where('status', Appointment::STATUS_COMPLETED)->where('date',  $date);
        });

        $advertiserWithAppointmentsDone = $query->with(['appointments' => function ($query) use ($date) {
            $query->where('status', Appointment::STATUS_COMPLETED)->where('date',  $date);
        }])->get();

        $advertiserWithAppointmentsDone->each(function ($advertiser) use ($date) {
            $advertiser->statistics()->create([
                'date' => $date,
                'earnings' => $advertiser->appointments->sum('price'),
                'worked_hours' => $advertiser->appointments->sum('duration'),
                'performed_services' => $advertiser->appointments->count(),
            ]);
        });

        $this->info(sprintf("%s had their earnings generated", $advertiserWithAppointmentsDone->count()));

        return 0;
    }
}
