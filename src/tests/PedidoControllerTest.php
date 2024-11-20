<?php

use PHPUnit\Framework\TestCase;

class PhpInputStreamMock
{
    private static $data = '';
    
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        return true;
    }
    
    public function stream_read($count)
    {
        $ret = substr(self::$data, 0, $count);
        self::$data = substr(self::$data, $count);
        return $ret;
    }
    
    public function stream_eof()
    {
        return empty(self::$data);
    }
    
    public function stream_stat()
    {
        return [];
    }
    
    public static function setData($data)
    {
        self::$data = $data;
    }
}

class PedidoControllerTest extends TestCase
{
    private $pedidoModelMock;
    private $dbMock;
    private $pedidoController;

    protected function setUp(): void
    {
        $this->dbMock = $this->createMock(PDO::class);
        $this->pedidoModelMock = $this->createMock(Pedido::class);
        
        $this->pedidoController = new PedidoController($this->dbMock);
        
        $reflection = new ReflectionClass($this->pedidoController);
        $property = $reflection->getProperty('pedidoModel');
        $property->setAccessible(true);
        $property->setValue($this->pedidoController, $this->pedidoModelMock);
    }

    protected function tearDown(): void
    {
        if (ob_get_length()) {
            ob_end_clean();
        }
    }

    public function testCreateSuccess()
    {
        $testData = [
            'email_cliente' => 'test@example.com',
            'quantidade' => 2,
            'preco_total' => 100.00
        ];

        stream_wrapper_unregister('php');
        stream_wrapper_register('php', 'PhpInputStreamMock');
        PhpInputStreamMock::setData(json_encode($testData));

        $this->pedidoModelMock->expects($this->once())
            ->method('create')
            ->with(
                $testData['email_cliente'],
                $testData['quantidade'],
                $testData['preco_total']
            )
            ->willReturn(true);

        ob_start();
        $this->pedidoController->create();
        $output = ob_get_clean();

        stream_wrapper_restore('php');

        $response = json_decode($output, true);
        $this->assertEquals('Pedido criado com sucesso.', $response['message']);
        $this->assertEquals(201, http_response_code());
    }

    public function testCreateWithInvalidData()
    {
        $testData = [
            'email_cliente' => 'test@example.com'
      
        ];

        stream_wrapper_unregister('php');
        stream_wrapper_register('php', 'PhpInputStreamMock');
        PhpInputStreamMock::setData(json_encode($testData));

        ob_start();
        $this->pedidoController->create();
        $output = ob_get_clean();

        stream_wrapper_restore('php');

        $response = json_decode($output, true);
        $this->assertEquals('Dados incompletos.', $response['message']);
        $this->assertEquals(400, http_response_code());
    }

    public function testCreateWithDatabaseError()
    {
        $testData = [
            'email_cliente' => 'test@example.com',
            'quantidade' => 2,
            'preco_total' => 100.00
        ];

        stream_wrapper_unregister('php');
        stream_wrapper_register('php', 'PhpInputStreamMock');
        PhpInputStreamMock::setData(json_encode($testData));

        $this->pedidoModelMock->expects($this->once())
            ->method('create')
            ->willThrowException(new PDOException('Database error'));

        ob_start();
        $this->pedidoController->create();
        $output = ob_get_clean();

        stream_wrapper_restore('php');

        $response = json_decode($output, true);
        $this->assertArrayHasKey('error', $response);
        $this->assertEquals(500, http_response_code());
    }

    public function testListSuccess()
    {
        $expectedPedidos = [
            [
                'id' => 1,
                'email_cliente' => 'test@example.com',
                'quantidade' => 2,
                'preco_total' => 100.00
            ],
            [
                'id' => 2,
                'email_cliente' => 'test2@example.com',
                'quantidade' => 1,
                'preco_total' => 50.00
            ]
        ];

        $this->pedidoModelMock->expects($this->once())
            ->method('list')
            ->willReturn($expectedPedidos);

        ob_start();
        $this->pedidoController->list();
        $output = ob_get_clean();

        $this->assertEquals($expectedPedidos, json_decode($output, true));
    }

    public function testListWithError()
    {
        $this->pedidoModelMock->expects($this->once())
            ->method('list')
            ->willThrowException(new Exception('Erro ao listar pedidos'));

        ob_start();
        $this->pedidoController->list();
        $output = ob_get_clean();

        $response = json_decode($output, true);
        $this->assertEquals('Erro ao listar pedidos', $response['error']);
        $this->assertEquals(500, http_response_code());
    }

    public function testGetByIdSuccess()
    {
        $id = 1;
        $expectedPedido = [
            'id' => 1,
            'email_cliente' => 'test@example.com',
            'quantidade' => 2,
            'preco_total' => 100.00
        ];

        $this->pedidoModelMock->expects($this->once())
            ->method('getById')
            ->with($id)
            ->willReturn($expectedPedido);

        ob_start();
        $this->pedidoController->getById($id);
        $output = ob_get_clean();

        $this->assertEquals($expectedPedido, json_decode($output, true));
    }

    public function testGetByIdNotFound()
    {
        $id = 999;

        $this->pedidoModelMock->expects($this->once())
            ->method('getById')
            ->with($id)
            ->willReturn(false);

        ob_start();
        $this->pedidoController->getById($id);
        $output = ob_get_clean();

        $response = json_decode($output, true);
        $this->assertEquals('Pedido não encontrado', $response['error']);
        $this->assertEquals(404, http_response_code());
    }

    public function testGetByIdWithError()
    {
        $id = 1;

        $this->pedidoModelMock->expects($this->once())
            ->method('getById')
            ->willThrowException(new Exception('Erro ao buscar pedido'));

        ob_start();
        $this->pedidoController->getById($id);
        $output = ob_get_clean();

        $response = json_decode($output, true);
        $this->assertEquals('Erro ao buscar pedido', $response['error']);
        $this->assertEquals(500, http_response_code());
    }

    public function testDeleteSuccess()
    {
        $id = 1;

        $this->pedidoModelMock->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(1);

        ob_start();
        $this->pedidoController->delete($id);
        $output = ob_get_clean();

        $response = json_decode($output, true);
        $this->assertTrue($response['success']);
        $this->assertEquals('Pedido deletado com sucesso', $response['message']);
    }

    public function testDeleteNotFound()
    {
        $id = 999;

        $this->pedidoModelMock->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(0);

        ob_start();
        $this->pedidoController->delete($id);
        $output = ob_get_clean();

        $response = json_decode($output, true);
        $this->assertEquals('Pedido não encontrado', $response['error']);
        $this->assertEquals(404, http_response_code());
    }

    public function testDeleteWithError()
    {
        $id = 1;

        $this->pedidoModelMock->expects($this->once())
            ->method('delete')
            ->willThrowException(new Exception('Erro ao deletar pedido'));

        ob_start();
        $this->pedidoController->delete($id);
        $output = ob_get_clean();

        $response = json_decode($output, true);
        $this->assertEquals('Erro ao deletar pedido', $response['error']);
        $this->assertEquals(500, http_response_code());
    }
}