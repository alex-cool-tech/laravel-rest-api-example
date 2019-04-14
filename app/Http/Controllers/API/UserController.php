<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Services\API\AuthService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @package App\Http\Controllers\API
 */
class UserController extends Controller
{
    /**
     * @var AuthService
     */
    private $userService;

    /**
     * @param AuthService $userService
     */
    public function __construct(AuthService $userService)
    {
        $this->userService = $userService;
        $this->middleware('isAdmin', ['only' => ['update', 'destroy']]);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return $this->userService->getAll();
    }

    /**
     * @param int $id
     *
     * @return UserResource
     */
    public function show(int $id): UserResource
    {
        return $this->userService->get($id);
    }

    /**
     * @param UpdateRequest $request
     * @param int $id
     *
     * @return UserResource
     */
    public function update(UpdateRequest $request, int $id): UserResource
    {
        return $this->userService->update($request, $id);
    }

    /**
     * @param int $id
     *
     * @return UserResource
     */
    public function destroy(int $id)
    {
        return $this->userService->remove($id);
    }
}
