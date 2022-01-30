<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class UserService
{
    public function all(): Collection
    {
        return User::all();
    }

    public function create(string $name, string $surname, string $email): User
    {
        $user = (new User())->setName($name)
            ->setSurname($surname)
            ->setEmail($email);

        $user->save();

        return $user;
    }
}
