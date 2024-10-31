<?php

use sistema\Nucleo\QueryBuilder;

class Credencial extends QueryBuilder {

    public function __construct() {
        $this->tabela = 'credencial';
    }

}