<?php

namespace sistema\Controlador;

use DateTime;
use Exception;
use sistema\Modelo\Auditoria;
use sistema\Modelo\Credencial;
use Sistema\Modelo\Usuario;
use sistema\Nucleo\Controlador;
use sistema\Modelo\Beneficiario;
use sistema\Modelo\Representante;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Sessao;

class CredencialControlador extends Controlador {
    public function __construct()
    {
        parent::__construct('views');
    }

    public function registrar(int $id = null) {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(isset($dados)){
            // ação registrar
            $beneficiario = (new Beneficiario())->buscaPorCPFOuRG($dados['cpf']);
            
            if($beneficiario->REPRESENTANTE !== '0'){
                $representante = (new Representante())->buscaPorId($beneficiario->REPRESENTANTE);
            }

            $credencial = new Credencial();
            $session = new Sessao();

            $credencial->TIPO = $dados['tipo_credencial'];
            $credencial->BENEFICIARIO = $beneficiario->ID;

            $dados_credencial = [
                'ANO' => date('Y'),
                'VALIDADE' => ($credencial->calcularValidade($beneficiario->SITUACAO_PNE))->format('Y-m-d'),
                'DTEMISSAO' => (new DateTime())->format('Y-m-d'),
                'SEGVIA' => 'N',
                'OBSSEGVIA' => '',
                'SEGVIACASSADA' => 'N',
                'OBSCASSADA' => '',
                'TIPORETIRADA' => 'PRAÇA DE ATENDIMENTO',
                'RETIRADA' => 'NAO',
                'BENEFICIARIO' => $id ?? $beneficiario->ID,
                'OPERADOR' => $session->usuarioId
            ];

            $credencial->preencher($dados_credencial);

            try {
                $credencial->gravar();
                $registro = $credencial->REGISTRO.'/'.$credencial->ANO;
                $json = ['status' => 1, 'mensagem' => 'Credencial registrada!', 'urlConfirmar' => URL_DESENVOLVIMENTO.'/credencial/visualizar?registro='.$registro];
            } catch(Exception $e){
                $json = ['status' => 0, 'mensagem' => $e->getMessage()];
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($json);
        } 
        // else {
        //     // form registrar (Deixou de ser utilizado???)
        //     $beneficiario = (new Beneficiario())->buscaPorCPFOuRG($id);
            
        //     if($beneficiario->REPRESENTANTE !== '0'){
        //         $representante = (new Representante())->buscaPorId($beneficiario->REPRESENTANTE);
        //     }

        //     $credencial = new Credencial();
        //     $session = new Sessao();

        //     $usuario = (new Usuario())->buscaPorId($session->usuarioId);

        //     echo $this->template->renderizar('credencial/formulario.html', [
        //         'credencial' => $credencial,
        //         'beneficiario' => $beneficiario ?? '',
        //         'representante' => $representante ?? '',
        //         'usuario' => $usuario ?? ''
        //     ]);
        // }
    }

    public function consultar() : void {

        $num_doc = filter_input(INPUT_POST, 'doc');

        if(isset($num_doc)){
            // Busca pelo documento (CPF ou RG) do beneficiário
            try {
                // Busca ID e infos do beneficiário
                $beneficiario = (new Beneficiario())->buscaPorCPFOuRG($num_doc);
    
                if(!isset($beneficiario)){
                    throw new Exception('Credencial não encontrada! Verifique o N° documento.');
                }

                // Busca todas as credencial existentes com ID do beneficiário
                $credenciais = (new Credencial())->buscarPorIdBeneficiario(true, $beneficiario->ID);
                
                if(!isset($credenciais)){
                    throw new Exception('Credencial não encontrada! Verifique o N° documento.');
                }
    
                $credenciaisArray = array_map(function($credencial) {
                    return (array) $credencial; // Convertendo cada credencial em um array
                }, $credenciais);
                $json = ['status' => 1, 'mensagem' => 'Credencial encontrada!', 'credencial' => $credenciaisArray, 'beneficiario' => (array) $beneficiario];
            } catch (Exception $e) {
                $json = ['status' => 0, 'mensagem' => 'Credencial não encontrada! Verifique o N° documento.'];
            }

            // if(!$credenciais){
            //     $json = ['status' => 0, 'mensagem' => 'Credencial não encontrada! Verifique o N° documento.'];
            // } else {
            //     $json = ['status' => 1, 'mensagem' => 'Credencial encontrada!', 'credencial' => $credenciaisArray, 'beneficiario' => (array) $beneficiario];
            // }
        } else {
            // busca por número (registro) da credencial
            $registro = filter_input(INPUT_POST, 'registro');

            
            try  {
                if(strpos($registro, '/') === false){
                    throw new Exception('Credencial não encontrada! Verifique o N° digitado (00000/0000).');
                }
                
                $partes = explode('/', $registro);
                $num_registro = $partes[0];
                $ano = $partes[1];

                // Busca credencial e informações do operador pelo número da credencial
                $credencial = (new Credencial())->buscarPorNumero($num_registro, $ano);

                if(!isset($credencial)){
                    throw new Exception('Credencial não encontrada! Verifique o N° digitado (00000/0000).');
                }

                // Busca inforamções do beneficiário pelo ID
                $beneficiario = (new Beneficiario())->buscaPorID($credencial->BENEFICIARIO);
                        
                if(!isset($beneficiario)){
                    throw new Exception('Credencial não encontrada! Verifique o N° digitado (00000/0000).');
                }
        
                $json = ['status' => 1, 'mensagem' => 'Credencial encontrada!', 'credencial' => (array) $credencial, 'beneficiario' => (array) $beneficiario];
            } catch(Exception $e){
                $json = ['status' => 0, 'mensagem' => $e->getMessage()];
            }
            
            // if(!isset($credencial)){
            //     $json = ['status' => 0, 'mensagem' => 'Credencial não encontrada! Verifique o N° documento ou cadastre a pessoa.'];
            // } else {
            //     $json = ['status' => 1, 'mensagem' => 'Credencial encontrada!', 'credencial' => (array) $credencial, 'beneficiario' => (array) $beneficiario];
            // }
        }
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);
        exit();
    }

