<?php

use PHPUnit\Framework\TestCase;

class CarrinhoTest extends TestCase
{
    private $carrinho;
    private $db;
    private $stmt;

    protected function setUp(): void
    {
        $this->db = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->carrinho = new Carrinho($this->db);
    }

    public function testCreate()
    {
        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $resultado = $this->carrinho->create('test@email.com', 1, 2, 100.00);
        $this->assertTrue($resultado);
    }

    public function testList()
    {
        $esperado = [
            ['ID' => 1, 'Email_cliente' => 'test@email.com', 'Quantidade' => 2],
            ['ID' => 2, 'Email_cliente' => 'test2@email.com', 'Quantidade' => 1]
        ];

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn($esperado);

        $resultado = $this->carrinho->list();
        $this->assertEquals($esperado, $resultado);
    }

    public function testGetById()
    {
        $esperado = ['ID' => 1, 'Email_cliente' => 'test@email.com', 'Quantidade' => 2];

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->willReturn($esperado);

        $resultado = $this->carrinho->getById(1);
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

        $resultado = $this->carrinho->update(1, 3);
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

        $resultado = $this->carrinho->delete(1);
        $this->assertEquals(1, $resultado);
    }
}