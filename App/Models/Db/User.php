<?php

namespace SyntoraPHP\App\Models\Db;

use Exception;
use PDOException;
use Medoo\Medoo;
use SyntoraPHP\App\Models\Database;

class User
{
    private Medoo $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Validate the provided token.
     *
     * @param string $token
     * @return array|false
     */
    public function validateToken(string $token)
    {
        // Fetch the user by token
        $user = $this->db->get('users', '*', ['token' => $token]);
        return $user ?: false;
    }

    /**
     * Create a new user.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param int $role
     * @return int
     */
    public function create($name, $email, $password, $role = 0): int
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $token = bin2hex(random_bytes(16));

            $this->db->insert('users', [
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => $role,
                'token' => $token
            ]);

            // Ensure the return type is an integer
            return (int)$this->db->id();
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            throw new Exception('Database error: Could not create user.');
        }
    }

    /**
     * Get user by email.
     *
     * @param string $email
     * @return array|false
     */
    public function getByEmail(string $email)
    {
        return $this->db->get('users', '*', ['email' => $email]);
    }

    /**
     * Get user by ID.
     *
     * @param int $id
     * @return array|false
     */
    public function getById(int $id)
    {
        return $this->db->get('users', '*', ['id' => $id]);
    }

    /**
     * Update user data.
     *
     * @param int $id
     * @param array $data
     * @param string $token
     * @return bool
     */
    public function update(int $id, array $data, string $token): bool
    {
        if ($this->validateToken($token)) {
            if (isset($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }

            return (bool)$this->db->update('users', $data, ['id' => $id]);
        }

        return false;
    }

    /**
     * Delete a user.
     *
     * @param int $id
     * @param string $token
     * @return bool
     */
    public function delete(int $id, string $token): bool
    {
        if ($this->validateToken($token)) {
            return (bool)$this->db->delete('users', ['id' => $id]);
        }

        return false;
    }

    /**
     * Authenticate a user and return a token.
     *
     * @param string $email
     * @param string $password
     * @return string|false
     */
    public function authenticate(string $email, string $password)
    {
        try {
            $user = $this->db->get('users', '*', ['email' => $email]);

            if ($user && password_verify($password, $user['password'])) {
                $token = bin2hex(random_bytes(64));
                $this->db->update('users', ['token' => $token], ['id' => $user['id']]);
                return $token;
            }

            return false;
        } catch (Exception $e) {
            throw new Exception('Unexpected error: ' . $e->getMessage());
        }
    }

    /**
     * Check if the user is an admin.
     *
     * @param int $id
     * @param string $token
     * @return bool
     */
    public function isAdmin(int $id, string $token): bool
    {
        $user = $this->validateToken($token);
        return $user && $user['id'] == $id && $user['role'] == 1;
    }
}
