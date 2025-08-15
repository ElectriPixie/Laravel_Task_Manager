<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Task;

class ReorderTasksRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'distinct', 'exists:tasks,id'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $allTaskIds = Task::pluck('id')->toArray();
            $submittedIds = $this->input('order', []);

            $missing = array_diff($allTaskIds, $submittedIds);
            $extra = array_diff($submittedIds, $allTaskIds);

            if (!empty($missing) || !empty($extra)) {
                $message = [];
                if (!empty($missing)) {
                    $message[] = 'Missing task IDs: ' . implode(', ', $missing);
                }
                if (!empty($extra)) {
                    $message[] = 'Unknown task IDs: ' . implode(', ', $extra);
                }
                $validator->errors()->add('order', implode(' | ', $message));
            }
        });
    }
}

