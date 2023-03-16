<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckAdvertiserAvailabilityRequest;
use App\Services\Advertiser\AdvertiserServiceContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class AdvertiserController extends Controller
{
    protected $advertiserService;

    public function __construct(AdvertiserServiceContract $advertiserService)
    {
        $this->advertiserService = $advertiserService;
    }

    public function index(): JsonResponse
    {
        try {
            $items = $this->advertiserService->getAllAdvertisers()->items();
            return new JsonResponse($items);
        } catch (\Throwable $exception) {
            $data = ['message' => $exception->getMessage()];
            return new JsonResponse($data, JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function show($uuid): JsonResponse
    {
        try {
            $advertiser = $this->advertiserService->getAdvertiserById($uuid);
            return new JsonResponse($advertiser);
        } catch (ModelNotFoundException $exception) {
            $data = ['message' => $exception->getMessage()];
            return new JsonResponse($data, JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function availability(CheckAdvertiserAvailabilityRequest $request, $uuid): JsonResponse
    {
        try {
            $data = [$uuid, $request->date, $request->start_time, $request->finish_time];
            $availability = $this->advertiserService->getAdvertiserAvailability(...$data);
            return new JsonResponse($availability);
        } catch (ModelNotFoundException $exception) {
            $data = ['message' => $exception->getMessage()];
            return new JsonResponse($data, JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function notifications($uuid): JsonResponse
    {
        try {
            $notifications = $this->advertiserService->getAdvertiserNotifications($uuid);
            return new JsonResponse($notifications->toArray());
        } catch (ModelNotFoundException $exception) {
            $data = ['message' => $exception->getMessage()];
            return new JsonResponse($data, JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
