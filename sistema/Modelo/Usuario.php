<?php

namespace Sistema\Modelo;

use sistema\Nucleo\QueryBuilder;
use sistema\Nucleo\Sessao;

use Exception;

class Usuario extends QueryBuilder {

    public function __construct() {
        $this->tabela = 'tb_usuario';
    }

    public function buscaPorEmail(string $email): ?Usuario {
        $busca = $this->busca("email =: e", "e={$email}");
        return $busca->resultado();
    }
    
    public function buscaPorUsuario(string $usuario): ?Usuario {
        $busca = $this->busca("USUARIO = '{$usuario}'");
        return $busca->resultado();
    }

    public function login(array $dados){
        $usuario = (new Usuario())->buscaPorUsuario($dados['usuario']);

        if(!$usuario){
            throw new Exception("Dados inválidos! Verifique o usuário e a senha digitada.");
        }

        if($dados['senha'] !== $usuario->SENHA){
            throw new Exception("Dados inválidos! Verifique o usuário e a senha digitada.");
        }

        if($usuario->ATIVO !== 1){
            throw new Exception("Dados inválidos! Usuário informado está inativo.");
        }

        $session = new Sessao();
        $session->criar('usuarioId', $usuario->ID);

        var_dump($session);
        return true;
    }

}