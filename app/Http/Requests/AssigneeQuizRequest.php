<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssigneeQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'url' => 'string|required|min:5|max:255|unique:quizzes,title',
            'quiz_id' => 'required|exists:quizzes,id',
            'users' => 'required|array',
            'users.*' => 'required|exists:users,id'
        ];
    }
}
