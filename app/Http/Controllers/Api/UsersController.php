<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    /**
     * QuizController constructor.
     *
     * @param UserService $service
     */
    public function __construct(private UserService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return ResponseJson::responseJson(
            UserResource::collection($this->service->getStudents())
        );
    }
}
