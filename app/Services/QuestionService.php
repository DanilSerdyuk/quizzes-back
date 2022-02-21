<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\QuestionDTO;
use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class QuestionService extends BaseService
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

    /**
     * @param array $payload
     *
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     *
     * @return Question|Model
     */
    public function store(array $payload): Question
    {
        $questionData = new QuestionDTO($payload);

        return Question::query()->create($questionData->all());
    }

    /**
     * @param array $payload
     * @param int   $id
     *
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     *
     * @return bool
     */
    public function update(array $payload, int $id): bool
    {
        $question = $this->checkOnExist(Question::class, $id);

        $questionData = new QuestionDTO($payload);

        return $question->update($questionData->notNull());
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        return (bool)Question::query()->whereKey($id)->delete();
    }

    /**
     * @param array $ids
     *
     * @return Collection
     */
    public function getCorrectAnswers(array $ids): Collection
    {
        return Question::query()
            ->whereIn('id', $ids)
            ->select(['id', 'title', 'correct_answer'])
            ->get()
            ->keyBy('id');
    }
}