    public function editar(){
        // Valores do formulário
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(isset($dados)){
            // PROCEDIMENTOS PARA EDITAR/ATUALIZAR A CREDENCIAL
            
            $credencial_atualizar = new Credencial();
            $credencial_atualizar->REGISTRO = $dados['registro'];
            $credencial_atualizar->ANO = $dados['ano'];
            $credencial_atualizar->SEGVIA = isset($dados['segVia']) ? 'S' : 'N';
            $credencial_atualizar->OBSSEGVIA = $dados['obsSegVia'];
            $credencial_atualizar->SEGVIACASSADA = isset($dados['segViaCassada']) ? 'S' : 'N';
            $credencial_atualizar->OBSCASSADA = $dados['obsCassada'];
            $credencial_atualizar->RETIRADA = 'PENDENTE';
            $credencial_atualizar->TIPO = $dados['tipo'];
            
            try {
                $credencial_atualizar->salvarCredencial();

                $registro = $dados['registro'].'/'.$dados['ano'];
                $json = ['status' => 1, 'mensagem' => 'Credencial atualizada!', 'urlConfirmar' => URL_DESENVOLVIMENTO.'/credencial/visualizar?registro='.$registro];
            } catch(Exception $e) {
                $json = ['status' => 0, 'mensagem' => $e->getMessage()];
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($json);
        } else {
            // PROCEDIMENTOS PARA EXIBIR A PÁGINA DE EDIÇÃO DE CREDENCIAL
            // Pega valor da url
            $registro = filter_input(INPUT_GET, 'registro');
            if(!empty($registro)){
                $partes = explode('/', $registro);
                $num_registro = $partes[0];
                $ano = $partes[1];
            }

            $credencial = (new Credencial())->buscarPorNumero($num_registro, $ano);
            
            if($credencial->RETIRADA === 'NAO' || $credencial->RETIRADA === 'PENDENTE'){
                $json = ['status' => 0, 'mensagem' => '2ª Via Indisponível! Crendencial ainda não foi retirada, clique em imprimir'];
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($json);
                return;
            }

            $beneficiario = (new Beneficiario())->buscaPorId($credencial->BENEFICIARIO);
    
            if($beneficiario->REPRESENTANTE !== '0'){
                $representante = (new Representante())->buscaPorId($beneficiario->REPRESENTANTE);
            }  
             
            $usuario = (new Usuario())->buscaPorId($credencial->OPERADOR);
    
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
    
    public function imprimir(){

        // Pega valor da url
        $registro = filter_input(INPUT_GET, 'registro');

        if(!empty($registro)){
            $partes = explode('/', $registro);
            $num_registro = $partes[0];
            $ano = $partes[1];
        }

        $credencial = (new Credencial())->buscaPorRegistro($num_registro, $ano);

        if($credencial->RETIRADA === 'SIM'){
            if($credencial->SEGVIA === 'S'){
                $json = ['status' => 0, 'mensagem' => 'Credencial e 2ª via impressas!'];
                echo json_encode($json);
                return;
            }

            $json = ['status' => 0, 'mensagem' => 'Credencial já impressa! Emita 2ª via, se necessário.'];
            echo json_encode($json);
            return;
        }
        
        $json = ['status' => 1];
        echo json_encode($json);

    }

    public function visualizar(){

        $auditoria = new Auditoria();

        // Pega valor da url
        $registro = filter_input(INPUT_GET, 'registro');

        if(!empty($registro)){
            $partes = explode('/', $registro);
            $num_registro = $partes[0];
            $ano = $partes[1];
        }

        $credencial = (new Credencial())->buscaPorRegistro($num_registro, $ano);
        $usuario_emissor = (new Usuario())->buscaPorId($credencial->OPERADOR);
        $beneficiario = (new Beneficiario())->buscaPorId($credencial->BENEFICIARIO);

        if($credencial->RETIRADA !== 'SIM'){
            $credencial->RETIRADA = 'SIM';
        }

        try{
            $credencial->salvarCredencial();
        } catch(Exception $e){
            echo 'Erro ao atualizar crendencial como retirada.';
            return;
        }

        $auditoria->gravar('IMPRESSÃO', $credencial);

        echo $this->template->renderizar('credencial/credencial.html', [
            'credencial' => $credencial,
            'emissor' => $usuario_emissor,
            'beneficiario' => $beneficiario
        ]);
        
    }
}