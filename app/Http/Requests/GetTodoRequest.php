<?php

namespace App\Http\Requests;

use App\Enums\TodoPriority;
use App\Enums\TodoStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class GetTodoRequest extends FormRequest
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
            'title' => [
                'sometimes',
                Rule::string()->min(1)->max(255)
            ],
            'assignee' => [
                'sometimes',
                Rule::string()->min(1)->max(255)
            ],
            'start' => [
                'sometimes',
                'date'
            ],
            'end' => [
                'sometimes',
                'date',
                'after_or_equal:start'
            ],
            'min' => [
                'sometimes',
                Rule::numeric()->min(0)
            ],
            'max' => [
                'sometimes',
                Rule::numeric()->min(0)
            ],
            'status' => [
                'sometimes',
                Rule::enum(TodoStatus::class)
            ],
            'priority' => [
                'sometimes',
                Rule::enum(TodoPriority::class)
            ]
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors.',
            'errors'    => $validator->errors()
        ], 422));
    }
}
