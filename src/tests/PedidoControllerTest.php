<?php

use PHPUnit\Framework\TestCase;

class PedidoControllerTest extends TestCase
{
    private $pedidoController;
    private $pedidoModel;
    private $carrinhoModel;
    private $db;
    
    protected function setUp(): void
    {
        $this->db = $this->createMock(PDO::class);
        $this->pedidoModel = $this->createMock(Pedido::class);
        $this->carrinhoModel = $this->createMock(Carrinho::class);
        $this->pedidoController = new PedidoController($this->db);
        
        if (!isset($_SESSION)) {
            $_SESSION = [];
        }
    }
    
    public function testFinalizarRedirecionaQuandoNaoLogado()
    {
    
        $_SESSION = [];
        
       
        ob_start();
        $this->pedidoController->finalizar();
        $headers = xdebug_get_headers();
        ob_end_clean();
        
        
        $this->assertContains('Location: /cliente/login', $headers);
    }
    
    public function testFinalizarExibeTelaComItensDoCarrinho()
    {
        $_SESSION['cliente_email'] = 'test@example.com';
        $itensEsperados = [
            [
                'ID' => 1,
                'Produto_ID' => 1,
                'Quantidade' => 2,
                'Preco_total' => 100.00
            ]
        ];
        
        $this->carrinhoModel->expects($this->once())
            ->method('listByCliente')
            ->with('test@example.com')
            ->willReturn($itensEsperados);
            
      
        ob_start();
        $this->pedidoController->finalizar();
        $output = ob_get_clean();
        
      
        $this->assertStringContainsString('Views/pedido/finalizar.php', $output);
    }
    
    public function testConfirmarPedidoComSucesso()
    {
        
        $_SESSION['cliente_email'] = 'test@example.com';
        $itensCarrinho = [
            [
                'ID' => 1,
                'Produto_ID' => 1,
                'Quantidade' => 2,
                'Preco_total' => 100.00
            ],
            [
                'ID' => 2,
                'Produto_ID' => 2,
                'Quantidade' => 1,
                'Preco_total' => 50.00
            ]
        ];
        
        $this->carrinhoModel->expects($this->once())
            ->method('listByCliente')
            ->with('test@example.com')
            ->willReturn($itensCarrinho);
            
        $this->pedidoModel->expects($this->once())
            ->method('create')
            ->with('test@example.com', 2, 150.00)
            ->willReturn(true);
            
        $this->carrinhoModel->expects($this->exactly(2))
            ->method('delete')
            ->willReturn(true);
            
        ob_start();
        $this->pedidoController->confirmar();
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Views/pedido/sucesso.php', $output);
    }
    
    public function testConfirmarRedirecionaQuandoCarrinhoVazio()
    {
       
        $_SESSION['cliente_email'] = 'test@example.com';
        
        $this->carrinhoModel->expects($this->once())
            ->method('listByCliente')
            ->with('test@example.com')
            ->willReturn([]);
            
        
        ob_start();
        $this->pedidoController->confirmar();
        $headers = xdebug_get_headers();
        ob_end_clean();
       
        $this->assertContains('Location: /carrinho', $headers);
    }
    
    public function testConfirmarExibeErroQuandoFalha()
    {
        $_SESSION['cliente_email'] = 'test@example.com';
        $itensCarrinho = [
            [
                'ID' => 1,
                'Produto_ID' => 1,
                'Quantidade' => 2,
                'Preco_total' => 100.00
            ]
        ];
        
        $this->carrinhoModel->expects($this->once())
            ->method('listByCliente')
            ->willReturn($itensCarrinho);
            
        $this->pedidoModel->expects($this->once())
            ->method('create')
            ->willReturn(false);
   
        ob_start();
        $this->pedidoController->confirmar();
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Erro ao finalizar pedido', $output);
    }
}