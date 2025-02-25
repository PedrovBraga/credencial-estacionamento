<?php

namespace sistema\Modelo;

use Exception;
use sistema\Nucleo\QueryBuilder;

class Representante extends QueryBuilder {

    public function __construct() {
        $this->tabela = 'tb_representante';
    }

    public function buscaPorCPFouRG(string $doc)
    {
        if($doc === null){
            return null;
        }

        $busca = $this->busca("CPF = :d OR RG = :d", "d={$doc}");
        return $busca->resultado();
    }
    
    public function buscaPorNome(string $nome)
    {
        $busca = $this->busca("NOME LIKE :n", "n=%{$nome}%");
        return $busca->resultado();
    }

    public function gravar(){
        $representante_existente = new Representante();

        // Verifica se o representante já existe
        if(($representante_existente->buscaPorCPFouRG($this->RG) || $representante_existente->buscaPorCPFouRG($this->CPF)) && $representante_existente->buscaPorNome($this->NOME)){
            return; // Cancela a operação gravar mas não lança exception para manter o fluxo de registro do beneficiário
        }

        return parent::salvar();
    }

}