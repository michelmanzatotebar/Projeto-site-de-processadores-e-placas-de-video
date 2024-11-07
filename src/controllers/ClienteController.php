?<?php

class ClienteController {
    private $clienteModel;
    
    public function __construct($db) {
        $this->clienteModel = new Cliente($db);
    }
    
    public function formLogin() {
        require_once 'Views/cliente/login.php';
    }
    
    public function login() {
        try {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $senha = $_POST['senha'] ?? '';
            
            if (empty($email) || empty($senha)) {
                $erro = "Preencha todos os campos";
                require_once 'Views/cliente/login.php';
                return;
            }
            
            $cliente = $this->clienteModel->getByEmail($email);
            if ($cliente && password_verify($senha, $cliente['senha'])) {
                $_SESSION['cliente_email'] = $email;
                header('Location: /');
                exit;
            } else {
                $erro = "Email ou senha inválidos";
                require_once 'Views/cliente/login.php';
            }
        } catch(PDOException $e) {
            $erro = "Erro ao realizar login: " . $e->getMessage();
            require_once 'Views/erro.php';
        }
    }
    
    public function formCadastro() {
        require_once 'Views/cliente/cadastro.php';
    }
    
    public function cadastrar() {
        try {
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_SPECIAL_CHARS);
            $numero = filter_input(INPUT_POST, 'numero', FILTER_SANITIZE_SPECIAL_CHARS);
            
            if (empty($nome) || empty($email) || empty($endereco) || empty($numero)) {
                $erro = "Preencha todos os campos";
                require_once 'Views/cliente/cadastro.php';
                return;
            }
            
            if ($this->clienteModel->create($nome, $endereco, $numero, $email)) {
                header('Location: /cliente/login');
                exit;
            } else {
                $erro = "Erro ao cadastrar";
                require_once 'Views/cliente/cadastro.php';
            }
        } catch(PDOException $e) {
            $erro = "Erro ao cadastrar: " . $e->getMessage();
            require_once 'Views/erro.php';
        }
    }
}