<?php

use PHPUnit\Framework\TestCase;

class CarrinhoTest extends TestCase
{
    private $pdoMock;
    private $carrinho;
    private $stmtMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        
        $this->stmtMock = $this->createMock(PDOStatement::class);
        
        $this->carrinho = new Carrinho($this->pdoMock);
    }

    public function testCreate()
    {
        $email_cliente = "test@example.com";
        $id_produto = 1;
        $quantidade = 2;
        $preco_produto = 50.00;
        $preco_total = 100.00;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains("INSERT INTO Carrinho"))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->exactly(5))
            ->method('bindParam')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->carrinho->create($email_cliente, $id_produto, $quantidade, $preco_produto);
        $this->assertTrue($result);
    }

    public function testListByCliente()
    {
        $email_cliente = "test@example.com";
        $expectedData = [
            [
                'ID' => 1,
                'Email_cliente' => 'test@example.com',
                'Id_produto' => 1,
                'Quantidade' => 2,
                'Preco_produto' => 50.00,
                'Preco_total' => 100.00,
                'Produto_Nome' => 'Produto Test'
            ]
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains("SELECT c.*, p.Nome as Produto_Nome"))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':email_cliente', $email_cliente)
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedData);

        $result = $this->carrinho->listByCliente($email_cliente);
        $this->assertEquals($expectedData, $result);
    }

    public function testUpdate()
    {
        $id = 1;
        $nova_quantidade = 3;
        $item_atual = [
            'ID' => 1,
            'Preco_produto' => 50.00,
            'Quantidade' => 2
        ];

        $carrinho = $this->getMockBuilder(Carrinho::class)
            ->setConstructorArgs([$this->pdoMock])
            ->onlyMethods(['getById'])
            ->getMock();

        $carrinho->expects($this->once())
            ->method('getById')
            ->with($id)
            ->willReturn($item_atual);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains("UPDATE Carrinho"))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->exactly(3))
            ->method('bindParam')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $carrinho->update($id, $nova_quantidade);
        $this->assertEquals(1, $result);
    }

    public function testUpdateWithInvalidId()
    {
        $id = 999;
        $nova_quantidade = 3;

        $carrinho = $this->getMockBuilder(Carrinho::class)
            ->setConstructorArgs([$this->pdoMock])
            ->onlyMethods(['getById'])
            ->getMock();

        $carrinho->expects($this->once())
            ->method('getById')
            ->with($id)
            ->willReturn(false);

        $result = $carrinho->update($id, $nova_quantidade);
        $this->assertFalse($result);
    }

    public function testDelete()
    {
        $id = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains("DELETE FROM Carrinho WHERE ID = :id"))
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

        $result = $this->carrinho->delete($id);
        $this->assertEquals(1, $result);
    }

    public function testDeleteNotFound()
    {
        $id = 999;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains("DELETE FROM Carrinho WHERE ID = :id"))
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

        $result = $this->carrinho->delete($id);
        $this->assertEquals(0, $result);
    }

    public function testCreateThrowsException()
    {
        $email_cliente = "test@example.com";
        $id_produto = 1;
        $quantidade = 2;
        $preco_produto = 50.00;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willThrowException(new PDOException('Database error'));

        $this->expectException(PDOException::class);
        $this->carrinho->create($email_cliente, $id_produto, $quantidade, $preco_produto);
    }

    public function testListByClienteThrowsException()
    {
        $email_cliente = "test@example.com";

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willThrowException(new PDOException('Database error'));

        $this->expectException(PDOException::class);
        $this->carrinho->listByCliente($email_cliente);
    }
}