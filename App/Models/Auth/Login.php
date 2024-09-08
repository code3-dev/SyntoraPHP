<?php

namespace SyntoraPHP\App\Models\Auth;

use SyntoraPHP\App\Models\Db\User;
use Exception;

class Login
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login($email, $password)
    {
        // Validate input
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email and password are required.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format.'];
        }

        try {
            // Authenticate user
            $token = $this->userModel->authenticate($email, $password);

            if ($token) {
                return ['success' => true, 'message' => 'Login successful.', 'token' => $token];
            } else {
                return ['success' => false, 'message' => 'Invalid email or password.'];
            }
        } catch (Exception $e) {
            // Catch and handle exceptions
            return ['success' => false, 'message' => 'An unexpected error occurred. Please try again later.'];
        }
    }
}
