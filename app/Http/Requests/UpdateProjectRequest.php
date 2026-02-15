<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:planning,in_progress,on_hold,completed,cancelled'],
            'managers' => ['nullable', 'array'],
            'managers.*' => ['exists:users,id'],
            'members' => ['nullable', 'array'],
            'members.*' => ['exists:users,id'],
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
            'name.required' => 'プロジェクト名は必須です。',
            'name.max' => 'プロジェクト名は255文字以内で入力してください。',
            'start_date.date' => '開始日は有効な日付を入力してください。',
            'end_date.date' => '終了日は有効な日付を入力してください。',
            'end_date.after_or_equal' => '終了日は開始日以降の日付を入力してください。',
            'status.required' => 'ステータスは必須です。',
            'status.in' => '有効なステータスを選択してください。',
            'managers.array' => 'プロジェクトマネージャーは配列形式で入力してください。',
            'managers.*.exists' => '選択したプロジェクトマネージャーが存在しません。',
            'members.array' => 'メンバーは配列形式で入力してください。',
            'members.*.exists' => '選択したメンバーが存在しません。',
        ];
    }
}
