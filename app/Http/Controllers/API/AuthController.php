<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Services\API\AuthService;
use Illuminate\Http\JsonResponse;

/**
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        return $this->authService->login($request);
    }

    /**
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        return $this->authService->register($request);
    }
}
