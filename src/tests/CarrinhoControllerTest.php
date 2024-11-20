<?php

use PHPUnit\Framework\TestCase;

class CarrinhoControllerTest extends TestCase
{
    private $carrinhoModelMock;
    private $produtoModelMock;
    private $dbMock;
    private $carrinhoController;

    protected function setUp(): void
    {
        $this->dbMock = $this->createMock(PDO::class);
        $this->carrinhoModelMock = $this->createMock(Carrinho::class);
        $this->produtoModelMock = $this->createMock(Produto::class);
        
        $this->carrinhoController = new CarrinhoController($this->dbMock);
        
        $reflection = new ReflectionClass($this->carrinhoController);
        
        $carrinhoProperty = $reflection->getProperty('carrinhoModel');
        $carrinhoProperty->setAccessible(true);
        $carrinhoProperty->setValue($this->carrinhoController, $this->carrinhoModelMock);
        
        $produtoProperty = $reflection->getProperty('produtoModel');
        $produtoProperty->setAccessible(true);
        $produtoProperty->setValue($this->carrinhoController, $this->produtoModelMock);
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        $_POST = [];
        
        if (ob_get_length()) {
            ob_end_clean();
        }
    }

    public function testMostrarComUsuarioLogado()
    {
        $_SESSION['cliente_email'] = 'test@example.com';

        $expectedItems = [
            [
                'ID' => 1,
                'Email_cliente' => 'test@example.com',
                'Id_produto' => 1,
                'Quantidade' => 2,
                'Preco_produto' => 50.00,
                'Preco_total' => 100.00,
                'Produto_Nome' => 'Produto Test'
            ]
        ];

        $this->carrinhoModelMock->expects($this->once())
            ->method('listByCliente')
            ->with('test@example.com')
            ->willReturn($expectedItems);

        $this->expectOutputRegex('/mostrar\.php/');
        
        $this->carrinhoController->mostrar();
        
        $this->assertEquals($expectedItems, $GLOBALS['itens'] ?? null);
    }

    public function testMostrarSemUsuarioLogado()
    {
        unset($_SESSION['cliente_email']);

        $this->expectOutputString('');
        $this->carrinhoController->mostrar();
        
        $this->assertContains('Location: /cliente/login', xdebug_get_headers());
    }

    public function testMostrarComErro()
    {
        $_SESSION['cliente_email'] = 'test@example.com';

        $this->carrinhoModelMock->expects($this->once())
            ->method('listByCliente')
            ->willThrowException(new PDOException('Database error'));

        $this->expectOutputRegex('/erro\.php/');
        
        $this->carrinhoController->mostrar();
        
        $this->assertStringContainsString('Erro ao mostrar carrinho', $GLOBALS['erro']);
    }

    public function testAdicionarComUsuarioLogado()
    {
        $_SESSION['cliente_email'] = 'test@example.com';
        $_POST['id_produto'] = '1';
        $_POST['quantidade'] = '2';

        $produtoMock = [
            'ID' => 1,
            'Nome' => 'Produto Test',
            'Preco' => 50.00
        ];

        $this->produtoModelMock->expects($this->once())
            ->method('getById')
            ->with('1')
            ->willReturn($produtoMock);

        $this->carrinhoModelMock->expects($this->once())
            ->method('create')
            ->with(
                'test@example.com',
                '1',
                '2',
                50.00
            )
            ->willReturn(true);

        $this->carrinhoController->adicionar();
        
        $this->assertContains('Location: /carrinho', xdebug_get_headers());
    }

    public function testAdicionarSemUsuarioLogado()
    {
        unset($_SESSION['cliente_email']);

        $this->carrinhoController->adicionar();
        
        $this->assertContains('Location: /cliente/login', xdebug_get_headers());
    }

    public function testAdicionarProdutoInexistente()
    {
        $_SESSION['cliente_email'] = 'test@example.com';
        $_POST['id_produto'] = '999';
        $_POST['quantidade'] = '2';

        $this->produtoModelMock->expects($this->once())
            ->method('getById')
            ->with('999')
            ->willReturn(false);

        $this->carrinhoController->adicionar();
        
        $this->assertContains('Location: /produtos', xdebug_get_headers());
    }

    public function testAdicionarComErro()
    {
        $_SESSION['cliente_email'] = 'test@example.com';
        $_POST['id_produto'] = '1';
        $_POST['quantidade'] = '2';

        $produtoMock = [
            'ID' => 1,
            'Nome' => 'Produto Test',
            'Preco' => 50.00
        ];

        $this->produtoModelMock->expects($this->once())
            ->method('getById')
            ->willReturn($produtoMock);

        $this->carrinhoModelMock->expects($this->once())
            ->method('create')
            ->willReturn(false);

        $this->carrinhoController->adicionar();
        
        $this->assertContains('Location: /produto/1', xdebug_get_headers());
    }

    public function testAdicionarComExcecao()
    {
        $_SESSION['cliente_email'] = 'test@example.com';
        $_POST['id_produto'] = '1';
        $_POST['quantidade'] = '2';

        $this->produtoModelMock->expects($this->once())
            ->method('getById')
            ->willThrowException(new PDOException('Database error'));

        $this->expectOutputRegex('/erro\.php/');
        
        $this->carrinhoController->adicionar();
        
        $this->assertStringContainsString('Erro ao adicionar ao carrinho', $GLOBALS['erro']);
    }

    public function testRemoverComSucesso()
    {
        $_POST['id'] = '1';

        $this->carrinhoModelMock->expects($this->once())
            ->method('delete')
            ->with('1')
            ->willReturn(true);

        $this->carrinhoController->remover();
        
        $this->assertContains('Location: /carrinho', xdebug_get_headers());
    }

    public function testRemoverComErro()
    {
        $_POST['id'] = '1';

        $this->carrinhoModelMock->expects($this->once())
            ->method('delete')
            ->willThrowException(new PDOException('Database error'));

        $this->expectOutputRegex('/erro\.php/');
        
        $this->carrinhoController->remover();
        
        $this->assertStringContainsString('Erro ao remover item do carrinho', $GLOBALS['erro']);
    }

    public function testRemoverSemId()
    {
        $_POST = [];

        $this->carrinhoController->remover();
        
        $this->assertContains('Location: /carrinho', xdebug_get_headers());
    }
}