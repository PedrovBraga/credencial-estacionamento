<?php

namespace sistema\Controlador;

use DateTime;
use Exception;
use sistema\Modelo\Beneficiario;
use sistema\Modelo\Credencial;
use sistema\Modelo\Representante;
use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Sessao;
use sistema\Nucleo\Transacao;

class SiteControlador extends Controlador
{

    public function __construct()
    {
        parent::__construct('views');
    }

    /**
     * Home Page
     * @return void
     */
    public function index(): void
    {
        // echo $this->template->renderizar('pessoa/pessoa.html', []);
    }

    public function cadastrar() : void {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(isset($dados)){
            // ajustar os names para mandar ao BD e salvar
            $dados_separados = Helpers::separarDadosFormulario($dados, 'representante', 'credencial');

            $beneficiario = new Beneficiario();
            $beneficiario->preencher($dados_separados['outro']);

            // Confere check PNE
            if(isset($dados_separados['outro']['DEF'])){
                $representante = new Representante();
                $representante->preencher($dados_separados['representante']);
            }

            $credencial = new Credencial();
            $session = new Sessao();

            $dados_credencial = [
                'ANO' => date('Y'),
                'VALIDADE' => ($credencial->calcularValidade($beneficiario->SITUACAO_PNE))->format('Y-m-d'),
                'DTEMISSAO' => (new DateTime())->format('Y-m-d'),
                'TIPO' => $dados_separados['credencial']['TIPO'],
                'SEGVIA' => 'N',
                'OBSSEGVIA' => '',
                'SEGVIACASSADA' => 'N',
                'OBSCASSADA' => '',
                'TIPORETIRADA' => 'PRAÇA DE ATENDIMENTO',
                'PROTOCOLOGPRO' => $dados_separados['credencial']['PROTOCOLOGPRO'],
                // 'RETIRADA' => 'NAO',
                'OPERADOR' => $session->usuarioId,
                'BENEFICIARIO' => $id ?? '',
            ];

            $credencial->preencher($dados_credencial);

            $transacao = new Transacao();

            try{
                $transacao->iniciar();
                
                if(isset($dados_separados['outro']['DEF'])){
                    $representante->salvar();
                    $beneficiario->gravar($representante);
                    $credencial->BENEFICIARIO = $beneficiario->ID;
                    $credencial->gravar();
                } else {
                    $beneficiario->gravar();
                    $credencial->BENEFICIARIO = $beneficiario->ID;
                    $credencial->gravar();
                }

                $transacao->confirmar();

                $num_registro = $credencial->REGISTRO.'/'.$credencial->ANO;
                
                $json = ['status' => 1, 'mensagem' => 'Credencial registrada. Deseja imprimi-la?', 'urlConfirmar' => URL_DESENVOLVIMENTO.'/credencial/visualizar?registro='.$num_registro];
            } catch (Exception $e){
                // Se erro, faz o rollback da transação
                $transacao->desfazer();

                $json = ['status' => 0, 'mensagem' => 'Erro ao cadastrar. '.$e->getMessage()];
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($json);
        } else {
            echo $this->template->renderizar('cadastro/formulario.html', [
                'URL_DESENVOLVIMENTO' => URL_DESENVOLVIMENTO,
            ]);
        }

    }
    
    /**
     * ERRO 404
     * @return void
     */
    public function erro404(): void
    {
        echo $this->template->renderizar('404.html', [
            'titulo' => 'Página não encontrada'
        ]);
    }

}
