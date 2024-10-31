<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;

class PessoaControlador extends Controlador {

    public function __construct()
    {
        parent::__construct('views');
    }

    public function cadastrar() : void {
        echo "teste";
    }

    public function buscar() : void {
        // $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // if(isset($dados)) {
        //     // Quando consulta é feita para verificar se já existe
        // } else {
        //     // Quando vai ser feito um novo cadastro
        // }

        echo "teste busca";
    }

    public function consultar($cpf) : void {
        // $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // $beneficiario = (new Beneficiario())->busca("cpf = :cpf", "cpf={$cpf}")->resultado();
        // if(!$beneficiario){
        //     // $this->mensagem->erro
        // }

        // echo $this->template->renderizar('pessoa.html', [
        //     'beneficiario' => $beneficiario,
            
        // ]);

        echo "teste consulta";
    }

}