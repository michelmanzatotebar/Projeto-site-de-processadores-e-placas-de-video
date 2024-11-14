<?php

use PHPUnit\Framework\TestCase;

class CarrinhoControllerTest extends TestCase
{
    private $carrinhoController;
    private $carrinhoModel;
    private $produtoModel;
    private $db;
    
    protected function setUp(): void
    {
  
        $this->db = $this->createMock(PDO::class);
        $this->carrinhoModel = $this->createMock(Carrinho::class);
        $this->produtoModel = $this->createMock(Produto::class);        
        $this->carrinhoController = new CarrinhoController($this->db);
        
      
        if (!isset($_SESSION)) {
            $_SESSION = [];
        }
    }
    
    public function testMostrarRedirecionaQuandoNaoLogado()
    {
      
        $_SESSION = [];
        
        
        ob_start();
        $this->carrinhoController->mostrar();
        $headers = xdebug_get_headers();
        ob_end_clean();
     
        $this->assertContains('Location: /cliente/login', $headers);
    }
    
    public function testMostrarExibeCarrinhoQuandoLogado()
    {
       
        $_SESSION['cliente_email'] = 'test@example.com';
        $itensEsperados = [
            ['ID' => 1, 'Produto_ID' => 1, 'Quantidade' => 2]
        ];
        
        $this->carrinhoModel->expects($this->once())
            ->method('listByCliente')
            ->with('test@example.com')
            ->willReturn($itensEsperados);
            
        ob_start();
        $this->carrinhoController->mostrar();
        $output = ob_get_clean();
      
        $this->assertStringContainsString('Views/carrinho/mostrar.php', $output);
    }
    
    public function testAdicionarProdutoAoCarrinho()
    {
        $_SESSION['cliente_email'] = 'test@example.com';
        $_POST['id_produto'] = '1';
        $_POST['quantidade'] = '2';
        
        $produto = ['ID' => 1, 'Preco' => 10.00];
        
        $this->produtoModel->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn($produto);
            
        $this->carrinhoModel->expects($this->once())
            ->method('create')
            ->with('test@example.com', 1, 2, 10.00)
            ->willReturn(true);
        
        ob_start();
        $this->carrinhoController->adicionar();
        $headers = xdebug_get_headers();
        ob_end_clean();
  
        $this->assertContains('Location: /carrinho', $headers);
    }
    
    public function testRemoverItemDoCarrinho()
    {
    
        $_POST['id'] = '1';
        
        $this->carrinhoModel->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);
            

        ob_start();
        $this->carrinhoController->remover();
        $headers = xdebug_get_headers();
        ob_end_clean();
        

        $this->assertContains('Location: /carrinho', $headers);
    }
}