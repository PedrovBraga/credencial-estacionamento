<?php

namespace Sistema\Modelo;

use DateInterval;
use DateTime;
use Exception;
use sistema\Nucleo\QueryBuilder;

class Credencial extends QueryBuilder {

    public function __construct() {
        $this->tabela = 'tb_credencial';
    }

    public function gravar(){

        $beneficiario = (new Beneficiario())->buscaPorId($this->BENEFICIARIO); 
        $credencial_verificacao = new Credencial();

        if($beneficiario === null) {
            throw new Exception('Erro ao registrar credencial! Nenhuma pessoa vinculada a credencial.');
        }

        if($this->TIPO === 'IDOSO'){
            if($beneficiario && $beneficiario->calculaIdade() >= 60){
                $ultima_credencial_idoso = $credencial_verificacao->buscarPorIdBeneficiario(false, $this->BENEFICIARIO, $this->TIPO) ?? null;

                if($ultima_credencial_idoso && !$ultima_credencial_idoso->verificaVencida()){
                    throw new Exception('Já existe uma crendencial de IDOSO válida para essa pessoa.');
                }
            } else {
                throw new Exception('Pessoa não tem direito a credencial de IDOSO.');
            }
        }

        if($this->TIPO === 'DEFICIENTE'){
            if($beneficiario && $beneficiario->DEF === 'S'){
                $ultima_credencial_def = $credencial_verificacao->buscarPorIdBeneficiario(false, $this->BENEFICIARIO, $this->TIPO) ?? null;

                if($ultima_credencial_def && !$ultima_credencial_def->verificaVencida()){
                    throw new Exception('Já existe uma crendencial de DEFICIENTE válida para essa pessoa.');
                }
            } else {
                throw new Exception('Pessoa não tem direito a credencial de DEFICIENTE.');
            }
        }
        
        return parent::salvarCredencial();
    }

    public function buscarPorIdBeneficiario(bool $todos = false, int $id, string $tipo = ''){

        if($todos){
            $busca = $this->busca("BENEFICIARIO = '{$id}'");
        } else {
            $cols_tbinner = [
                "u.NOME AS NOMEOPERADOR",
                "u.CARGO AS CARGOOPERADOR"
            ];
    
            $busca = $this->buscaLeftJoin("usuario", "u", "OPERADOR", "tb_credencial.BENEFICIARIO = '{$id}' AND tb_credencial.TIPO = '{$tipo}'", null, $cols_tbinner);
        }
        
        return $busca->resultado($todos);
    } 
   
    public function buscarPorNumero(string $num_registro, string $ano){

        $cols_tbinner = [
            "u.NOME AS NOMEOPERADOR",
            "u.CARGO AS CARGOOPERADOR"
        ];

        $busca = $this->buscaLeftJoin("usuario", "u", "OPERADOR", "tb_credencial.REGISTRO = '{$num_registro}' AND tb_credencial.ANO = {$ano}", null, $cols_tbinner);
        return $busca->resultado();
    } 

    public function verificaVencida(){
        // Obtém a data de nascimento no formato 'Y-m-d'
        $data_validade = new DateTime($this->VALIDADE);
        $hoje = new DateTime(); // Data atual

        // Retorna se data de validade já passou
        return $data_validade < $hoje;
    }
    
    public function calcularValidade(string $situacao_pne): DateTime{

        $hoje = new DateTime(); // Data atual

        if ($hoje->format('m-d') === '02-29') {
            // Se for 29 de fevereiro (ano bissexto)
            $hoje->modify('-1 day'); // Ajusta para 28/02
        }

        // Adiciona 5 anos
        $data_validade = (clone $hoje)->add(new DateInterval('P5Y'));

        if($situacao_pne === 'TEMPORARIO' && $this->TIPO === 'DEFICIENTE'){
            // Adiciona 1 ano
            $data_validade = (clone $hoje)->add(new DateInterval('P1Y'));
        }

        // Garante que a hora seja 00:00:00
        $data_validade->setTime(0, 0, 0);

        return $data_validade;
    }

}

