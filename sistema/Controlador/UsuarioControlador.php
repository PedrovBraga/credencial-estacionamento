<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Sessao;
use sistema\Modelo\Usuario;

class UsuarioControlador extends Controlador
{

    public function __construct()
    {
        parent::__construct('views');
    }

    public static function usuario(){
        $sessao = new Sessao();

        if(!$sessao->checar('usuarioId')){
            return null;
        }

        return (new Usuario())->buscaPorId($sessao->usuarioId);
    }


}
