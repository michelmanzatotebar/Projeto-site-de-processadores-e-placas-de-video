<?php

use PHPUnit\Framework\TestCase;

class PedidoTest extends TestCase
{
    private $pdoMock;
    private $pedido;
    private $stmtMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        
        $this->stmtMock = $this->createMock(PDOStatement::class);
        
        $this->pedido = new Pedido($this->pdoMock);
    }

    public function testCreate()
    {
        $email_cliente = "test@example.com";
        $quantidade = 2;
        $preco_total = 100.00;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains("INSERT INTO Pedido"))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->exactly(3))
            ->method('bindParam')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->pedido->create($email_cliente, $quantidade, $preco_total);
        $this->assertTrue($result);
    }

    public function testList()
    {
        $expectedData = [
            ['ID' => 1, 'Email_cliente' => 'test@example.com', 'Quantidade' => 2, 'Preco_total' => 100.00],
            ['ID' => 2, 'Email_cliente' => 'test2@example.com', 'Quantidade' => 1, 'Preco_total' => 50.00]
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains("SELECT * FROM Pedido"))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedData);

        $result = $this->pedido->list();
        $this->assertEquals($expectedData, $result);
    }

    public function testGetById()
    {
        $id = 1;
        $expectedData = [
            'ID' => 1,
            'Email_cliente' => 'test@example.com',
            'Quantidade' => 2,
            'Preco_total' => 100.00
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains("SELECT * FROM Pedido WHERE ID = :id"))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id)
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedData);

        $result = $this->pedido->getById($id);
        $this->assertEquals($expectedData, $result);
    }

    public function testDelete()
    {
        $id = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains("DELETE FROM Pedido WHERE ID = :id"))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id)
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->pedido->delete($id);
        $this->assertEquals(1, $result);
    }

    public function testDeleteNotFound()
    {
        $id = 999;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains("DELETE FROM Pedido WHERE ID = :id"))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id)
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('rowCount')
            ->willReturn(0);


        $result = $this->pedido->delete($id);
        $this->assertEquals(0, $result);
    }

    public function testCreateThrowsException()
    {
        $email_cliente = "test@example.com";
        $quantidade = 2;
        $preco_total = 100.00;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willThrowException(new PDOException('Database error'));
        $this->expectException(PDOException::class);
        $this->pedido->create($email_cliente, $quantidade, $preco_total);
    }
}