<?php

namespace sistema\Nucleo;

use sistema\Controlador\UsuarioControlador;
use sistema\Nucleo\Template;
use sistema\Nucleo\Helpers;

/**
 * Classe Controlador, responsável por instanciar templates e mensagens para uso global
 *
 * @author Ronaldo Aires
 */
class Controlador
{
    protected Template $template;
    protected Mensagem $mensagem;

    protected $usuario;

    /**
     * Construtor responsável por definir o diretório pai das views e criar a instancia do engine template e mensagens.
     * @param string $diretorio
     */
    public function __construct(string $diretorio)
    {
        $this->template = new Template($diretorio);
        
        $this->mensagem = new Mensagem();

        $this->usuario = UsuarioControlador::usuario();

        // Recebe a url excluindo o domínio
        $urlAtual = $_SERVER['REQUEST_URI'];

        if(!$this->usuario && $urlAtual !== '/' . URL_SITE . 'login'){
            $this->mensagem->erro('Faça login para acessar o painel de controle!');

            Helpers::redirecionar('login');
        }
    }
}
