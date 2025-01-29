<?php

namespace sistema\Controlador;

use sistema\Modelo\Beneficiario;
use sistema\Modelo\Representante;
use sistema\Nucleo\Controlador;
use sistema\Nucleo\Transacao;
use sistema\Nucleo\Helpers;

class PessoaControlador extends Controlador {

    public function __construct()
    {
        parent::__construct('views');
    }

    public function cadastrar() : void {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(isset($dados)){
            // ajustar os names para mandar ao BD e salvar
            $dados_separados = $this->separarDadosFormulario($dados, 'representante');

            $beneficiario = new Beneficiario();
            $beneficiario->preencher($dados_separados['outro']);

            // Confere check PNE
            if(isset($dados_separados['outro']['DEF'])){
                $representante = new Representante();
                $representante->preencher($dados_separados['representante']);
            }

            $transacao = new Transacao();

            try{
                $transacao->iniciar();

                if(isset($dados_separados['outro']['DEF'])){
                    $representante->salvar();
                    $beneficiario->gravar($representante);
                } else {
                    $beneficiario->gravar();
                }

                $transacao->confirmar();

            } catch (\PDOException $e){
                // Se erro, faz o rollback da transação
                $transacao->desfazer();

                // Exibe a mensagem de erro
                $this->mensagem->erro('Erro ao salvar os dados: ' . $e->getMessage());
                exit;
            }

            // Redirecionar para Carteirinha
            // echo $this->template->renderizar('credencial/formulario.html', [
            //     'beneficiario' => $beneficiario,
            //     'representante' => $representante
            // ]);

            $url = 'credencial/registrar/'.$beneficiario->ID;
            Helpers::redirecionar($url);
        } else {
            echo $this->template->renderizar('pessoa/formulario.html', []);
        }

    }

    public function buscar() : void {
        echo "teste busca";
    }

    public function consultar() : void {

        $num_doc = filter_input(INPUT_POST, 'doc');
        
        $beneficiario = (new Beneficiario())->buscaPorCPFOuRG($num_doc);
        if(!$beneficiario){
            $json = [
                'status' => 0, 
                'mensagem' => 'Munícipe não encontrado! Verifique o N° do documento digitado ou registre o munícipe.',
                'urlCancelar' => URL_DESENVOLVIMENTO.'/pessoa/cadastrar',
            ];
        } else {
            $json = ['status' => 1, 'mensagem' => 'Beneficiário encontrado!', 'beneficiario' => (array) $beneficiario];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);
    }

    // Substring procurada no name recebido do form é utilizada como chave para um dos arrays de separação
    public function separarDadosFormulario(array $dados, string $substring): array
    {
        $separados = [
            $substring => [],
            'outro' => []
        ];

        foreach ($dados as $campo => $valor) {
            $campo_uppercase = mb_strtoupper($campo, 'UTF-8');
            $valor_uppercase = mb_strtoupper($valor, 'UTF-8');
 
            if (strpos($campo, $substring) !== false) {
                $tam_susbstring = mb_strlen($substring, 'UTF-8');
                
                // torna valor negativo para remover substring através de substr() e contar o tamanho de trás pra frente
                // - 1 é referente ao '_' que vem na $string mas não utilizamos na $substring
                $tam_susbstr = - $tam_susbstring - 1;
                
                // remove '_$substring' do fim da string
                $campo_upper_tratado = substr($campo_uppercase, 0, $tam_susbstr);

                $separados[$substring][$campo_upper_tratado] = $valor_uppercase;
            } else {
                $separados['outro'][$campo_uppercase] = $valor_uppercase;
            }
        }

        return $separados;
    }

}
