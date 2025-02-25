<?php
namespace sistema\Modelo;

use DateTime;
use Exception;
use sistema\Nucleo\QueryBuilder;
use sistema\Nucleo\Sessao;

class Auditoria extends QueryBuilder {

    public function __construct() {
        $this->tabela = 'tb_auditoria';
    }

    public function gravar(string $operacao, $objeto){

        switch(get_class($objeto)){
            // Espaço no início da string devido a concatenação
            case 'Sistema\Modelo\Usuario':
                $operacao .= ' DE USUÁRIO';
                break;
            case 'sistema\Modelo\Beneficiario':
                $operacao .= ' DE BENEFICIÁRIO';
                break;
            case 'sistema\Modelo\Representante':
                $operacao .= ' DE REPRESENTANTE';
                break;
                // Pq 'S'?
            case 'Sistema\Modelo\Credencial':
                $objeto->TIPO === 'IDOSO' ? $operacao .= ' DE CREDENCIAL IDOSO' : $operacao .= ' DE CREDENCIAL PNE';
                $num_credencial = $objeto->REGISTRO.'/'.$objeto->ANO;
                break;
        }


        $sessao = new Sessao();
        $usuario = (new Usuario())->buscaPorId($sessao->usuarioId); 

        $this->OPERADOR = $usuario->USUARIO;
        $this->OPERACAO = $operacao;
        $this->DT_OPERACAO = (new DateTime())->format('Y-m-d H:i:s');
        $this->NOME_MUNICIPE = $objeto->NOME ?? '';
        $this->CREDENCIAL = $num_credencial ?? '';

        return $this->cadastrar($this->armazenar());   
    }

    public function buscaPorCPFOuRG(string $doc)
    {
        $beneficiario = (new Beneficiario())->buscaPorCPFOuRG($doc, false);
    
        if(!$beneficiario){
            throw new Exception('Beneficiário não encontrado.');
        }
        // echo "beneficiario: ".var_dump($beneficiario);
        $busca = (new Auditoria())->busca("NOME_MUNICIPE = :n", "n={$beneficiario->NOME}");
        // var_dump($busca);
        return $busca->resultado(true);
    }
    
    public function buscaPorOperador(string $operador)
    {

        $busca = (new Auditoria())->busca("OPERADOR = :o", "o={$operador}");

        return $busca->resultado(true);
    }

}




