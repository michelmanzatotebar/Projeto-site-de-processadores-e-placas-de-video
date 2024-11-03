?<?php
class ClienteController {
    private $clienteModel;

    public function __construct() {
        $this->clienteModel = new Cliente();
    }

    public function formLogin() {
    
        require_once 'Views/cliente/login.php';
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if($this->clienteModel->autenticar($email, $senha)) {
            $_SESSION['cliente_email'] = $email;
            header('Location: /');
        } else {
            $erro = "Email ou senha inválidos";
            require_once 'Views/cliente/login.php';
        }
    }

    public function formCadastro() {
         require_once 'Views/cliente/cadastro.php';
    }

    public function cadastrar() {
  
        $dados = [
            'nome' => $_POST['nome'],
            'email' => $_POST['email'],
            'endereco' => $_POST['endereco'],
            'numero' => $_POST['numero']
        ];

        if($this->clienteModel->cadastrar($dados)) {
            header('Location: /cliente/login');
        } else {
            $erro = "Erro ao cadastrar";
            require_once 'Views/cliente/cadastro.php';
        }
    }
}