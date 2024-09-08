<?php

namespace SyntoraPHP\App\Models\Auth;

use SyntoraPHP\App\Models\Db\User;
use Exception;

class Update
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Update user information
     *
     * @param int $id User ID to be updated
     * @param array $data Data to be updated
     * @param string $token Authentication token
     * @return array Response array indicating success or failure
     */
    public function update(int $id, array $data, string $token): array
    {
        try {
            // Validate token
            $currentUser = $this->userModel->validateToken($token);
            if (!$currentUser) {
                return ['success' => false, 'message' => 'Invalid token.'];
            }

            // Check if updating own account or if admin
            if ($currentUser['id'] !== $id && $currentUser['role'] != 1) {
                return ['success' => false, 'message' => 'Unauthorized action.'];
            }

            // If attempting to update role, ensure the current user is an admin
            if (isset($data['role']) && $currentUser['role'] != 1) {
                return ['success' => false, 'message' => 'Only admins can change roles.'];
            }

            // Check if email already exists
            if (isset($data['email'])) {
                $existingUser = $this->userModel->getByEmail($data['email']);
                if ($existingUser && $existingUser['id'] !== $id) {
                    return ['success' => false, 'message' => 'Email already in use.'];
                }
            }

            // Validate input data
            $validationErrors = $this->validateData($data);
            if ($validationErrors) {
                return ['success' => false, 'message' => $validationErrors];
            }

            // Update user information
            $updateResult = $this->userModel->update($id, $data, $token);

            if ($updateResult) {
                return ['success' => true, 'message' => 'Update successful.'];
            } else {
                return ['success' => false, 'message' => 'No changes detected or update failed.'];
            }
        } catch (Exception $e) {
            // Handle exceptions (log them, etc.)
            return ['success' => false, 'message' => 'An unexpected error occurred. Please try again later.'];
        }
    }

    /**
     * Validate user input data
     *
     * @param array $data Data to be validated
     * @return string|null Validation error message or null if no errors
     */
    private function validateData(array $data): ?string
    {
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email format.';
        }

        if (isset($data['password']) && strlen($data['password']) < 6) {
            return 'Password must be at least 6 characters long.';
        }

        return null;
    }
}
