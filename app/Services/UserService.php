<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

/**
 * UserService
 */
class UserService
{
    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return User::all();
    }

    /**
     * @param string $name
     * @param string $surname
     * @param string $email
     * @return User
     */
    public function create(string $name, string $surname, string $email): User
    {
        $user = (new User())->setName($name)
            ->setSurname($surname)
            ->setEmail($email);

        $user->save();

        return $user;
    }
}
