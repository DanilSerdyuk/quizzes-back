<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\QuizDTO;
use App\Enums\RoleEnum;
use App\Events\QuizWasCreated;
use App\Mail\AssignedTestsMail;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class QuizService extends BaseService
{
    /**
     * QuizService constructor.
     *
     * @param QuestionService $questionService
     */
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

            $questions = $this->questionService->storeMany($payload['questions'], $quiz->id);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        event(new QuizWasCreated($quiz->user_id, $questions));

        return $quiz;
    }

    /**
     * @param string $slug
     *
     * @return Quiz
     */
    public function show(string $slug): Quiz
    {
        /** @var Quiz $quiz */
        $quiz = $this->checkOnExist(Quiz::class, $slug, 'slug');

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
        $quiz = $this->checkOnExist(Quiz::class, $id);

        $quizData = new QuizDTO($payload);

        return $quiz->update($quizData->notNull());
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        return (bool)Quiz::query()->whereKey($id)->delete();
    }

    /**
     * @param array $payload
     *
     * @return void
     */
    public function assigned(array $payload): void
    {
        /** @var User $admin */
        $admin = User::query()
            ->whereHas('roles', fn($q) => $q->where('name', RoleEnum::ADMIN))
            ->first();

        ['users' => $usersIds, 'url' => $url] = $payload;

        User::query()
            ->whereIn('id', $usersIds)
            ->select(['id', 'email', 'name'])
            ->get()
            ->each(function (User $user) use ($admin, $url){
                Mail::to($user->email)->send(new AssignedTestsMail($admin, $user, $url));
            });
    }

    /**
     * @param int $perPage
     * @param int $page
     *
     * @return array
     */
    public function get(int $perPage = 10, int $page = 1): array
    {
        $quizzes = Quiz::with(['user' => fn($q) => $q->select(['id', 'name'])])
            ->withCount('questions')
            ->select(['id', 'title', 'user_id', 'slug', 'created_at'])
            ->paginate(perPage: $perPage, page: $page);

        return [
            'current_page' => $quizzes->currentPage(),
            'last_page' => $quizzes->lastPage(),
            'total' => $quizzes->total(),
            'data' => $quizzes->items()
        ];
    }
}
