<?php

namespace sistema\Nucleo;


class Sessao {

    public function __construct() {
        if(!session_id()){
            session_start();
        }
    }

    public function __get($atributo)
    {
        if (!empty($_SESSION[$atributo])) {
            return $_SESSION[$atributo];
        }
    }


    public function criar(string $chave, mixed $valor): Sessao {
        $_SESSION[$chave] = (is_array($valor) ? (object) $valor : $valor);
        return $this;
    }

    public function limpar(string $chave){
        unset($_SESSION[$chave]);
        return $this;
    }

    public function carregar(): ?object{
        return (object) $_SESSION;
    }

    public function checar(string $chave){
        return isset($_SESSION[$chave]);
    }

    public function deletar(): Sessao {
        session_destroy();
        return $this;
    }

}
