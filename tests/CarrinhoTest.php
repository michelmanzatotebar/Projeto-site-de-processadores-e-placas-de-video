<?php
namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Carrinho;

class CarrinhoTest extends TestCase
{
    private $carrinho;

    protected function setUp(): void
    {
        $this->carrinho = new Carrinho(
            1,
            'joao@email.com',
            1,
            2,
            2499.99
        );
    }

    public function testCriacaoCarrinho(): void
    {
        $this->assertInstanceOf(Carrinho::class, $this->carrinho);
    }

    public function testGettersRetornamValoresCorretos(): void
    {
        $this->assertEquals(1, $this->carrinho->getId());
        $this->assertEquals('joao@email.com', $this->carrinho->getEmailCliente());
        $this->assertEquals(1, $this->carrinho->getIdProduto());
        $this->assertEquals(2, $this->carrinho->getQuantidade());
        $this->assertEquals(2499.99, $this->carrinho->getPrecoProtudo());
        $this->assertEquals(4999.98, $this->carrinho->getPrecoTotal());
    }

    public function testAtualizacaoQuantidadeRecalculaPrecoTotal(): void
    {
        $this->carrinho->setQuantidade(3);
        $this->assertEquals(3, $this->carrinho->getQuantidade());
        $this->assertEquals(7499.97, $this->carrinho->getPrecoTotal());
    }
}