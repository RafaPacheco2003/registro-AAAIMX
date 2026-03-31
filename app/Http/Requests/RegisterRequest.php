<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        ];

        if ($this->isMethod('POST')) {
            $rules['team_members'] = 'required|array|between:1,4';
            $rules['team_members.*.name'] = 'required|string|max:255';
            $rules['team_members.*.personal_email'] = 'required|email|max:255';
            $rules['team_members.*.institutional_email'] = 'required|email|max:255';
        }

        return $rules;
    }
}
