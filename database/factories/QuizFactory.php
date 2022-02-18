<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class QuizFactory extends Factory
{
    /** @var string $model */
    protected $model = Quiz::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $title = $this->faker->text;

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'user_id' => User::all()->pluck('id')->random(),
        ];
    }
}
