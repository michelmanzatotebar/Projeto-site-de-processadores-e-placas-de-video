<?php
require_once __DIR__ . '/../models/Pedido.php';

class PedidoController {
    private $pedidoModel;
    
    public function __construct($db) {
        $this->pedidoModel = new Pedido($db);
    }
    
    public function create() {
        $data = json_decode(file_get_contents("php://input"));
        
        if(isset($data->email_cliente) && isset($data->quantidade) && isset($data->preco_total)){
            try {
                error_log("Dados recebidos no PedidoController: " . $data); // Log para debug
                http_response_code(201);
                $this->pedidoModel->create($data->email_cliente,$data->quantidade,$data->preco_total);
                echo json_encode(['message' => 'Pedido criado com sucesso.']);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(["message" => "Erro ao criar o pedido (controller).", "error" => $e->getMessage()]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Dados incompletos."]);
        }
    
    
    }
    
    public function list() {
        try {
            $pedidos = $this->pedidoModel->list();
            echo json_encode($pedidos);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao listar pedidos']);
        }
    }
    
    public function getById($id) {
        try {
            $pedido = $this->pedidoModel->getById($id);
            
            if ($pedido) {
                echo json_encode($pedido);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Pedido não encontrado']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao buscar pedido']);
        }
    }
    
    public function delete($id) {
        try {
            if ($this->pedidoModel->delete($id)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Pedido deletado com sucesso'
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Pedido não encontrado']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao deletar pedido']);
        }
    }
}