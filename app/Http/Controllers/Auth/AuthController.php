<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(private AuthService $service)
    {
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     *
     * @throws AuthorizationException
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        /** @var string $token */
        if (! $token = auth()->attempt($request->validated())) {
            throw new AuthorizationException('Wrong credentials', 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * User registration
     */
    public function registration(RegistrationRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->service->registration($validated);

        return ResponseJson::responseJson(['message' => 'Successfully registration!'], 201);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return ResponseJson::responseJson(
            new UserResource(auth()->user())
        );
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return ResponseJson::responseJson(code:204);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        /** @var string $token */
        $token = JWTAuth::parseToken()->refresh();

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return ResponseJson::responseJson([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
