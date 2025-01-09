<?php
namespace Sistema\Controlador;

use sistema\Nucleo\Controlador;

class AuditoriaControlador extends Controlador {
    public function __construct() {
        parent::__construct('views/');
    }
    
    public function consultar(): void {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        if(isset($dados)){
            // Consulta registros do funcionário e envia dados pra view
        } 

        echo $this->template->renderizar('auditoria/auditoria.html', []);
    }
}

