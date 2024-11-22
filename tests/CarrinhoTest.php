<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;
use PDOStatement;

require_once __DIR__ . '/../src/models/Carrinho.php';

class CarrinhoTest extends TestCase
{
    private $db;
    private $carrinho;
    private $stmt;

    protected function setUp(): void
    {
        $this->db = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->carrinho = new \Carrinho($this->db);
    }

    public function testCreate()
    {
        $email_cliente = 'test@example.com';
        $id_produto = 1;
        $quantidade = 2;
        $preco_produto = 100.00;

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->exactly(5))
            ->method('bindParam')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->carrinho->create($email_cliente, $id_produto, $quantidade, $preco_produto);
        $this->assertTrue($result);
    }

    public function testListByCliente()
    {
        $email_cliente = 'test@example.com';
        $expectedResult = [
            [
                'ID' => 1,
                'Email_cliente' => 'test@example.com',
                'Id_produto' => 1,
                'Quantidade' => 2,
                'Preco_produto' => 100.00,
                'Preco_total' => 200.00
            ]
        ];

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':email_cliente', $email_cliente);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedResult);

        $result = $this->carrinho->listByCliente($email_cliente);
        $this->assertEquals($expectedResult, $result);
    }

    public function testDelete()
    {
        $id = 1;

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->carrinho->delete($id);
        $this->assertEquals(1, $result);
    }
}