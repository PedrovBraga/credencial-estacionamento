<?php

namespace sistema\Controlador;

use DateTime;
use sistema\Nucleo\Sessao;
use sistema\Modelo\Usuario;
use sistema\Nucleo\Controlador;
use Exception;

class UsuarioControlador extends Controlador {

    public function __construct()
    {
        parent::__construct('views');
    }

    public static function usuario(){
        $sessao = new Sessao();

        if(!$sessao->checar('usuarioId')){
            return null;
        }

        return (new Usuario())->buscaPorId($sessao->usuarioId);
    }

    public function listar(){

        $usuario = new Usuario();

        $usuarios = $usuario->listar();
        // echo var_dump($usuarios);
        echo $this->template->renderizar('usuario/lista.html', [
            'usuarios' => $usuarios
        ]);
    }

    public function editar(){
        // Valores do formulário para atualizar
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(isset($dados)){
            $usuario = (new Usuario())->buscaPorUsuario($dados['usuario']);
            $usuario->DATA_ATUALIZACAO = (new DateTime())->format('Y-m-d H:i:s');
            
            // Caso esteja alterando a senha
            if(isset($dados['senha'])){
                $dados['senha'] = $usuario->criptografaSenha($dados['senha']);
                $urlSenhaAlterada = URL_DESENVOLVIMENTO . '/cadastrar';
            }

            try {
                $usuario->preencher($dados);
                // echo print_r($usuario);
                $usuario->salvar();

                $json = ['status' => 1, 'mensagem' => 'Usuário atualizado!', 'urlConfirmar' => $urlSenhaAlterada ?? URL_DESENVOLVIMENTO . '/usuario/listar'];
            } catch (Exception $e) {
                $json = ['status' => 0, 'mensagem' => 'Falha ao atualizar usuário!'];
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($json);
            
        } else {
            // Pega usuário pelo valor passado na url
            $user = filter_input(INPUT_GET, 'usuario', FILTER_DEFAULT);
            $usuario = (new Usuario())->buscaPorUsuario($user);

            echo $this->template->renderizar('usuario/formulario.html', [
                'usuario' => $usuario
            ]);
        }
    }

    public function desativar(){
        $user = filter_input(INPUT_POST, 'usuario', FILTER_DEFAULT);

        $usuario = (new Usuario())->buscaPorUsuario($user);
        // echo print_r($usuario);

        $usuario->ATIVO = 0;
        // $usuario->id = $usuario->ID;

        try {
            $usuario->salvar();
            $json = ['status' => 1, 'mensagem' => 'Usuário desativado!', 'urlConfirmar' => URL_DESENVOLVIMENTO . '/usuario/listar'];
        } catch(Exception $e){
            $json = ['status' => 0, 'mensagem' => 'Falha ao desativar usuário!'];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);

    }

    public function cadastrar(){
        // Valores do formulário
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(isset($dados)){
            $usuario = new Usuario();
            $usuario->preencher($dados);
            
            try {
                // echo print_r($usuario);
                $usuario->registrar();
                $json = ['status' => 1, 'mensagem' => 'Usuário cadastrado!', 'urlConfirmar' => URL_DESENVOLVIMENTO . '/usuario/listar'];
            } catch (Exception $e) {
                $json = ['status' => 0, 'mensagem' => $e->getMessage()];
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($json);
        } else {
            echo $this->template->renderizar('usuario/formulario.html', []);
        }
    }

    public function alterarSenha(){
        // Valores do formulário
        $user = filter_input(INPUT_GET, 'usuario');
        
        $usuario = (new Usuario())->buscaPorUsuario($user);
        // print_r($usuario);
        echo $this->template->renderizar('login/login.html', [
            'alterarSenha' => true,
            'usuario' => $usuario
        ]);
    }


}
