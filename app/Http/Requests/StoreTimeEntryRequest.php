<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTimeEntryRequest extends FormRequest
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
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // タスクが指定されている場合、そのタスクがプロジェクトに属しているか確認
            if ($this->task_id) {
                $project = $this->route('project');
                $task = \App\Models\Task::find($this->task_id);
                
                if ($task && $task->project_id !== $project->id) {
                    $validator->errors()->add('task_id', '選択したタスクはこのプロジェクトに属していません。');
                }
            }
        });
    }
}
