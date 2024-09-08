<?php

namespace SyntoraPHP\App\Controllers;

use SyntoraPHP\App\Models\Auth\Auth;

class PanelController
{
    public function index()
    {
        $authModel = new Auth();
 
        if (isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_email'])) {
            $token = $_COOKIE['auth_token'];
            $email = $_COOKIE['auth_email'];

            // Get user by email and token
            $user = $authModel->getUserByEmail($email, $token);

            if (!$user) {
                header('Location: /auth/login');
                exit();
            }

            $data = [
                "user" => $user,
                "token" => $token
            ];

            view("panel", $data);
        }else {
            header('Location: /auth/login');
            exit();
        }
    }
}