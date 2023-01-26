<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;

class UserRepository implements Interfaces\UserRepositoryInterface
{

    protected $userModel;
    protected $user;

    public function __construct(User $user)
    {
        $this->userModel = $user;
    }

    public function saveUser($data) {
        return User::create($data);
    }
}

?>