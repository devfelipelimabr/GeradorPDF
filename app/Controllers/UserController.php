<?php

namespace App\Controllers;

use App\Entities\User;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    private $session;
    private $userModel;

    public function __construct()
    {
        $this->session = service('session');
        $this->userModel = new UserModel();
    }

    public function register()
    {
        // Recebe dados do request
        $data = [
            'name'                => $this->request->getVar('name'),
            'email'               => $this->request->getVar('email'),
            'password'            => $this->request->getVar('password'),
            'password_confirm'    => $this->request->getVar('password_confirm'),
        ];

        // Validação básica (pode-se adicionar validação mais complexa)
        if (empty($data['name']) || empty($data['email']) || empty($data['password']) || empty($data['password_confirm'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Dados incompletos'])
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        // Cria entidade User e define a senha
        $user = new User($data);

        // Salva o usuário
        $this->userModel->save($user);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Usuário registrado com sucesso']);
    }

    public function login()
    {
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Busca usuário por nome
        $user = $this->userModel->getUserByEmail($email);

        if (!$user || !$user->checkPassword($password)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Credenciais inválidas'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        // Configura sessão de autenticação
        $this->session->set('user_id', $user->id);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Login realizado com sucesso']);
    }

    public function logout()
    {
        $this->session->destroy();
        return $this->response->setJSON(['status' => 'success', 'message' => 'Logout realizado com sucesso']);
    }
}
