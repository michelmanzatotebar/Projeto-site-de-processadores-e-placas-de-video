<?php
namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Produto;

class ProdutoTest extends TestCase
{
    private $produto;

    protected function setUp(): void
    {
        $this->produto = new Produto(
            1,
            'Processador AMD Ryzen 7',
            'Processador',
            'AMD',
            '8 núcleos, 16 threads, 3.8GHz',
            2499.99
        );
    }

    public function testCriacaoProduto(): void
    {
        $this->assertInstanceOf(Produto::class, $this->produto);
    }

    public function testGettersRetornamValoresCorretos(): void
    {
        $this->assertEquals(1, $this->produto->getId());
        $this->assertEquals('Processador AMD Ryzen 7', $this->produto->getNome());
        $this->assertEquals('Processador', $this->produto->getCategoria());
        $this->assertEquals('AMD', $this->produto->getMarca());
        $this->assertEquals('8 núcleos, 16 threads, 3.8GHz', $this->produto->getEspecificacoes());
        $this->assertEquals(2499.99, $this->produto->getPreco());
    }

    public function testToArrayRetornaArrayCompleto(): void
    {
        $expected = [
            'id' => 1,
            'nome' => 'Processador AMD Ryzen 7',
            'categoria' => 'Processador',
            'marca' => 'AMD',
            'especificacoes' => '8 núcleos, 16 threads, 3.8GHz',
            'preco' => 2499.99
        ];

        $this->assertEquals($expected, $this->produto->toArray());
    }
}