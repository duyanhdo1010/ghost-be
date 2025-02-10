<?php

namespace App\Http\Controllers;

use App\Exceptions\ResourceCanNotCreateException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Services\UserService;

class AuthController extends Controller
{
    protected $authService;

    /**
     * @param $authService
     */
    public function __construct(UserService $userService, AuthService $authService)
    {
        $this->authService = $authService;
//        middleware nay duoc su dung boi vi cac hanh dong nhu logout va refresh can token
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * @throws ResourceCanNotCreateException
     */
    public function register(RegisterRequest $request)
    {
        $credentials = $request->validated();
        $user = $this->authService->createUser($credentials);
        return $this->success('User created!', $user);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = $this->authService->login($credentials);
        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = $this->authService->me();
        return $this->success('User data retrieved', $user);
    }

    public function logout()
    {
        $this->authService->logout();
        return $this->success('Successfully logged out');
    }

    public function refresh()
    {
        $token = $this->authService->refresh();
        return $this->respondWithToken($token);
    }
}
