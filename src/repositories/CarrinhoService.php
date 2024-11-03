<?php
class CarrinhoService {
    private $carrinhoRepository;
    private $produtoRepository;
    
    public function __construct(CarrinhoRepository $carrinhoRepository, ProdutoRepository $produtoRepository) {
        $this->carrinhoRepository = $carrinhoRepository;
        $this->produtoRepository = $produtoRepository;
    }
    
    public function adicionarProduto($email_cliente, $id_produto, $quantidade) {
        $produto = $this->produtoRepository->findById($id_produto);
        if (!$produto) {
            throw new Exception("Produto não encontrado");
        }
        
        $item = new Carrinho(
            null,
            $email_cliente,
            $id_produto,
            $quantidade,
            $produto->getPreco()
        );
        
        return $this->carrinhoRepository->addItem($item);
    }
    
    public function atualizarQuantidade($id, $quantidade) {
        return $this->carrinhoRepository->updateQuantidade($id, $quantidade);
    }
    
    public function removerItem($id) {
        return $this->carrinhoRepository->removeItem($id);
    }
    
    public function getCarrinho($email_cliente) {
        return $this->carrinhoRepository->findByClienteEmail($email_cliente);
    }
}