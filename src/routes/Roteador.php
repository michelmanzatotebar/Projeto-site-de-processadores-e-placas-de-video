class Roteador {
    private $rotas = [];
    
    public function obter($caminho, $funcao) {
        $this->rotas['GET'][$caminho] = $funcao;
    }
    
    public function enviar($caminho, $funcao) {
        $this->rotas['POST'][$caminho] = $funcao;
    }
    
    public function executar() {
        $metodo = $_SERVER['REQUEST_METHOD'];
        $caminho = $_SERVER['PATH_INFO'] ?? '/';
        
        $funcao = $this->rotas[$metodo][$caminho] ?? false;
        
        if(!$funcao) {
            header("HTTP/1.0 404 Not Found");
            require_once '../src/Views/404.php';
            return;
        }
        
        if(is_callable($funcao)) {
            call_user_func($funcao);
        }
    }
}