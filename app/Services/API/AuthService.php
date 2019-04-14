<?php

namespace App\Services\API;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * @package App\Services\API
 */
class AuthService
{
    const EXPIRES_IN_MIXIN = 60;

    /**
     * @var User
     */
    private $user;

    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @param User $user
     * @param AuthManager $auth
     * @param ResponseFactory $responseFactory
     */
    public function __construct(User $user, AuthManager $auth, ResponseFactory $responseFactory)
    {
        $this->user = $user;
        $this->authManager = $auth;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = $this->authManager->attempt($credentials)) {
            throw new UnauthorizedHttpException("challenge");
        }

        return $this->respondWithToken($token);
    }

    /**
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->user->create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'name' => $request->name,
            'is_admin' => $request->is_admin
        ]);

        $token = $this->authManager->login($user);
        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    private function respondWithToken(string $token): JsonResponse
    {
        return $this->responseFactory->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->expiresIn()
        ]);
    }

    /**
     * Get token expiration time
     *
     * @return float|int
     */
    private function expiresIn()
    {
        return $this->authManager
                ->factory()
                ->getTTL() * self::EXPIRES_IN_MIXIN;
    }
}