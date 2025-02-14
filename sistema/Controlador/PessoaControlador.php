<?php

namespace sistema\Controlador;

use DateTime;
use Exception;
use sistema\Modelo\Beneficiario;
use sistema\Modelo\Representante;
use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;

class PessoaControlador extends Controlador {

    public function __construct()
    {
        parent::__construct('views');
    }

    public function buscar() : void {
        echo "teste busca";
    }

    public function consultar() : void {

        $num_doc = filter_input(INPUT_POST, 'doc');
        
        $beneficiario = (new Beneficiario())->buscaPorCPFOuRG($num_doc);
        if(!$beneficiario){
            $json = [
                'status' => 0, 
                'mensagem' => 'Munícipe não encontrado! Verifique o N° do documento digitado ou registre o munícipe.',
                'urlCancelar' => URL_DESENVOLVIMENTO.'/cadastrar',
            ];
        } else {
            $json = ['status' => 1, 'mensagem' => 'Beneficiário encontrado!', 'beneficiario' => (array) $beneficiario];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);
    }

    public function editar(){
        // Valores do formulário para atualizar
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(isset($dados)){
            // ajustar os names para mandar ao BD e salvar
            $dados_separados = Helpers::separarDadosFormulario($dados, 'representante', 'null');

            $beneficiario = (new Beneficiario())->buscaPorCPFOuRG($dados['cpf'], false);
            $beneficiario->preencher($dados_separados['outro']);

            $beneficiario->ATUALIZADO_EM = (new DateTime())->format('Y-m-d H:i:s');

            $representante = new Representante();
            // Confere check PNE (se deficiente)
            if(isset($dados_separados['outro']['DEF'])){
                $representante->preencher($dados_separados['representante']);
            }

            try {
                if(isset($dados_separados['outro']['DEF'])){
                    $representante->gravar();
                    // echo print_r($representante);
                    $beneficiario->gravar($representante);
                } else {
                    $beneficiario->gravar();
                }

                $json = ['status' => 1, 'mensagem' => 'Usuário atualizado!', 'urlConfirmar' => URL_DESENVOLVIMENTO . '/pessoa/editar'];
            } catch (Exception $e) {
                $json = ['status' => 0, 'mensagem' => 'Falha ao atualizar usuário! '.$e->getMessage()];
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($json);
            
        } else {
            // Renderiza tela editar
            echo $this->template->renderizar('pessoa/formulario.html', []);
        }
    }

}
