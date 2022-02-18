<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Services\QuestionService;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    /**
     * QuizController constructor.
     *
     * @param QuestionService $service
     */
    public function __construct(private QuestionService $service)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreQuestionRequest $request
     *
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     *
     * @return JsonResponse
     */
    public function store(StoreQuestionRequest $request): JsonResponse
    {
        return ResponseJson::responseJson(
            new QuestionResource($this->service->store($request->validated()))
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateQuestionRequest $request
     * @param int                                      $id
     *
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     *
     * @return JsonResponse
     */
    public function update(UpdateQuestionRequest $request, int $id): JsonResponse
    {
        return ResponseJson::responseJson($this->service->update($request->validated(), $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return ResponseJson::responseJson(code: 204);
    }
}
