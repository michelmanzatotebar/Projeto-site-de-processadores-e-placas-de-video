<?php
namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Pedido;

class PedidoTest extends TestCase
{
    private $pedido;

    protected function setUp(): void
    {
        $this->pedido = new Pedido(
            1,
            'joao@email.com',
            2,
            4999.98
        );
    }

    public function testCriacaoPedido(): void
    {
        $this->assertInstanceOf(Pedido::class, $this->pedido);
    }

    public function testGettersRetornamValoresCorretos(): void
    {
        $this->assertEquals(1, $this->pedido->getId());
        $this->assertEquals('joao@email.com', $this->pedido->getEmailCliente());
        $this->assertEquals(2, $this->pedido->getQuantidade());
        $this->assertEquals(4999.98, $this->pedido->getPrecoTotal());
    }

    public function testFinalizarPedidoRetornaTrue(): void
    {
        $this->assertTrue($this->pedido->finalizarPedido());
    }
}
