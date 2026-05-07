<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Helpers\Crypt;
use App\Helpers\Hash;
use App\Models\UserModel;
use Exception;

class AuthController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function loginForm()
    {
        $this->layout("auth/login");
    }

    public function login(Request $request)
    {
        $fields_required = [
            "email" => "Email é obrigatório",
            "password" => "Senha é obrigatória",
        ];

        if (!$request->validate($fields_required)) {
            $this->json(['errors' => $request->errors()], 400);
        }

        $email = $request->email("email");
        $password = $request->string("password");

        $user = $this->userModel->findUserByEmail($email);

        if (!$user || !Hash::check($password, $user['password'])) {
            $this->json(['errors' => 'Email ou senha inválidos'], 401);
        }

        Auth::login($user['id']);

        $this->redirect("/");
    }

    public function registerForm()
    {
        $this->layout("auth/register");
    }

    public function register(Request $request)
    {
        $fields_required = [
            "name" => "Nome é obrigatório",
            "email" => "Email é obrigatório",
            "password" => "Senha é obrigatória",
        ];

        if (!$request->validate($fields_required)) {
            $this->json(['errors' => $request->errors()], 400);
        }

        $data = [
            "name" => $request->string("name"),
            "email" => $request->email("email"),
            "password" => Hash::make($request->string("password")),
            "token" => Crypt::encrypt($request->string("token")),
        ];

        if ($this->userModel->userExists($data['email'])) {
            $this->json(['errors' => 'Email já cadastrado'], 400);
        }

        try {
            $id = $this->userModel->create($data);

            Auth::login($id);

            $this->redirect("/");
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->json(['errors' => 'Falha ao realizar o cadastro. Tente novamente mais tarde!'], 500);
        }
    }

    public function logout()
    {
        Auth::logout();
        $this->redirect("auth/login");
    }
}
