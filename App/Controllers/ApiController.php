<?php

namespace SyntoraPHP\App\Controllers;

use SyntoraPHP\App\Models\Auth\Auth;
use SyntoraPHP\App\Models\Auth\Login;
use SyntoraPHP\App\Models\Json;
use SyntoraPHP\App\Models\Auth\Register;
use SyntoraPHP\App\Models\Auth\Update;
use SyntoraPHP\App\Models\CORS;

class ApiController
{
    private CORS $cors;

    public function __construct()
    {
        $this->cors = new CORS();
        $this->cors->origin(['*'])
            ->methods(['GET', 'POST'])
            ->headers(['Content-Type', 'Authorization'])
            ->expose(['Content-Length'])
            ->maxAge(0)
            ->credentials(false)
            ->setHeaders();
    }

    public function register()
    {
        $json = new Json();
        $registerModel = new Register();

        $inputData = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $json->show([
                "status" => false,
                "message" => "Invalid JSON format."
            ], 400);
            return;
        }

        if (
            empty($inputData['name']) ||
            empty($inputData['email']) ||
            empty($inputData['password']) ||
            empty($inputData['confirmPassword'])
        ) {
            $json->show([
                "status" => false,
                "message" => "Missing required fields."
            ], 400);
            return;
        }

        try {
            $response = $registerModel->register(
                $inputData['name'],
                $inputData['email'],
                $inputData['password'],
                $inputData['confirmPassword']
            );

            if ($response['success']) {
                // Set the token and email in cookies for 24 hours
                setcookie('auth_token', $response['token'], time() + 86400, '/', '', false, true);
                setcookie('auth_email', $inputData['email'], time() + 86400, '/', '', false, true);

                $json->show([
                    "status" => true,
                    "message" => $response['message'],
                    "user_id" => $response['user_id']
                ], 200);
            } else {
                $json->show([
                    "status" => false,
                    "message" => $response['message']
                ], 400);
            }
        } catch (\PDOException $e) {
            $json->show([
                "status" => false,
                "message" => "Database error: Please try again later."
            ], 500);
        } catch (\Exception $e) {
            $json->show([
                "status" => false,
                "message" => "An unexpected error occurred. Please try again later."
            ], 500);
        }
    }

    public function login()
    {
        $json = new Json();
        $loginModel = new Login();

        $inputData = json_decode(file_get_contents('php://input'), true);

        if (!isset($inputData['email']) || !isset($inputData['password'])) {
            $json->show([
                "status" => false,
                "message" => "Missing required fields."
            ], 400);
            return;
        }

        $response = $loginModel->login($inputData['email'], $inputData['password']);

        if ($response['success']) {
            // Set the token and email in cookies for 24 hours
            setcookie('auth_token', $response['token'], time() + 86400, '/', '', false, true);
            setcookie('auth_email', $inputData['email'], time() + 86400, '/', '', false, true);

            $json->show([
                "status" => true,
                "message" => $response['message'],
                "token" => $response['token']
            ], 200);
        } else {
            $json->show([
                "status" => false,
                "message" => $response['message']
            ], 401);
        }
    }

    public function getUserData()
    {
        $json = new Json();
        $authModel = new Auth();

        $email = isset($_GET['email']) ? $_GET['email'] : null;
        $token = isset($_GET['token']) ? $_GET['token'] : null;

        if (empty($email) || empty($token)) {
            $json->show([
                "status" => false,
                "message" => "Missing required fields."
            ], 400);
            return;
        }

        $user = $authModel->getUserByEmail($email, $token);

        if ($user) {
            $json->show([
                "status" => true,
                "user" => $user
            ], 200);
        } else {
            $json->show([
                "status" => false,
                "message" => "Invalid or expired token, or user not found."
            ], 401);
        }
    }

    public function updatePassword()
    {
        $json = new Json();
        $updateModel = new Update();

        $inputData = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $json->show([
                "status" => false,
                "message" => "Invalid JSON format."
            ], 400);
            return;
        }

        if (
            empty($inputData['id']) ||
            empty($inputData['password']) ||
            empty($inputData['token'])
        ) {
            $json->show([
                "status" => false,
                "message" => "Missing required fields."
            ], 400);
            return;
        }

        $updateData = [
            'password' => $inputData['password']
        ];

        try {
            $response = $updateModel->update(
                $inputData['id'],
                $updateData,
                $inputData['token']
            );

            if ($response['success']) {
                $json->show([
                    "status" => true,
                    "message" => $response['message']
                ], 200);
            } else {
                $json->show([
                    "status" => false,
                    "message" => $response['message']
                ], 400);
            }
        } catch (\PDOException $e) {
            $json->show([
                "status" => false,
                "message" => "Database error: Please try again later."
            ], 500);
        } catch (\Exception $e) {
            $json->show([
                "status" => false,
                "message" => "An unexpected error occurred. Please try again later."
            ], 500);
        }
    }

    public function updateName()
    {
        $json = new Json();
        $updateModel = new Update();

        $inputData = json_decode(file_get_contents('php://input'), true);

        if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['token'])) {
            $json->show([
                "status" => false,
                "message" => "Missing required fields."
            ], 400);
            return;
        }

        $data = ['name' => $inputData['name']];

        $response = $updateModel->update($inputData['id'], $data, $inputData['token']);

        if ($response['success']) {
            $json->show([
                "status" => true,
                "message" => $response['message']
            ], 200);
        } else {
            $json->show([
                "status" => false,
                "message" => $response['message']
            ], 400);
        }
    }

    public function updateEmail()
    {
        $json = new Json();
        $updateModel = new Update();

        $inputData = json_decode(file_get_contents('php://input'), true);

        if (!isset($inputData['id']) || !isset($inputData['email']) || !isset($inputData['token'])) {
            $json->show([
                "status" => false,
                "message" => "Missing required fields."
            ], 400);
            return;
        }

        $data = ['email' => $inputData['email']];

        $response = $updateModel->update($inputData['id'], $data, $inputData['token']);

        if ($response['success']) {
            $json->show([
                "status" => true,
                "message" => $response['message']
            ], 200);
        } else {
            $json->show([
                "status" => false,
                "message" => $response['message']
            ], 400);
        }
    }

    public function deleteUser()
    {
        $json = new Json();
        $authModel = new Auth();

        $inputData = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $json->show([
                "status" => false,
                "message" => "Invalid JSON format."
            ], 400);
            return;
        }

        if (
            empty($inputData['id']) ||
            empty($inputData['token'])
        ) {
            $json->show([
                "status" => false,
                "message" => "Missing required fields."
            ], 400);
            return;
        }

        try {
            $userModel = new \SyntoraPHP\App\Models\Db\User();
            $response = $userModel->delete($inputData['id'], $inputData['token']);

            if ($response) {
                $json->show([
                    "status" => true,
                    "message" => "User successfully deleted."
                ], 200);
            } else {
                $json->show([
                    "status" => false,
                    "message" => "Invalid token or user could not be deleted."
                ], 400);
            }
        } catch (\PDOException $e) {
            $json->show([
                "status" => false,
                "message" => "Database error: Please try again later."
            ], 500);
        } catch (\Exception $e) {
            $json->show([
                "status" => false,
                "message" => "An unexpected error occurred. Please try again later."
            ], 500);
        }
    }

    public function logout()
    {
        setcookie('auth_token', '', time() - 3600, '/', '', false, true);
        setcookie('auth_email', '', time() - 3600, '/', '', false, true);

        header('Location: /auth/login');
        exit();
    }
}
