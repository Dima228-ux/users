<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

/**
 * Class SignUpRequest
 * @package App\Http\Requests\Api
 */
class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:8',
                'max:250',
            ],
            'email' => [
                'email',
                Rule::unique('users', 'email'),
                'max:250',
            ],
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'max:250',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z\d.@])[^@]{8,}$/u'
            ],
            'phone' => [
                'required',
                'string',
                'regex:/(\+7|8)[- _]*\(?[- _]*(\d{3}[- _]*\)?([- _]*\d){7}|\d\d[- _]*\d\d[- _]*\)?([- _]*\d){6})+$/',
                Rule::unique('users', 'phone')->ignore(request()->id)
            ],
            'telegram' => [
                'required',
                'string',
                'regex:/.*\B@(?=\w{5,32}\b)[a-zA-Z0-9]+(?:_[a-zA-Z0-9]+)*.*+$/',
                Rule::unique('users', 'telegram')->ignore(request()->id)
            ],
        ];
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'success' => false,
                    'errors' => $validator->errors(),
                ],
                Response::HTTP_BAD_REQUEST
            )
        );
    }
}
