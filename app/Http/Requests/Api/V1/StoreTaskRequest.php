<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

final class StoreTaskRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'status' => ['nullable','enum:' . \App\Enums\TaskStatusEnum::class],
            'due_date' => ['nullable','date','after_or_equal:today','date_format:Y-m-d'],
            'assigned_to' => ['nullable','array'],
            'assigned_to.*' => ['exists:users,id','not_in:' . auth('api')->id()],
        ];
    }
}
