<?php
namespace sistema\Modelo;

use DateTime;
use Exception;
use sistema\Nucleo\QueryBuilder;
use sistema\Modelo\Representante;

class Beneficiario extends QueryBuilder {

    public function __construct() {
        $this->tabela = 'tb_beneficiario';
    }

    public function gravar(Representante $representante = null){
        if($representante){
            echo $representante->RG.' --------- ';
            // Pegar ID do representante e passar para beneficiario
            $representante_cadastrado = $representante->buscaPorCPFouRG($representante->RG);

            if (!$representante_cadastrado) {
                // Se não encontrar o representante, lança uma exceção ou retorna false
                throw new Exception('Representante não encontrado ao buscar ID.');
            }
            echo print_r($representante_cadastrado);
            // Adiciona ID do representante ao Beneficiario
            $this->REPRESENTANTE = $representante_cadastrado->ID;
        }

        if(!$this->ID){
            if($this->buscaPorCPFOuRG($this->CPF)){
                $usuario_existente = $this->buscaPorCPFOuRG($this->CPF);
                echo print_r($usuario_existente);
                throw new Exception('Número de CPF já cadastrado!');
            } 
        }

        return parent::salvar();   
    }

    public function buscaPorCPFOuRG(string $doc, bool $com_representante = true)
    {
        if($com_representante){
            $cols_tbinner = [
                "r.NOME AS NOMEREP",
                "r.EMAIL AS EMAILREP",
                "r.TELEFONE AS TELEFONEREP",
                "r.CEP AS CEPREP",
                "r.ENDERECO AS ENDERECOREP",
                "r.COMPLEMENTO AS COMPLEMENTOREP",
                "r.BAIRRO AS BAIRROREP",
                "r.CIDADE AS CIDADEREP",
                "r.UF AS UFREP",
                "r.RG AS RGREP",
                "r.RG_EXPEDIDOR AS RG_EXPEDIDORREP",
                "r.RG_DATA AS RG_DATAREP",
                "r.CPF AS CPFREP"
            ];
    
            $busca = $this->buscaLeftJoin("representante", "r", "REPRESENTANTE", "tb_beneficiario.CPF = '{$doc}' OR tb_beneficiario.RG = '{$doc}'", null, $cols_tbinner);
        } else {
            $busca = $this->busca("CPF = :d OR RG = :d", "d={$doc}");
        }

        return $busca->resultado();
    }

    public function calculaIdade(): int{
       // Obtém a data de nascimento no formato 'Y-m-d'
        $dataNascimento = new DateTime($this->DATA_NASCIMENTO);
        $hoje = new DateTime(); // Data atual

        // Calcula a diferença entre as datas
        $idade = $hoje->diff($dataNascimento)->y;

        return $idade;
    }
}
