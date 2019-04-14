<?php

namespace Tests\Unit;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Models\User;
use App\Services\API\AuthService;
use Illuminate\Auth\AuthManager;
use Illuminate\Routing\ResponseFactory;
use Mockery as m;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @var AuthManager|m\MockInterface
     */
    private $authManager;

    /**
     * @var User|m\MockInterface
     */
    private $user;

    /**
     * @var RegisterRequest|m\MockInterface
     */
    private $registerRequest;

    /**
     * @var ResponseFactory|m\MockInterface
     */
    private $responseFactory;

    /**
     * @var LoginRequest|m\MockInterface
     */
    private $loginRequest;

    protected function setUp(): void
    {
        $this->authManager = m::mock(AuthManager::class);
        $this->user = m::mock(User::class);
        $this->registerRequest = m::mock(RegisterRequest::class);
        $this->loginRequest = m::mock(LoginRequest::class);
        $this->responseFactory = m::mock(ResponseFactory::class);

        $this->authService = new AuthService($this->user, $this->authManager, $this->responseFactory);
    }

    /**
     * @test
     *
     * @return void
     */
    public function login()
    {
        $expiresIn = 60;
        $jsonResult = [
            'access_token' => 'token',
            'token_type' => 'bearer',
            'expires_in' => 60 * $expiresIn
        ];

        $this->loginRequest
            ->shouldReceive('only')
            ->with(['email', 'password'])
            ->andReturn(['email' => 'test', 'password' => 'test']);

        $this->authManager
            ->shouldReceive('attempt')
            ->with(['email' => 'test', 'password' => 'test'])
            ->andReturn('token')
            ->getMock()
            ->shouldReceive('factory->getTTL')
            ->andReturn($expiresIn);

        $this->responseFactory
            ->shouldReceive('json')
            ->with($jsonResult)
            ->andReturn(json_encode($jsonResult));

        $result = $this->authService->login($this->loginRequest);
        $this->assertEquals(json_encode($jsonResult), $result);
    }
}
