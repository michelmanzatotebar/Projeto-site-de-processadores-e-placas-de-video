<?php

use PHPUnit\Framework\TestCase;

class ClienteTest extends TestCase
{
    private $cliente;
    private $db;
    private $stmt;

    protected function setUp(): void
    {
        $this->db = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->cliente = new Cliente($this->db);
    }

    public function testCreate()
    {
        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $resultado = $this->cliente->create('John Doe', 'Rua Test', '123', 'john@email.com');
        $this->assertTrue($resultado);
    }

    public function testList()
    {
        $esperado = [
            ['ID' => 1, 'Nome' => 'John Doe', 'Email' => 'john@email.com'],
            ['ID' => 2, 'Nome' => 'Jane Doe', 'Email' => 'jane@email.com']
        ];

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn($esperado);

        $resultado = $this->cliente->list();
        $this->assertEquals($esperado, $resultado);
    }

    public function testGetById()
    {
        $esperado = ['ID' => 1, 'Nome' => 'John Doe', 'Email' => 'john@email.com'];

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->willReturn($esperado);

        $resultado = $this->cliente->getById(1);
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

        $resultado = $this->cliente->update(1, 'John Updated', 'Nova Rua', '456', 'john@email.com');
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

        $resultado = $this->cliente->delete(1);
        $this->assertEquals(1, $resultado);
    }
}