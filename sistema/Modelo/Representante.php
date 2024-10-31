<?php

use sistema\Nucleo\QueryBuilder;

class Representante extends QueryBuilder {

    public function __construct() {
        $this->tabela = 'representante';
    }

}