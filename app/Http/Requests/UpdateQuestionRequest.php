<?php

namespace App\Http\Requests;

use App\Enums\QuestionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
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
            'type' => 'required|in:' . QuestionTypeEnum::implode(),
            'title' => 'required|string|max:255',
            'correct_answer' => 'required',
        ];
    }
}
