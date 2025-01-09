<?php

namespace sistema\Modelo;
use sistema\Nucleo\QueryBuilder;

class Representante extends QueryBuilder {

    public function __construct() {
        $this->tabela = 'tb_representante';
    }

    public function buscaPorRG(string $rg)
    {
        $busca = $this->busca( "RG = '{$rg}'", null, 'ID');
        return $busca->resultado();
    }

}