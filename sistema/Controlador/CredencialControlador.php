<?php

namespace sistema\Controlador;

use DateTime;
use Exception;
use sistema\Modelo\Credencial;
use Sistema\Modelo\Usuario;
use sistema\Nucleo\Controlador;
use sistema\Modelo\Beneficiario;
use sistema\Modelo\Representante;
use sistema\Nucleo\Alerta;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Sessao;

class CredencialControlador extends Controlador {
    public function __construct()
    {
        parent::__construct('views/');
    }

    // PENSAR NA LOGICA DE ID NO FORMULARIO, QND CONSULTAR DESAPARECER E APARECER ID
    // E TBM, CADASTRAR NOVA COM PESSOA JÁ CADASTRADA

    public function registrar(int $id = null) {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if($id){
            $beneficiario = (new Beneficiario())->buscaPorId($id);
    
            if($beneficiario->REPRESENTANTE !== '0'){
                $representante = (new Representante())->buscaPorId($beneficiario->REPRESENTANTE);
            }            
        }

        $credencial = new Credencial();
        $session = new Sessao();

        $dados_credencial = [
            'ANO' => date('Y'),
            'VALIDADE' => ($credencial->calcularValidade())->format('Y-m-d'),
            'DTEMISSAO' => (new DateTime())->format('Y-m-d'),
            'SEGVIA' => 'N',
            'OBSSEGVIA' => '',
            'SEGVIACASSADA' => 'N',
            'OBSCASSADA' => '',
            'TIPORETIRADA' => '',
            'RETIRADA' => 'NAO',
            'BENEFICIARIO' => $id ?? '',
            'OPERADOR' => $session->usuarioId
        ];

        $credencial->preencher($dados_credencial);

        if(isset($dados)){
            // registrar
            $credencial->TIPO = $dados['tipo'];
            $credencial->TIPORETIRADA = $dados['tipoRetirada'];
            $credencial->BENEFICIARIO = ((new Beneficiario())->buscaPorCPFOuRG($dados['doc']))->ID;

            try {
                $credencial->gravar();
                $json = ['status' => 1, 'mensagem' => 'Credencial registrada. Deseja imprimi-la?', 'urlConfirmar' => URL_DESENVOLVIMENTO.'/credencial/visualizar'];
            } catch(Exception $e){
                $json = ['status' => 0, 'mensagem' => $e->getMessage()];
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($json);
        } else {
            $usuario = (new Usuario())->buscaPorId($session->usuarioId);

            echo $this->template->renderizar('credencial/formulario.html', [
                'credencial' => $credencial,
                'beneficiario' => $beneficiario ?? '',
                'representante' => $representante ?? '',
                'usuario' => $usuario ?? ''
            ]);
        }
    }

    public function consultar() : void {

        $num_doc = filter_input(INPUT_POST, 'doc');

        if(isset($num_doc)){
            // Busca ID e infos do beneficiário
            $beneficiario = (new Beneficiario())->buscaPorCPFOuRG($num_doc);
            // Busca todas as credencial existentes com ID do beneficiário
            $credenciais = (new Credencial())->buscarPorIdBeneficiario(true, $beneficiario->ID);

            // echo var_dump($credenciais);

            $credenciaisArray = array_map(function($credencial) {
                return (array) $credencial; // Convertendo cada credencial em um array
            }, $credenciais);

            if(!$credenciais){
                $json = ['status' => 0, 'mensagem' => 'Credencial não encontrada! Verifique o N° documento ou cadastre a pessoa.'];
            } else {
                $json = ['status' => 1, 'mensagem' => 'Credencial encontrada!', 'credencial' => $credenciaisArray, 'beneficiario' => (array) $beneficiario];
            }
        } else {
            $registro = filter_input(INPUT_POST, 'registro');

            $partes = explode('/', $registro);
            $num_registro = $partes[0];
            $ano = $partes[1];
            // Busca credencial e informações do operador pelo número da credencial
            $credencial = (new Credencial())->buscarPorNumero($num_registro, $ano);
            // Busca inforamções do beneficiário pelo ID
            $beneficiario = (new Beneficiario())->buscaPorID($credencial->BENEFICIARIO);
            
            if(!$credencial){
                $json = ['status' => 0, 'mensagem' => 'Credencial não encontrada! Verifique o N° documento ou cadastre a pessoa.'];
            } else {
                $json = ['status' => 1, 'mensagem' => 'Credencial encontrada!', 'credencial' => (array) $credencial, 'beneficiario' => (array) $beneficiario];
            }
        }
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);
        exit();
    }

    public function editar(){
        // Valores do formulário
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        // Pega valor da url
        $registro = filter_input(INPUT_GET, 'registro');
        if(!empty($registro)){
            $partes = explode('/', $registro);
            $num_registro = $partes[0];
            $ano = $partes[1];
        }

        if(isset($dados)){
            $credencial_atualizar = new Credencial();
            $credencial_atualizar->REGISTRO = $dados['registro'];
            $credencial_atualizar->ANO = $dados['ano'];
            $credencial_atualizar->TIPORETIRADA = $dados['tipoRetirada'];
            $credencial_atualizar->RETIRADA = $dados['retirada'];

            try {
                $credencial_atualizar->salvarCredencial();
                $json = ['status' => 1, 'mensagem' => 'Credencial atualizada!', 'urlConfirmar' => URL_DESENVOLVIMENTO.'/credencial/listar'];
            } catch(Exception $e) {
                $json = ['status' => 0, 'mensagem' => $e->getMessage()];
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($json);
            exit();
        } else {

            $credencial = (new Credencial())->buscarPorNumero($num_registro, $ano);
            $beneficiario = (new Beneficiario())->buscaPorId($credencial->BENEFICIARIO);
    
            if($beneficiario->REPRESENTANTE !== '0'){
                $representante = (new Representante())->buscaPorId($beneficiario->REPRESENTANTE);
            }  
             
            $session = new Sessao();
            $usuario = (new Usuario())->buscaPorId($session->usuarioId);
    
            $credencial->VALIDADE = Helpers::trataFormatoData($credencial->VALIDADE);
            $credencial->DTEMISSAO = Helpers::trataFormatoData($credencial->DTEMISSAO);
    
            echo $this->template->renderizar('credencial/formulario.html', [
                    'credencial' => $credencial,
                    'beneficiario' => $beneficiario ?? '',
                    'representante' => $representante ?? '',
                    'usuario' => $usuario ?? ''
                ]);
        }

    }

    public function listar(){
        echo $this->template->renderizar('credencial/lista.html', []);
    }

    public function visualizar(){
        echo $this->template->renderizar('credencial/credencial.html', []);
    }
}