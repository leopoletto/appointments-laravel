<?php

namespace App\Http\Requests;

use App\Rules\ZipCode;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        try {
            $startTime = Carbon::parse($this->start_time);
            $finishTime = Carbon::parse($this->finish_time);
        } catch (InvalidFormatException $exception) {
            return;
        }

        $this->merge([
            'duration' => $startTime->diffInHours($finishTime)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => ['required', 'date', 'date_format:Y-m-d', 'after:yesterday'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'finish_time' => ['required', 'date_format:H:i:s', 'after:start_time'],
            'duration' => ['required', 'numeric', 'max:3'],
            'consumer_id' => ['required'],
            'consumer_zip' => ['nullable', app(ZipCode::class)],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
