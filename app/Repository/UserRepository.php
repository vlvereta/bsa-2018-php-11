<?php

namespace App\Repository;

use App\User;
use App\Repository\Contracts\UserRepository as IUserRepository;

class UserRepository implements IUserRepository
{
    public function getById(int $id): ?User
    {
        return User::find($id);
    }
}