<?php

namespace App\Entities;

use App\Libraries\Token;
use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Checks if the given password matches the stored password hash.
     *
     * This method uses `password_verify()` to compare the provided password with the stored password hash.
     *
     * @param string $password The password to be checked.
     *
     * @return bool True if the password matches the stored hash, false otherwise.
     */
    public function checkPassword(string $password): bool
    {
        return password_verify($password, $this->password_hash);
    }
}
