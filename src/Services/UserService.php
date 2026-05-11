<?php

namespace App\Services;

use App\Helpers\Crypt;
use App\Models\UserModel;
use Exception;

class UserService
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function getTokenByUserId(int $user_id): ?string
    {
        $user = $this->userModel->findUserById($user_id);

        if (empty($user['token'])) {
            throw new Exception('Token de autenticação não encontrado para o usuário');
        }

        $token = Crypt::decrypt($user['token']);

        if (!$token) {
            throw new Exception('Token de autenticação inválido.');
        }

        return $token;
    }
}
