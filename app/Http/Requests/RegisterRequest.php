<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('PATCH')) {
            return [
                'payment_status' => 'sometimes|in:pending,confirmed,rejected',
                'payment_date' => 'sometimes|nullable|date',
                'has_discount' => 'sometimes|nullable|boolean',
                'comments' => 'sometimes|nullable|string|max:255',
            ];
        }

        $rules = [
            'team_name' => 'required|string|max:255',
            'robot_name' => 'required|string|max:255',
            'education_level' => 'required|in:high_school,university',
            'institution' => 'required|string|max:255',
            'personal_email' => 'required|email|max:255',
            'institutional_email' => 'required|email|max:255',
            'comments' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'has_discount' => 'nullable|boolean',
            'payment_date' => 'nullable|date',
            'payment_status' => 'required|in:pending,confirmed,rejected',
        ];

        if ($this->isMethod('POST')) {
            $rules['team_members'] = 'required|array|between:1,4';
            $rules['team_members.*.name'] = 'required|string|max:255';
            $rules['team_members.*.personal_email'] = 'required|email|max:255';
            $rules['team_members.*.institutional_email'] = 'required|email|max:255';
            unset($rules['payment_status']);
        }

        return $rules;
    }

    public function withValidator(Validator $validator): void
    {
        if (! $this->isMethod('PATCH')) {
            return;
        }

        $validator->after(function (Validator $v): void {
            $allowed = ['payment_status', 'payment_date', 'has_discount', 'comments'];
            $input = $this->all();
            $sent = collect($allowed)->filter(fn (string $key) => array_key_exists($key, $input));

            if ($sent->isEmpty()) {
                $v->errors()->add(
                    'payment_status',
                    'En PATCH envía al menos uno de: payment_status, payment_date, has_discount, comments.'
                );
            }
        });
    }
}
