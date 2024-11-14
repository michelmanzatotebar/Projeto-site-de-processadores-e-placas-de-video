<?php

use PHPUnit\Framework\TestCase;

class ProdutoTest extends TestCase
{
    private $produto;
    private $db;
    private $stmt;

    protected function setUp(): void
    {
        $this->db = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->produto = new Produto($this->db);
    }

    public function testCreate()
    {
        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $resultado = $this->produto->create('Produto Test', 'Categoria Test', 'Marca Test', 'Specs Test', 100.00);
        $this->assertTrue($resultado);
    }

    public function testList()
    {
        $esperado = [
            ['ID' => 1, 'Nome' => 'Produto 1', 'Preco' => 100.00],
            ['ID' => 2, 'Nome' => 'Produto 2', 'Preco' => 200.00]
        ];

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn($esperado);

        $resultado = $this->produto->list();
        $this->assertEquals($esperado, $resultado);
    }

    public function testGetById()
    {
        $esperado = ['ID' => 1, 'Nome' => 'Produto Test', 'Preco' => 100.00];

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->willReturn($esperado);

        $resultado = $this->produto->getById(1);
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

        $resultado = $this->produto->update(1, 'Produto Updated', 'Nova Categoria', 'Nova Marca', 'Novas Specs', 150.00);
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

        $resultado = $this->produto->delete(1);
        $this->assertEquals(1, $resultado);
    }
}