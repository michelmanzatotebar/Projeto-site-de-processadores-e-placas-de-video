<?php
class Produto {
    private $id;
    private $nome;
    private $categoria;
    private $marca;
    private $especificacoes;
    private $preco;
    
    public function __construct($id, $nome, $categoria, $marca, $especificacoes, $preco) {
        $this->id = $id;
        $this->nome = $nome;
        $this->categoria = $categoria;
        $this->marca = $marca;
        $this->especificacoes = $especificacoes;
        $this->preco = $preco;
    }
    

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getCategoria() { return $this->categoria; }
    public function getMarca() { return $this->marca; }
    public function getEspecificacoes() { return $this->especificacoes; }
    public function getPreco() { return $this->preco; }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'categoria' => $this->categoria,
            'marca' => $this->marca,
            'especificacoes' => $this->especificacoes,
            'preco' => $this->preco
        ];
    }
}
