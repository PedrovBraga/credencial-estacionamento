<?php

namespace sistema\Nucleo;

use Twig\Lexer;
use sistema\Nucleo\Helpers;

/**
 * Classe Template
 */
class Template
{

    private \Twig\Environment $twig;

    public function __construct(string $diretorio)
    {
        $loader = new \Twig\Loader\FilesystemLoader($diretorio);
        $this->twig = new \Twig\Environment($loader);
    
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }        

        $this->twig->addGlobal('URL_DESENVOLVIMENTO', URL_DESENVOLVIMENTO);
        $this->twig->addGlobal('_SESSION', $_SESSION);


        $lexer = new Lexer($this->twig, array(
            $this->helpers()
        ));
        $this->twig->setLexer($lexer);

    }

    /**
     * Metodo responsavel por realizar a renderização das views
     * @param string $view
     * @param array $dados
     * @return string
     */
    public function renderizar(string $view, array $dados)
    {
        try {
            return $this->twig->render($view, $dados);
        } catch (\Twig\Error\LoaderError | \Twig\Error\SyntaxError $ex) {

            echo 'Erro:: ' . $ex->getMessage();
        }
    }

    /**
     * Metodo responsavel por chamar funções da classe Helpers
     * @return void
     */
    private function helpers(): void
    {
        array(
            $this->twig->addFunction(
                    new \Twig\TwigFunction('url', function (string $url = null) {
                                echo $url;
                            })
            ),
            $this->twig->addFunction(
                    new \Twig\TwigFunction('saudacao', function () {
                                return Helpers::saudacao();
                            })
            ),
            $this->twig->addFunction(
                    new \Twig\TwigFunction('resumirTexto', function (string $texto, int $limite) {
                                return Helpers::resumirTexto($texto, $limite);
                            })
            ),
            // $this->twig->addFunction(
            //         new \Twig\TwigFunction('flash', function () {
            //                     return Helpers::flash();
            //                 })
            // ),
        );
    }

}
