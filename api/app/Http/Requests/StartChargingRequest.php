<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StartChargingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'station_id' => 'required|uuid',
            'driver_token' => 'required|string|regex:/^[A-Za-z0-9\-._~]{20,80}$/',
            'callback_url' => 'required|url',
            'timeout' => 'integer|nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'driver_token.regex' => 'Driver token should be a string token of 20 to 80 characters in length. Allowed characters include: Uppercase letters ( A-Z ), Lowercase letters ( a-z ), Digits ( 0-9 ), Hyphen ( - ), period ( . ), underscore ( _ ), and tilde ( ~ )',
        ];
    }

    protected function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST)
        );
    }
}
