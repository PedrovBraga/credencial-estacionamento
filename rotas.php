<?php

use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\Helpers;

try {
    SimpleRouter::setDefaultNamespace('sistema\Controlador');

    SimpleRouter::get(URL_SITE, 'SiteControlador@index');
    SimpleRouter::get(URL_SITE . 'busca', 'SiteControlador@buscar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'pessoa/editar', 'PessoaControlador@editar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'pessoa/consulta-cpf/{cpf}', 'PessoaControlador@consulta');
    
    SimpleRouter::start();
} catch (Pecee\SimpleRouter\Exceptions\NotFoundHttpException $ex) {
    echo "Capturando exceção";
    if (Helpers::localhost()) {
        echo $ex->getMessage();
    } else {
        Helpers::redirecionar('404');
    }
}
