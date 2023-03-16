<?php

namespace App\Repositories\Advertiser;

use App\DataTransferObjects\Advertiser\AdvertiserCollection;
use App\DataTransferObjects\Advertiser\AdvertiserData;
use App\Models\Advertiser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdvertiserRepositoryEloquent implements AdvertiserRepositoryContract
{
    protected $advertiser;

    public function __construct(Advertiser $advertiser)
    {
        $this->advertiser = $advertiser;
    }

    public function getAllAdvertisers(): AdvertiserCollection
    {
        return AdvertiserCollection::create($this->advertiser->all()->toArray());
    }

    public function getAdvertiserById(string $uuid): AdvertiserData
    {
        $advertiser =  $this->advertiser->find($uuid);

        if (!$advertiser) {
            throw new ModelNotFoundException('Advertiser not found');
        }

        return new AdvertiserData($advertiser->toArray());
    }

    public function checkAdvertiserAvailability(
        string $advertiserUuid,
        string $date,
        string $startTime,
        string $finishTime
    ): bool {
        $advertiser = $this->advertiser->find($advertiserUuid);

        if (!$advertiser) {
            throw new ModelNotFoundException('Advertiser not found');
        }

        $date = Carbon::parse($date);
        $startTime = Carbon::parse($startTime);
        $finishTime = Carbon::parse($finishTime);

        $isAvailable = $advertiser->schedules()
            ->where(function (Builder $query) use ($date, $startTime, $finishTime) {
                $query->where('weekday', $date->dayOfWeek)
                    ->where('start_time', '<=', $startTime->format('H:i:s'))
                    ->where('finish_time', '>=', $finishTime->format('H:i:s'));
            })->exists();

        return $isAvailable;
    }
}
