<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;
use PDOStatement;

require_once __DIR__ . '/../src/models/Pedido.php';

class PedidoTest extends TestCase
{
    private $db;
    private $pedido;
    private $stmt;

    protected function setUp(): void
    {
        $this->db = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->pedido = new \Pedido($this->db);
    }

    public function testCreate()
    {
        $email_cliente = 'test@example.com';
        $quantidade = 2;
        $preco_total = 200.00;

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->exactly(3))
            ->method('bindParam')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->pedido->create($email_cliente, $quantidade, $preco_total);
        $this->assertTrue($result);
    }

    public function testList()
    {
        $expectedResult = [
            [
                'ID' => 1,
                'Email_cliente' => 'test@example.com',
                'Quantidade' => 2,
                'Preco_total' => 200.00
            ]
        ];

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedResult);

        $result = $this->pedido->list();
        $this->assertEquals($expectedResult, $result);
    }

    public function testGetById()
    {
        $id = 1;
        $expectedResult = [
            'ID' => 1,
            'Email_cliente' => 'test@example.com',
            'Quantidade' => 2,
            'Preco_total' => 200.00
        ];

        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedResult);

        $result = $this->pedido->getById($id);
        $this->assertEquals($expectedResult, $result);
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM Pedido WHERE ID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Erro ao deletar pedido: " . $e->getMessage());
            throw $e;
        }
    }
    public function testCreateThrowsException()
    {
        $email_cliente = 'test@example.com';
        $quantidade = 2;
        $preco_total = 200.00;

        $this->db->expects($this->once())
            ->method('prepare')
            ->willThrowException(new PDOException('Database error'));

        $this->expectException(PDOException::class);
        $this->pedido->create($email_cliente, $quantidade, $preco_total);
    }
}