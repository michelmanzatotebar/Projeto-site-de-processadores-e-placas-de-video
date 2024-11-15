<?php
class PedidoController {
    private $pedidoModel;
    
    public function __construct($db) {
        $this->pedidoModel = new Pedido($db);
    }
    
    public function create() {
        try {
            $rawData = file_get_contents('php://input');
            error_log("Dados recebidos: " . $rawData);
            
            $data = json_decode($rawData, true);
            
            if (!$data) {
                error_log("Erro ao decodificar JSON: " . json_last_error_msg());
                http_response_code(400);
                echo json_encode(['error' => 'Dados inválidos']);
                return;
            }
            
            if (!isset($data['email_cliente']) || !isset($data['quantidade']) || !isset($data['preco_total'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Dados incompletos']);
                return;
            }
            
            $email_cliente = filter_var($data['email_cliente'], FILTER_SANITIZE_EMAIL);
            $quantidade = floatval($data['quantidade']);
            $preco_total = floatval($data['preco_total']);
            
            $result = $this->pedidoModel->create($email_cliente, $quantidade, $preco_total);
            
            if ($result) {
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Pedido criado com sucesso',
                    'id' => $result
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Erro ao criar pedido']);
            }
            
        } catch (Exception $e) {
            error_log("Erro no controller de pedido: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Erro interno do servidor']);
        }
    }
    public function list() {
        header('Content-Type: application/json');
        try {
            $pedidos = $this->pedidoModel->list();
            echo json_encode($pedidos);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao listar pedidos']);
        }
    }
    
    public function getById($id) {
        header('Content-Type: application/json');
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
    
    public function update($id) {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data || !isset($data['quantidade']) || !isset($data['preco_total'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Dados inválidos']);
                return;
            }
            
            $quantidade = floatval($data['quantidade']);
            $preco_total = floatval($data['preco_total']);
            
            if ($this->pedidoModel->update($id, $quantidade, $preco_total)) {
                echo json_encode(['success' => true, 'message' => 'Pedido atualizado']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Erro ao atualizar pedido']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao atualizar pedido']);
        }
    }
    
    public function delete($id) {
        header('Content-Type: application/json');
        try {
            if ($this->pedidoModel->delete($id)) {
                echo json_encode(['success' => true, 'message' => 'Pedido deletado']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Erro ao deletar pedido']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao deletar pedido']);
        }
    }
}