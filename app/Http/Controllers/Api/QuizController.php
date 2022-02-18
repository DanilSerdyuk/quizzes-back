<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssigneeQuizRequest;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Http\Resources\QuizResource;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * QuizController constructor.
     *
     * @param QuizService $service
     */
    public function __construct(private QuizService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return ResponseJson::responseJson(
            $this->service->get($request->get('perPage', 10), $request->get('page', 1))
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreQuizRequest $request
     *
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     *
     * @return JsonResponse
     */
    public function store(StoreQuizRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $payload['user_id'] = auth()->id();

        return ResponseJson::responseJson($this->service->store($payload));
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     *
     * @return JsonResponse
     */
    public function show(string $slug): JsonResponse
    {
        return ResponseJson::responseJson(new QuizResource($this->service->show($slug)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateQuizRequest $request
     * @param int                                  $id
     *
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     *
     * @return JsonResponse
     */
    public function update(UpdateQuizRequest $request, int $id): JsonResponse
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

    /**
     * Assignee user on test
     *
     * @param  AssigneeQuizRequest $request
     *
     * @return JsonResponse
     */
    public function assignee(AssigneeQuizRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->service->assigned($validated);

        return ResponseJson::responseJson(code: 202);
    }
}
