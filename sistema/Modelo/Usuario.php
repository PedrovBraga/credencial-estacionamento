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
    
    public function buscaPorMatricula(string $matricula): ?Usuario {
        $busca = $this->busca("MATRICULA = '{$matricula}'");
        return $busca->resultado();
    }

    public function registrar(): bool {
        $usuario = (new Usuario())->buscaPorUsuario($this->USUARIO);
        if($usuario){
            throw new Exception("Usuário já existe! Escolha outro nome de usuário.");
        }

        $usuario_matricula = (new Usuario())->buscaPorMatricula($this->MATRICULA);
        if($usuario_matricula){
            throw new Exception("Matrícula já existe!");
        }

        $this->SENHA = $this->criptografaSenha(1234);
        $this->ATIVO = 1;
        
        return parent::salvar();
    }

    public function listar(){
        $busca = $this->busca('ATIVO = 1', null, "NOME, USUARIO, EMAIL, CARGO, ATIVO");
        return $busca->resultado(true);
    }

    public function login(array $dados){
        $usuario = (new Usuario())->buscaPorUsuario($dados['usuario']);

        if(!$usuario){
            throw new Exception("Dados inválidos! Verifique o usuário e a senha digitada.");
        }

        if($this->criptografaSenha($dados['senha']) != $usuario->SENHA){
            throw new Exception("Dados inválidos! Verifique o usuário e a senha digitada.");
        }

        if($usuario->ATIVO !== 1){
            throw new Exception("Dados inválidos! Usuário informado está inativo.");
        }

        $session = new Sessao();
        $session->criar('usuarioId', $usuario->ID);
        $session->criar('nivel', $usuario->COD_PERFIL);

        // var_dump($session);
        return true;
    }

    function criptografaSenha(string $senha): float {
        return $this->criptografar($senha); // Retorna o valor final
    }
    
    private function criptografar(string $senha): float {
        $R = 0;
        $C = $senha;
        
        // Loop através de cada caractere da senha
        for ($i = 1; $i <= strlen($C); $i++) {
            $R += ($i + 1) * ord($C[$i-1]); // ord() retorna o valor ASCII do caractere
        }
        
        return $R; // Retorna o valor final
    }

}