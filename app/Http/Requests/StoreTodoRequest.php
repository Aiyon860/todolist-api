<?php

namespace App\Http\Requests;

use App\Enums\TodoPriority;
use App\Enums\TodoStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
                 'required',
                 Rule::string()->min(1)->max(255)
             ],
             'assignee' => [
                 'nullable',
                 Rule::string()->min(1)->max(255)
             ],
             'due_date' => [
                 'required',
                 Rule::date()->todayOrAfter()
             ],
             'time_tracked' => [
                 'sometimes',
                 Rule::numeric()->min(0)
             ],
             'status' => [
                 'sometimes',
                 Rule::enum(TodoStatus::class)
             ],
             'priority' => [
                 'required',
                 Rule::enum(TodoPriority::class)
             ]
         ];
     }
}
