<?php

namespace App\Models;

use App\Entities\User;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $returnType       = User::class;
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'name',
        'email',
        'password',
        'password_confirm',
        'reset_hash',
        'reset_expires_in',
        'image',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;

    // Validation
    protected $validationRules = [
        'name'              => 'required|min_length[3]|max_length[128]',
        'email'             => 'required|valid_email|is_unique[users.email]',
        'password'          => 'required|alpha_numeric_punct|min_length[3]|max_length[128]',
        'password_confirm'  => 'matches[password]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'    => 'O nome é obrigatório.',
            'min_length'  => 'O nome deve ter pelo menos 3 caracteres.',
            'max_length'  => 'O nome não pode ter mais de 128 caracteres.',
        ],
        'email' => [
            'required'    => 'O e-mail é obrigatório.',
            'valid_email' => 'Insira um endereço de e-mail válido.',
            'is_unique'   => 'Este e-mail já está em uso.',
        ],
        'password' => [
            'required'    => 'A senha é obrigatória.',
            'min_length'      => 'A senha deve ter no mínimo 8 caracteres.',
            'max_length'  => 'A senha não pode ter mais de 128 caracteres.',
            'alpha_numeric_punct'   => 'A senha deve conter apenas letras, símbolos e números.',
        ],
        'password_confirm' => [
            'matches'     => 'A confirmação da senha não coincide com a senha.',
        ],
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    // Utils functions

    /**
     * Hashes the password in the provided data array.
     *
     * This method checks for a 'password' key in the 'data' sub-array. 
     * If found, it hashes the password using `password_hash()` with the default algorithm (PASSWORD_DEFAULT) 
     * and stores it in the 'password_hash' key. The original 'password' and 'password_confirm' keys are then removed.
     *
     * @param array $data The data array containing the password to be hashed.
     *
     * @return array The updated data array with the hashed password and removed original password fields.
     */
    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            unset($data['data']['password']);
            unset($data['data']['password_confirm']);
        }
        return $data;
    }

    /**
     * Searches for a user by email address.
     *
     * @param string $email The email address of the user to search for.
     * @param bool $withDeleted Specifies whether soft deleted users should also be included in the search.
     * Defaults to `false`, excluding deleted users.
     *
     * @return mixed Returns the user entity record corresponding to the given email address, or `null` if not found.
     */
    public function getUserByEmail(string $email, bool $withDeleted = false): User|null
    {
        return $this
            ->where('email', $email)
            ->withDeleted($withDeleted)
            ->first();
    }

    /**
     * Retrieves a user by their ID.
     *
     * This method fetches a user record from the database based on their ID.
     * It allows for optionally including deleted users by setting `$withDeleted` to true.
     *
     * @param string $id The user's ID.
     * @param bool $withDeleted Whether to include deleted users (default: false).
     *
     * @return User|null The User object if found, null otherwise.
     */
    public function getUserById(string $id, bool $withDeleted = false): User|null
    {
        return $this
            ->where('id', $id)
            ->withDeleted($withDeleted)
            ->first();
    }

    /**
     * Retrieves a user by their password reset hash.
     *
     * @param string $reset_hash The password reset hash.
     * @param bool $withDeleted Whether to include deleted users. Defaults to false.
     *
     * @return User|null The user with the given reset hash, or null if no such user exists.
     */
    public function getUserByResetHash(string $reset_hash, bool $withDeleted = false): User|null
    {
        return $this
            ->where('reset_hash', $reset_hash)
            ->withDeleted($withDeleted)
            ->first();
    }

    /**
     * Checks if a user's password matches the stored hash.
     *
     * @param int $userId The ID of the user to check.
     * @param string $password The password to compare against the stored hash.
     *
     * @return bool True if the password matches, false otherwise.
     */
    public function checkUserPassword(int $userId, string $password): bool
    {
        // Retrieve the user by ID.
        $user = $this->find($userId);

        // Ensure user exists and password matches the current password.
        return $user && password_verify($password, $user->password_hash);
    }
}
