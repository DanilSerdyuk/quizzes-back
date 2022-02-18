<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class AnswerFactory extends Factory
{
    /** @var string $model */
    protected $model = Answer::class;

    /**
     * Define the model's default state.
     *
     * @throws \ReflectionException
     *
     * @return array
     */
    public function definition(): array
    {
        $question = Question::inRandomOrder()->get()->first();
        $value = Arr::random([$question->correct_answer, 'incorrect']);

        return [
            'user_id' => User::all()->pluck('id')->random(),
            'question_id' => $question->id,
            'value' => $value,
            'is_correct' => $value == $question->correct_answer,
        ];
    }
}
