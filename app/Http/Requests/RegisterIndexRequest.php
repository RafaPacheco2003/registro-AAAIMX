<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'payment_status' => 'sometimes|in:pending,confirmed,rejected',
            'registration_code' => 'sometimes|string|max:255',
            'team_name' => 'sometimes|string|max:255',
            'robot_name' => 'sometimes|string|max:255',
        ];
    }
}
