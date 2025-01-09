<?php

use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\Helpers;

try {
    SimpleRouter::setDefaultNamespace('sistema\Controlador');

    
    SimpleRouter::get(URL_SITE, 'SiteControlador@index');

    // Login
    SimpleRouter::match(['get', 'post'], URL_SITE . 'login', 'LoginControlador@logar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'sair', 'LoginControlador@deslogar');

    // Pessoa
    SimpleRouter::match(['get', 'post'], URL_SITE . 'pessoa/cadastrar', 'PessoaControlador@cadastrar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'pessoa/editar', 'PessoaControlador@editar');
    SimpleRouter::post(URL_SITE . 'pessoa/consulta-doc', 'PessoaControlador@consultar');
    
    // Credencial
    SimpleRouter::match(['get', 'post'], URL_SITE . 'credencial/registrar/{id?}', 'CredencialControlador@registrar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'credencial/editar', 'CredencialControlador@editar');
    SimpleRouter::post(URL_SITE . 'credencial/consulta', 'CredencialControlador@consultar');
    SimpleRouter::get(URL_SITE . 'credencial/listar', 'CredencialControlador@listar');
    SimpleRouter::get(URL_SITE . 'credencial/visualizar', 'CredencialControlador@visualizar');

    // Auditoria
    SimpleRouter::match(['get', 'post'], URL_SITE . 'auditoria', 'AuditoriaControlador@consultar');

    SimpleRouter::start();
} catch (Pecee\SimpleRouter\Exceptions\NotFoundHttpException $ex) {
    echo "Capturando exceção";
    if (Helpers::localhost()) {
        echo $ex->getMessage();
    } else {
        Helpers::redirecionar('404');
    }
}
