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
     * @param int $id
     *
     * @return Quiz
     */
    public function getById(int $id): Quiz
    {
        /** @var Quiz $quiz */
        $quiz = $this->checkOnExist(Quiz::class, $id);

        return $quiz->load(['questions:id,quiz_id,type,title,correct_answer', 'user:id,name,email']);
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

        ['users' => $usersIds, 'url' => $url, 'quiz_id' => $quizId] = $payload;

        $url = $this->swapIdToSlug($url, $quizId);

        $this->sendMailToUsers($admin, $usersIds, $url);
    }

    /**
     * @param string $url
     * @param int    $quizId
     *
     * @return string
     */
    private function swapIdToSlug(string $url, int $quizId): string
    {
        $quizSlug = $this->getById($quizId);

        return sprintf('%s/%s', $url, $quizSlug->slug);
    }

    /**
     * @param User   $admin
     * @param array  $usersIds
     * @param string $url
     */
    private function sendMailToUsers(User $admin, array $usersIds, string $url): void
    {
        User::query()
            ->whereIn('id', $usersIds)
            ->select(['id', 'email', 'name'])
            ->get()
            ->each(function (User $user) use ($admin, $url){
                Mail::to($user->email)->send(new AssignedTestsMail($admin, $user, $url));
            });
    }

    /**
     * @param int         $perPage
     * @param int         $page
     * @param string|null $q
     *
     * @return array
     */
    public function get(int $perPage = 10, int $page = 1, ?string $q = null): array
    {
        $quizzes = Quiz::query()
            ->when($q, function ($query) use ($q) {
                $like = sprintf('%%%s%%', $q);
                $query->where('title', 'like', $like);
            })
            ->with(['user' => fn($q) => $q->select(['id', 'name'])])
            ->select(['id', 'title', 'user_id', 'slug', 'created_at'])
            ->withCount('questions')
            ->orderByDesc('created_at')
            ->paginate(perPage: $perPage, page: $page);

        return [
            'current_page' => $quizzes->currentPage(),
            'last_page' => $quizzes->lastPage(),
            'total' => $quizzes->total(),
            'data' => $quizzes->items()
        ];
    }
}
