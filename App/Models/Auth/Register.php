<?php

namespace SyntoraPHP\App\Models\Auth;

use SyntoraPHP\App\Models\Db\User;
use PDOException;

class Register
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function register($name, $email, $password, $confirmPassword)
    {
        // Validate input
        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            return ['success' => false, 'message' => 'All fields are required.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format.'];
        }

        if ($password !== $confirmPassword) {
            return ['success' => false, 'message' => 'Passwords do not match.'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters long.'];
        }

        // Check if email already exists
        if ($this->userModel->getByEmail($email)) {
            return ['success' => false, 'message' => 'Email already in use.'];
        }

        try {
            // Create new user
            $userId = $this->userModel->create($name, $email, $password);

            if ($userId) {
                $token = $this->userModel->authenticate($email, $password);
                return [
                    'success' => true,
                    'message' => 'Registration successful.',
                    'user_id' => $userId,
                    'token' => $token
                ];
            } else {
                return ['success' => false, 'message' => 'Registration failed. Please try again.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database error: Please try again later.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'An unexpected error occurred. Please try again later.'];
        }
    }
}
