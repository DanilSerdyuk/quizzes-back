<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\QuizDTO;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class QuizService
{
    public function __construct(private QuestionService $questionService)
    {
    }

    /**
     * @param array $payload
     *
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties|\Exception
     *
     * @return Quiz|Model
     */
    public function store(array $payload): Quiz
    {
        $quizData = new QuizDTO($payload);

        DB::beginTransaction();

        try {
            $quiz = Quiz::query()->create($quizData->all());

            $this->questionService->storeMany($payload['questions'], $quiz->id);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return $quiz;
    }

    /**
     * @param string $slug
     *
     * @return Quiz
     */
    public function show(string $slug): Quiz
    {
        if (!$quiz = Quiz::query()->whereSlug($slug)->first()) {
            throw (new ModelNotFoundException())->setModel('Quiz', $slug);
        }

        return $quiz->load(['questions:id,quiz_id,type,title,correct_answer', 'user:id,name,email']);
    }

    /**
     * @param array $payload
     * @param int   $id
     *
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @return bool
     */
    public function update(array $payload, int $id): bool
    {
        if (!$quiz = Quiz::query()->whereKey($id)->first()) {
            throw (new ModelNotFoundException())->setModel('Quiz', $id);
        }

        $quizData = new QuizDTO($payload);

        return $quiz->update($quizData->notNull());
    }
}
