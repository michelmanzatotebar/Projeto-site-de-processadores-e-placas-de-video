<?php
class Cliente {
    private $id;
    private $nome;
    private $endereco;
    private $numero;
    private $email;
    
    public function __construct($id, $nome, $endereco, $numero, $email) {
        $this->id = $id;
        $this->nome = $nome;
        $this->endereco = $endereco;
        $this->numero = $numero;
        $this->email = $email;
    }
    
  
    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getEndereco() { return $this->endereco; }
    public function getNumero() { return $this->numero; }
    public function getEmail() { return $this->email; }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'endereco' => $this->endereco,
            'numero' => $this->numero,
            'email' => $this->email
        ];
    }
}
