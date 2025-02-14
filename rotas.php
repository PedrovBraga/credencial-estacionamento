<?php

use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\Helpers;

try {
    SimpleRouter::setDefaultNamespace('sistema\Controlador');

    
    SimpleRouter::get(URL_SITE, 'SiteControlador@index');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'cadastrar', 'SiteControlador@cadastrar');

    // Login
    SimpleRouter::match(['get', 'post'], URL_SITE . 'login', 'LoginControlador@logar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'sair', 'LoginControlador@deslogar');

    // Pessoa
    SimpleRouter::match(['get', 'post'], URL_SITE . 'pessoa/editar', 'PessoaControlador@editar');
    SimpleRouter::post(URL_SITE . 'pessoa/consulta-doc', 'PessoaControlador@consultar');
    
    // Credencial
    SimpleRouter::match(['get', 'post'], URL_SITE . 'credencial/registrar/{id?}', 'CredencialControlador@registrar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'credencial/editar', 'CredencialControlador@editar');
    SimpleRouter::post(URL_SITE . 'credencial/consulta', 'CredencialControlador@consultar');
    SimpleRouter::get(URL_SITE . 'credencial/listar', 'CredencialControlador@listar');
    SimpleRouter::get(URL_SITE . 'credencial/visualizar', 'CredencialControlador@visualizar');
    SimpleRouter::get(URL_SITE . 'credencial/imprimir', 'CredencialControlador@imprimir');

    // Auditoria
    SimpleRouter::match(['get', 'post'], URL_SITE . 'auditoria', 'AuditoriaControlador@consultar');
   
    // Usuário
    SimpleRouter::get(URL_SITE . 'usuario/listar', 'UsuarioControlador@listar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'usuario/editar', 'UsuarioControlador@editar');
    SimpleRouter::match(['get', 'post'], URL_SITE . 'usuario/cadastrar', 'UsuarioControlador@cadastrar');
    SimpleRouter::post(URL_SITE . 'usuario/desativar', 'UsuarioControlador@desativar');

    SimpleRouter::start();
} catch (Pecee\SimpleRouter\Exceptions\NotFoundHttpException $ex) {
    echo "Capturando exceção";
    if (Helpers::localhost()) {
        echo $ex->getMessage();
    } else {
        Helpers::redirecionar('404');
    }
}
