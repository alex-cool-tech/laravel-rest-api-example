<?php

namespace Tests\Unit;

use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\API\UserService;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\Paginator;
use Mockery as m;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var AuthManager|m\MockInterface
     */
    private $authManager;

    /**
     * @var User|m\MockInterface
     */
    private $user;

    /**
     * @var UpdateRequest|m\MockInterface
     */
    private $updateRequest;

    protected function setUp(): void
    {
        $this->authManager = m::mock(AuthManager::class);
        $this->user = m::mock(User::class);
        $this->updateRequest = m::mock(UpdateRequest::class);

        $this->userService = new UserService($this->user, $this->authManager);
    }

    /**
     * @test
     *
     * @return void
     */
    public function getUser()
    {
        $data = [
            'id' => 1,
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'created_at' => '22.11.1994',
            'updated_at' => '22.11.1994',
        ];

        $this->user
            ->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($data);

        $result = $this->userService->get(1);

        $this->assertEquals($data, $result->resource);
        $this->assertInstanceOf(UserResource::class, $result);
    }

    /**
     * @test
     *
     * @return void
     */
    public function getUsers()
    {
        $data = new Paginator([
            [
                'id' => 1,
                'name' => 'Test1',
                'email' => 'test1@gmail.com',
                'created_at' => '22.11.1994',
                'updated_at' => '22.11.1994',
            ],
            [
                'id' => 2,
                'name' => 'Test2',
                'email' => 'test2@gmail.com',
                'created_at' => '22.11.1994',
                'updated_at' => '22.11.1994',
            ]
        ], 5, 1);

        $this->user
            ->shouldReceive('paginate')
            ->once()
            ->andReturn($data);

        $result = $this->userService->getAll();

        $this->assertEquals($data, $result->resource);
        $this->assertInstanceOf(AnonymousResourceCollection::class, $result);
    }

    /**
     * @test
     *
     * @return void
     */
    public function updateUser()
    {
        $changedData = ['name' => 'Test2', 'email' => 'test2@gmail.com'];
        $this->updateRequest
            ->shouldReceive('only')
            ->once()
            ->with(['name', 'email'])
            ->andReturn($changedData);
        $this->user
            ->shouldReceive('findOrFail')
            ->once()
            ->andReturnSelf();
        $this->user
            ->shouldReceive('update')
            ->with($changedData);

        $result = $this->userService->update($this->updateRequest, 1);

        $this->assertEquals($this->user, $result->resource);
        $this->assertInstanceOf(UserResource::class, $result);
    }

    /**
     * @test
     *
     * @return void
     */
    public function removeUser()
    {
        $this->user
            ->shouldReceive('findOrFail')
            ->once()
            ->andReturnSelf()
            ->getMock()
            ->shouldReceive('isSelf')
            ->with(1)
            ->once()
            ->andReturn(false)
            ->getMock()
            ->shouldReceive('delete');

        $result = $this->userService->remove(1);

        $this->assertEquals($this->user, $result->resource);
        $this->assertInstanceOf(UserResource::class, $result);
    }
}
