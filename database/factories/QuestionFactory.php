<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class QuestionFactory extends Factory
{
    /** @var string $model */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @throws \ReflectionException
     *
     * @return array
     */
    public function definition(): array
    {
        $type = Arr::random(\App\Enums\QuestionTypeEnum::getAllValues());

        return [
            'type' => $type,
            'title' => $this->faker->text,
            'quiz_id' => Quiz::all()->pluck('id')->random(),
            'correct_answer' => $type === \App\Enums\QuestionTypeEnum::TEXT ? 'this is correct value' : 1,
        ];
    }
}
