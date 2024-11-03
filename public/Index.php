require_once '../src/Rotas/Roteador.php';
require_once '../src/Controllers/ProdutoController.php';
require_once '../src/Controllers/CarrinhoController.php';
require_once '../src/Controllers/ClienteController.php';
require_once '../src/Controllers/PedidoController.php';

$roteador = new Roteador();

// Rotas de Produtos
$roteador->obter('/', function() {
    $controlador = new ProdutoController();
    $controlador->index();
});

$roteador->obter('/produto/:id', function($id) {
    $controlador = new ProdutoController();
    $controlador->mostrar($id);
});

// Rotas do Carrinho
$roteador->obter('/carrinho', function() {
    $controlador = new CarrinhoController();
    $controlador->mostrar();
});

$roteador->enviar('/carrinho/adicionar', function() {
    $controlador = new CarrinhoController();
    $controlador->adicionar();
});

$roteador->enviar('/carrinho/remover', function() {
    $controlador = new CarrinhoController();
    $controlador->remover();
});

// Rotas do Cliente
$roteador->obter('/cliente/login', function() {
    $controlador = new ClienteController();
    $controlador->formLogin();
});

$roteador->enviar('/cliente/login', function() {
    $controlador = new ClienteController();
    $controlador->login();
});

$roteador->obter('/cliente/cadastro', function() {
    $controlador = new ClienteController();
    $controlador->formCadastro();
});

// Rotas de Pedido
$roteador->obter('/pedido/finalizar', function() {
    $controlador = new PedidoController();
    $controlador->finalizar();
});

$roteador->enviar('/pedido/confirmar', function() {
    $controlador = new PedidoController();
    $controlador->confirmar();
});

$roteador->executar();