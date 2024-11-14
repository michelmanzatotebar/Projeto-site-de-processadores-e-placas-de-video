<?php

use PHPUnit\Framework\TestCase;

class ClienteControllerTest extends TestCase
{
    private $clienteController;
    private $clienteModel;
    private $db;
    
    protected function setUp(): void
    {
        $this->db = $this->createMock(PDO::class);
        $this->clienteModel = $this->createMock(Cliente::class);
        $this->clienteController = new ClienteController($this->db);
        
        if (!isset($_SESSION)) {
            $_SESSION = [];
        }
    }
    
    public function testLoginComCredenciaisValidas()
    {
        $_POST['email'] = 'test@example.com';
        $_POST['senha'] = 'senha123';
        
        $cliente = [
            'email' => 'test@example.com',
            'senha' => password_hash('senha123', PASSWORD_DEFAULT)
        ];
        
        $this->clienteModel->expects($this->once())
            ->method('getByEmail')
            ->with('test@example.com')
            ->willReturn($cliente);
            
        ob_start();
        $this->clienteController->login();
        $headers = xdebug_get_headers();
        ob_end_clean();
        
        $this->assertContains('Location: /', $headers);
        $this->assertEquals('test@example.com', $_SESSION['cliente_email']);
    }
    
    public function testLoginComCredenciaisInvalidas()
    {
        $_POST['email'] = 'test@example.com';
        $_POST['senha'] = 'senha_errada';
        
        $this->clienteModel->expects($this->once())
            ->method('getByEmail')
            ->with('test@example.com')
            ->willReturn(null);
            
  
        ob_start();
        $this->clienteController->login();
        $output = ob_get_clean();
        

        $this->assertStringContainsString('Email ou senha inválidos', $output);
    }
    
    public function testCadastroComDadosValidos()
    {
        
        $_POST['nome'] = 'Teste';
        $_POST['email'] = 'test@example.com';
        $_POST['endereco'] = 'Rua Teste';
        $_POST['numero'] = '123';
        
        $this->clienteModel->expects($this->once())
            ->method('create')
            ->with('Teste', 'Rua Teste', '123', 'test@example.com')
            ->willReturn(true);
            
      
        ob_start();
        $this->clienteController->cadastrar();
        $headers = xdebug_get_headers();
        ob_end_clean();
        
       
        $this->assertContains('Location: /cliente/login', $headers);
    }
    
    public function testCadastroComDadosIncompletos()
    {
      
        $_POST['nome'] = 'Teste';
        $_POST['email'] = '';  
        
       
        ob_start();
        $this->clienteController->cadastrar();
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Preencha todos os campos', $output);
    }
}