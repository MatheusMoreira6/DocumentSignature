<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Helpers\Crypt;
use App\Helpers\Hash;
use App\Models\UserModel;

class ProfileController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $user = $this->userModel->findUserById(Session::userId());

        if (isset($user['cpf'])) {
            $user['cpf'] = preg_replace(
                '/(\d{3})(\d{3})(\d{3})(\d{2})/',
                '$1.$2.$3-$4',
                $user['cpf']
            );
        }

        if (isset($user['token'])) {
            $user['token'] = Crypt::decrypt($user['token']);
        }

        return $this->layout('profile/index', compact('user'), true);
    }

    public function update(Request $request)
    {
        $user_id = Session::userId();
        $current_password = $request->string("current_password");
        $new_password = $request->string("new_password");
        $token = $request->string("token");

        if (
            (!empty($current_password) && empty($new_password)) ||
            (empty($current_password) && !empty($new_password))
        ) {
            $this->json(['errors' => 'Senha atual e nova senha são obrigatórias.'], 400);
        }

        $user = $this->userModel->findUserById($user_id);

        if (!empty($current_password) && !Hash::check($current_password, $user['password'])) {
            $this->json(['errors' => 'Senha atual incorreta.'], 400);
        }

        $data = [
            'token' => empty($token) ? null : Crypt::encrypt($token),
        ];

        if (!empty($new_password)) {
            $data['password'] = Hash::make($new_password);
        }

        $status = $this->userModel->updateUser($data, $user_id);

        if (!$status) {
            $this->json(['errors' => 'Falha ao atualizar perfil. Tente novamente mais tarde!'], 500);
        }

        $this->json(['message' => 'Perfil atualizado com sucesso!']);
    }
}
