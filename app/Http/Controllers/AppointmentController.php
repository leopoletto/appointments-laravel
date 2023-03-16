<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Services\Advertiser\AdvertiserServiceContract;
use App\Services\Appointment\AppointmentServiceContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $appointmentService;
    protected $advertiserService;

    public function __construct(
        AppointmentServiceContract $appointmentService,
        AdvertiserServiceContract $advertiserService
    ) {
        $this->appointmentService = $appointmentService;
        $this->advertiserService = $advertiserService;
    }

    public function index(Request $request, $advertiserUuid): JsonResponse
    {
        try {
            $appointments = $this->appointmentService->getAdvertiserAppointments(
                $advertiserUuid,
                $request->get('period')
            );
            return new JsonResponse($appointments->toArray());
        } catch (ModelNotFoundException $exception) {
            $data = ['message' => $exception->getMessage()];
            return new JsonResponse($data, JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function store(StoreAppointmentRequest $request, $advertiserUuid): JsonResponse
    {
        try {
            $availability = $this->advertiserService->getAdvertiserAvailability(
                $advertiserUuid,
                $request->date,
                $request->start_time,
                $request->finish_time
            );

            $appointmentData = array_merge([
                'advertiser_id' => $advertiserUuid,
                'price' => $availability->price,
            ], $request->validated());

            $appointment = $this->appointmentService->createAppointment($appointmentData);
            return new JsonResponse($appointment->toArray(), JsonResponse::HTTP_CREATED);
        } catch (ModelNotFoundException $exception) {
            $data = ['message' => $exception->getMessage()];
            return new JsonResponse($data, JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function cancel($appointmentUuid): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->cancelAppointment($appointmentUuid);
            return new JsonResponse($appointment->toArray());
        } catch (ModelNotFoundException $exception) {
            $data = ['message' => $exception->getMessage()];
            return new JsonResponse($data, JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function start($appointmentUuid): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->startService($appointmentUuid);
            return new JsonResponse($appointment->toArray());
        } catch (ModelNotFoundException $exception) {
            $data = ['message' => $exception->getMessage()];
            return new JsonResponse($data, JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function finish($appointmentUuid): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->finishService($appointmentUuid);
            return new JsonResponse($appointment->toArray());
        } catch (ModelNotFoundException $exception) {
            $data = ['message' => $exception->getMessage()];
            return new JsonResponse($data, JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
