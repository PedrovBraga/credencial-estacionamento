<?php

namespace sistema\Controlador;


use sistema\Modelo\Beneficiario;
use sistema\Nucleo\Controlador;

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
                'urlCancelar' => URL_DESENVOLVIMENTO.'/pessoa/cadastrar',
            ];
        } else {
            $json = ['status' => 1, 'mensagem' => 'Beneficiário encontrado!', 'beneficiario' => (array) $beneficiario];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);
    }

}
