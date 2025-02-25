<?php
namespace Sistema\Controlador;

use sistema\Modelo\Auditoria;
use Sistema\Modelo\Usuario;
use sistema\Nucleo\Controlador;

class AuditoriaControlador extends Controlador {
    public function __construct() {
        parent::__construct('views/');
    }
    
    public function consultar(): void {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $usuarios = (new Usuario())->listar();

        echo $this->template->renderizar('auditoria/auditoria.html', [
            'usuarios' => $usuarios
        ]);
    }
    
    public function consultarMunicipe(): void {
        
        $num_doc = filter_input(INPUT_GET, 'doc');

        $auditoria_registros = (new Auditoria())->buscaPorCPFOuRG($num_doc); 

        // var_dump($auditoria_registros);

        $auditoriasArray = array_map(function($auditoria) {
            return (array) $auditoria; // Convertendo cada credencial em um array
        }, $auditoria_registros);

        $json = ['status' => 1, 'mensagem' => 'Beneficiário encontrado!', 'auditorias' => (array) $auditoriasArray];
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);
    }
    
    public function consultarOperador(): void {
        $operador = filter_input(INPUT_GET, 'operador');

        $auditoria_registros = (new Auditoria())->buscaPorOperador($operador);

        $auditoriaArray = array_map(function($auditoria) {
            return (array) $auditoria; // Convertendo cada credencial em um array
        }, $auditoria_registros);


        $json = ['status' => 1, 'mensagem' => 'Operador encontrado!', 'auditorias' => $auditoriaArray];
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);
    }
}

