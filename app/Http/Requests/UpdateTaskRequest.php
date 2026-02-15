<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:todo,in_progress,review,done'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'due_date' => ['nullable', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'estimated_hours' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'タスク名は必須です。',
            'title.max' => 'タスク名は255文字以内で入力してください。',
            'status.required' => 'ステータスは必須です。',
            'status.in' => '有効なステータスを選択してください。',
            'priority.required' => '優先度は必須です。',
            'priority.in' => '有効な優先度を選択してください。',
            'due_date.date' => '期限は有効な日付を入力してください。',
            'assigned_to.exists' => '選択した担当者が存在しません。',
            'estimated_hours.integer' => '見積工数は整数で入力してください。',
            'estimated_hours.min' => '見積工数は0以上である必要があります。',
        ];
    }
}
