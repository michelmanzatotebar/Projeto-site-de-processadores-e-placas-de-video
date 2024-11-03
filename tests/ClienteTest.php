<?php
namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Cliente;

class ClienteTest extends TestCase
{
    private $cliente;

    protected function setUp(): void
    {
        $this->cliente = new Cliente(
            1,
            'João Silva',
            'Rua das Flores, 123',
            '11999999999',
            'joao@email.com'
        );
    }

    public function testCriacaoCliente(): void
    {
        $this->assertInstanceOf(Cliente::class, $this->cliente);
    }

    public function testGettersRetornamValoresCorretos(): void
    {
        $this->assertEquals(1, $this->cliente->getId());
        $this->assertEquals('João Silva', $this->cliente->getNome());
        $this->assertEquals('Rua das Flores, 123', $this->cliente->getEndereco());
        $this->assertEquals('11999999999', $this->cliente->getNumero());
        $this->assertEquals('joao@email.com', $this->cliente->getEmail());
    }

    public function testToArrayRetornaArrayCompleto(): void
    {
        $expected = [
            'id' => 1,
            'nome' => 'João Silva',
            'endereco' => 'Rua das Flores, 123',
            'numero' => '11999999999',
            'email' => 'joao@email.com'
        ];

        $this->assertEquals($expected, $this->cliente->toArray());
    }
}