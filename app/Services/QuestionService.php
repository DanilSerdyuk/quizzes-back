<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\QuestionDTO;
use App\Models\Question;

class QuestionService
{
    /**
     * @param array $payload
     * @param int   $quizId
     *
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @return array
     */
    public function storeMany(array $payload, int $quizId): array
    {
        $result = [];

        foreach ($payload as $question) {
            $questionData = new QuestionDTO(array_merge($question, ['quiz_id' => $quizId]));
            $result[] = Question::query()->create($questionData->all());
        }

        return $result;
    }
}
