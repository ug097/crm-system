<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTimeEntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // バリデーション後にコントローラーで権限チェック
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        abort(403, '他のユーザーの工数記録は更新できません。');
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // TimeEntryに関連しないフィールドを削除
        $allowedFields = ['task_id', 'date', 'hours', 'description', '_token', '_method'];
        $input = $this->only($allowedFields);
        $this->replace($input);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'task_id' => ['nullable', 'exists:tasks,id'],
            'date' => ['required', 'date'],
            'hours' => [
                'required',
                'numeric',
                'min:0.25',
                'max:24',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $quarts = round((float) $value * 4, 0);
                    if (abs((float) $value - $quarts / 4) > 0.001) {
                        $fail('工数は0.25時間単位で入力してください。（例：0.25、0.5、1、1.25）');
                    }
                },
            ],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Get validated data with only TimeEntry-related fields.
     *
     * @return array<string, mixed>
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);
        
        // TimeEntryに関連するフィールドのみを返す
        return array_intersect_key($validated, array_flip([
            'task_id',
            'date',
            'hours',
            'description',
        ]));
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'task_id.exists' => '選択したタスクが存在しません。',
            'date.required' => '日付は必須です。',
            'date.date' => '有効な日付を入力してください。',
            'hours.required' => '工数は必須です。',
            'hours.numeric' => '工数は数値で入力してください。',
            'hours.min' => '工数は0.25時間以上である必要があります。',
            'hours.max' => '工数は24時間以下である必要があります。',
        ];
    }
}
