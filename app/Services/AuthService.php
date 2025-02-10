<?php

namespace App\Services;

use App\Exceptions\ResourceCanNotCreateException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{
    protected $userRepository;

    /**
     * @param $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser($credentials)
    {
        try {
            DB::beginTransaction();

            $user = $this->userRepository->create($credentials);
            if (!$user) {
                throw new ResourceCanNotCreateException('Failed to register user');
            }

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function login($credentials)
    {
        $token = auth()->attempt($credentials);
        if (!$token) {
            throw new UnauthorizedException();
        }

        return $token;
    }

    public function me()
    {
        $user = auth()->user();
        return $user;
    }

    public function refresh()
    {
        $token = auth()->refresh();
        return $token;
    }

    public function logout()
    {
        auth()->logout();
    }
}
