<?php

namespace App\Http\Requests\Api\V1\Driver;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $old_password = [];
        $confirm_new_password = [];
        $password = [];
        if($this->password){
            $password = ['required', 'min:6', 'confirmed'];
            $confirm_new_password = ['required'];
            $old_password = ["required",  function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::guard('driver-api')->user()->password)) {
                    $fail('Old password didn\'t match');
                }
            }];
        }
        return [
            'password' => $password,
            'password_confirmation' => $confirm_new_password,
            'old_password' => $old_password
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }


    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Please fill in information are required.',
            'message_details' => $validator->errors()
        ], 400));
    }
}
