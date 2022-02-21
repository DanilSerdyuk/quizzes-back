<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnswerQuizRequest;
use App\Models\User;
use App\Services\AnswerService;
use Illuminate\Http\JsonResponse;

class AnswerController extends Controller
{
    /**
     * QuizController constructor.
     *
     * @param AnswerService $service
     */
    public function __construct(private AnswerService $service)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\AnswerQuizRequest $request
     *
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     *
     * @return JsonResponse
     */
    public function addAnswers(AnswerQuizRequest $request): JsonResponse
    {
        $payload = $request->validated();
        /** @var User $user */
        $user = auth()->user();

        $this->service->attemptingAddAnswers($user, $payload);

        return ResponseJson::responseJson(code:201);
    }
}
