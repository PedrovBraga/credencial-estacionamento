<?php

use sistema\Nucleo\QueryBuilder;

class Usuario extends QueryBuilder {

    public function __construct() {
        $this->tabela = 'usuario';
    }

}