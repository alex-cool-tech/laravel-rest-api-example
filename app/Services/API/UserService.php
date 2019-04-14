<?php

namespace App\Services\API;

use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * @package App\Services\API
 */
class UserService
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * @param User $user
     * @param AuthManager $authManager
     */
    public function __construct(User $user, AuthManager $authManager)
    {
        UserResource::withoutWrapping();

        $this->user = $user;
        $this->authManager = $authManager;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getAll(): AnonymousResourceCollection
    {
        return UserResource::collection($this->user->paginate());
    }

    /**
     * @param int $id
     *
     * @return UserResource
     */
    public function get(int $id): UserResource
    {
        UserResource::withoutWrapping();
        return new UserResource($this->user->findOrFail($id));
    }

    /**
     * @param UpdateRequest $request
     * @param int $id
     *
     * @return UserResource
     */
    public function update(UpdateRequest $request, int $id): UserResource
    {
        $user = $this->user->findOrFail($id);
        $user->update($request->only(['name', 'email']));

        return new UserResource($user);
    }

    /**
     * @param int $id
     *
     * @return UserResource
     */
    public function remove(int $id): UserResource
    {
        $user = $this
            ->user
            ->findOrFail($id);

        if ($user && $user->isSelf($id)) {
            throw new UnprocessableEntityHttpException();
        }

        $result = new UserResource($user);
        $user->delete();

        return $result;
    }
}
