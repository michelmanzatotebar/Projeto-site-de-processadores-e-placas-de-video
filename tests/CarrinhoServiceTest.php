<?php
namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use App\Services\CarrinhoService;
use App\Repositories\CarrinhoRepository;
use App\Repositories\ProdutoRepository;
use App\Models\Produto;
use App\Models\Carrinho;

class CarrinhoServiceTest extends TestCase
{
    private $carrinhoService;
    private $carrinhoRepository;
    private $produtoRepository;

    protected function setUp(): void
    {
        $this->carrinhoRepository = $this->createMock(CarrinhoRepository::class);
        $this->produtoRepository = $this->createMock(ProdutoRepository::class);
        $this->carrinhoService = new CarrinhoService(
            $this->carrinhoRepository,
            $this->produtoRepository
        );
    }

    public function testAdicionarProdutoAoCarrinho(): void
    {
        
        $produto = new Produto(1, 'Produto Test', 'Categoria', 'Marca', 'Specs', 100.00);
        
        $this->produtoRepository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($produto);

        $this->carrinhoRepository
            ->expects($this->once())
            ->method('addItem')
            ->willReturn(true);

        
        $resultado = $this->carrinhoService->adicionarProduto('test@email.com', 1, 2);

        
        $this->assertTrue($resultado);
    }

    public function testAdicionarProdutoInexistenteAoCarrinho(): void
    {
        
        $this->produtoRepository
            ->expects($this->once())
            ->method('findById')
            ->with(999)
            ->willReturn(null);

        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Produto não encontrado');

        
        $this->carrinhoService->adicionarProduto('test@email.com', 999, 1);
    }

    public function testAtualizarQuantidadeNoCarrinho(): void
    {
        
        $this->carrinhoRepository
            ->expects($this->once())
            ->method('updateQuantidade')
            ->with(1, 3)
            ->willReturn(true);


        $resultado = $this->carrinhoService->atualizarQuantidade(1, 3);

        
        $this->assertTrue($resultado);
    }

    public function testRemoverItemDoCarrinho(): void
    {
        
        $this->carrinhoRepository
            ->expects($this->once())
            ->method('removeItem')
            ->with(1)
            ->willReturn(true);

        
        $resultado = $this->carrinhoService->removerItem(1);

        
        $this->assertTrue($resultado);
    }

    public function testBuscarCarrinhoPorEmail(): void
    {
        
        $itensCarrinho = [
            new Carrinho(1, 'test@email.com', 1, 2, 100.00)
        ];

        $this->carrinhoRepository
            ->expects($this->once())
            ->method('findByClienteEmail')
            ->with('test@email.com')
            ->willReturn($itensCarrinho);

        
        $resultado = $this->carrinhoService->getCarrinho('test@email.com');

        
        $this->assertCount(1, $resultado);
        $this->assertInstanceOf(Carrinho::class, $resultado[0]);
        $this->assertEquals('test@email.com', $resultado[0]->getEmailCliente());
    }
}