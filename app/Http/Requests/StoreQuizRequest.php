<?php

namespace App\Http\Requests;

use App\Enums\QuestionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
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
     * @throws \ReflectionException
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'string|required|min:5|max:255|unique:quizzes,title',
            'questions' => 'required|array',
            'questions.*.type' => 'required|in:' . QuestionTypeEnum::implode(),
            'questions.*.title' => 'required|string',
            'questions.*.correct_answer' => 'required',
        ];
    }
}
