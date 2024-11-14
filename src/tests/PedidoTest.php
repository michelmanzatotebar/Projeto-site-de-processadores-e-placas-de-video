<?php

use PHPUnit\Framework\TestCase;

class PedidoTest extends TestCase
{
    private $pedido;
    private $db;
    private $stmt;

    protected function setUp(): void
    {
        $this->db = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->pedido = new Pedido($this->db);
    }

    public function testCreate()
    {
        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $resultado = $this->pedido->create('test@email.com', 2, 200.00);
        $this->assertTrue($resultado);
    }

    public function testList()
    {
        $esperado = [
            ['ID' => 1, 'Email_cliente' => 'test@email.com', 'Preco_total' => 200.00],
            ['ID' => 2, 'Email_cliente' => 'test2@email.com', 'Preco_total' => 150.00]
        ];

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn($esperado);

        $resultado = $this->pedido->list();
        $this->assertEquals($esperado, $resultado);
    }

    public function testGetById()
    {
        $esperado = ['ID' => 1, 'Email_cliente' => 'test@email.com', 'Preco_total' => 200.00];

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->willReturn($esperado);

        $resultado = $this->pedido->getById(1);
        $this->assertEquals($esperado, $resultado);
    }

    public function testUpdate()
    {
        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $resultado = $this->pedido->update(1, 3, 300.00);
        $this->assertEquals(1, $resultado);
    }

    public function testDelete()
    {
        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $resultado = $this->pedido->delete(1);
        $this->assertEquals(1, $resultado);
    }
}