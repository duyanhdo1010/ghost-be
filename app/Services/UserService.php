<?php

namespace App\Services;

use App\Exceptions\ResourceCanNotCreateException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected $userRepository;

    /**
     * @param $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

}
