<?php

namespace App\Services;

use App\Mail\StudentAnswers;
use App\Models\Answer;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AnswerService
{
    /**
     * AnswerService constructor.
     *
     * @param QuestionService $questionService
     */
    public function __construct(private QuestionService $questionService)
    {
    }

    /**
     * @param array $payload
     * @param int   $userId
     *
     * @return array
     */
    public function bulkCreate(array $payload, int $userId): array
    {
        ['result' => $payload, 'questions' => $questions] = $this->prepareData($payload, $userId);

        $answers = [];

        foreach ($payload as $key => $newData) {
            $answers[$key] = Answer::query()->create($newData);
            $answers[$key]['question'] = $questions[$key];
        }

        return $answers;
    }

    /**
     * @param array $payload
     * @param int   $userId
     *
     * @return array
     */
    public function prepareData(array $payload, int $userId): array
    {
        $result = [];

        $questions = $this->questionService->getCorrectAnswers(array_keys($payload['answers']));

        foreach ($payload['answers'] as $key => $value) {
            $result[$key] = [
                'user_id' => $userId,
                'question_id' => $key,
                'value' => $value,
                'is_correct' => $questions[$key]->correct_answer == $value
            ];
        }

        return ['result' => $result, 'questions' => $questions];
    }

    /**
     * @param User  $user
     * @param array $payload
     */
    public function attemptingAddAnswers(User $user, array $payload): void
    {
        $data = $this->bulkCreate($payload, $user->id);

        Mail::to($user->email)->send(new StudentAnswers($user, $data));
    }
}
