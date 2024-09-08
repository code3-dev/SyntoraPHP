<?php

namespace SyntoraPHP\App\Models\Auth;

use SyntoraPHP\App\Models\Db\User;
use Exception;

class Auth
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function validateToken(string $token): bool
    {
        try {
            $user = $this->userModel->validateToken($token);
            return $user !== false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getUserByEmail(string $email, string $token): ?array
    {
        try {
            if (!$this->validateToken($token)) {
                return null;
            }

            $user = $this->userModel->getByEmail($email);

            if ($user) {
                return $this->filterUserData($user);
            }

            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function getUserById(int $id, string $token): ?array
    {
        try {
            if (!$this->validateToken($token)) {
                return null;
            }

            $user = $this->userModel->getById($id);

            if ($user) {
                return $this->filterUserData($user);
            }

            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    private function filterUserData(array $user): array
    {
        unset($user['password']);
        unset($user['token']);
        return $user;
    }
}
