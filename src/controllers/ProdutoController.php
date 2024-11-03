class ProdutoController {
    private $produtoModel;

    public function __construct() {
        $this->produtoModel = new Produto();
    }

    public function index() {
        $produtos = $this->produtoModel->buscarTodos();
        require_once 'Views/produtos/listar.php';
    }

    public function mostrar($id) {
        $produto = $this->produtoModel->buscarPorId($id);
        require_once 'Views/produtos/detalhes.php';
    }

    public function buscarPorCategoria($categoria) {
        $produtos = $this->produtoModel->buscarPorCategoria($categoria);
        require_once 'Views/produtos/listar.php';
    }
}