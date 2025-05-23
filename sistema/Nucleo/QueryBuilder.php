<?php

namespace sistema\Nucleo;

use sistema\Modelo\Auditoria;
use sistema\Nucleo\Conexao;
use sistema\Nucleo\Mensagem;


abstract class QueryBuilder
{
    // Dados para criar e atualizar. Atributo genérico, permite criarmos qualquer atributo "no lugar" dele. 
    protected $dados;
    // Query para consulta
    protected $query;
    // Recebe erro ou msg de erro
    protected $erro;
    // Dados para buscas
    protected $parametros;
    // Valor da tabela que a classe filha se refere
    protected $tabela;
    protected $ordem;
    protected $limite;
    protected $offset;
    // Objeto Mensagem a ser exibido p/ usuário
    protected $mensagem;

    public function __construct(string $tabela)
    {
        $this->tabela = $tabela;

        $this->mensagem = new Mensagem();
    }

    public function ordem(string $ordem)
    {
        $this->ordem = " ORDER BY {$ordem}";
        return $this;
    }

    public function limite(string $limite)
    {
        $this->limite = " LIMIT {$limite}";
        return $this;
    }

    public function offset(string $offset)
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    public function erro()
    {
        return $this->erro;
    }

    public function mensagem()
    {
        return $this->mensagem;
    }

    public function dados()
    {
        return $this->dados;
    }

    // Popula $dados com atributos dinâmicos
    public function __set($nome, $valor)
    {
        if (empty($this->dados)) {
            $this->dados = new \stdClass();
        }

        $this->dados->$nome = $valor;
    }

    // Verifica se uma propriedade está definida dentro de $dados
    public function __isset($nome)
    {
        return isset($this->dados->$nome);
    }

    // Acessa o valor de uma propriedade dentro de $dados, retornando null se a propriedade não estiver definida.
    public function __get($nome)
    {
        return $this->dados->$nome ?? null;
    }

    public function busca(?string $termos = null, ?string $parametros = null, string $colunas = '*')
    {
        if ($termos) {
            $this->query = "SELECT {$colunas} FROM " . $this->tabela . " WHERE {$termos} ";
            parse_str($parametros, $this->parametros);
            return $this;
        }

        $this->query = "SELECT {$colunas} FROM " . $this->tabela;
        return $this;
    }
    
    // LEFT JOIN sempre retorna os valores da tb base (tb_beneficiario) mesmo que a condição ON não seja realizada
    public function buscaLeftJoin(string $tb_inner = null, string $alias_tbinner = null, string $coluna_comparacao = null, ?string $termos = null, ?string $parametros = null, array $colunas = ['*'])
    {
        $tb_inner = "tb_".$tb_inner;

        // Concatena as colunas com aliases para a consulta
        $colunas_str = implode(', ', $colunas);
        
        $this->query = "SELECT {$this->tabela}.*, {$colunas_str} FROM {$this->tabela} 
                        LEFT JOIN {$tb_inner} {$alias_tbinner} 
                        ON {$alias_tbinner}.ID = $this->tabela.{$coluna_comparacao}";

        if ($termos) {
            $this->query .= " WHERE {$termos} ";
            parse_str($parametros, $this->parametros);
            return $this;
        }

        return $this;
    }
    
    public function buscaPorId(int $id)
    {
        $busca = $this->busca("ID = {$id}");
        return $busca->resultado();
    }

    public function buscaPorRegistro(int $numRegistro, int $ano)
    {
        $busca = $this->busca("REGISTRO = {$numRegistro} AND ANO = {$ano}");
        return $busca->resultado();
    }

    protected function cadastrar(array $dados)
    {
        try {
            $colunas = implode(',', array_keys($dados));
            $valores = ':' . implode(',:', array_keys($dados));

            $query = "INSERT INTO " . $this->tabela . " ({$colunas}) VALUES ({$valores}) ";
            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute($this->filtro($dados));

            return Conexao::getInstancia()->lastInsertId();
        } catch (\PDOException $ex) {
            echo $this->erro = $ex->getMessage();
            return null;
        }
        // Retorna último ID inserido ou erro
    }

    protected function atualizar(array $dados, string $termos)
    {
        try {
            $set = [];

            foreach ($dados as $chave => $valor) {
                $set[] = "{$chave} = :{$chave}";
            }
            $set = implode(', ', $set);

            $query = "UPDATE " . $this->tabela . " SET {$set} WHERE {$termos}";
            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute($this->filtro($dados));

            return ($stmt->rowCount() ?? 1);
        } catch (\PDOException $ex) {
            echo $this->erro = $ex->getMessage();
            return null;
        }
        // Retorna número de atualizações ou erro
    }

    public function apagar(string $termos)
    {
        try {
            $query = "DELETE FROM " . $this->tabela . " WHERE {$termos}";
            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute();

            return true;
        } catch (\PDOException $ex) {
            $this->erro = $ex->getMessage();
            return null;
        }

        // Retorna TRUE ou erro
    }
    
    public function deletar()
    {
        if(empty($this->ID)){
            return false;
        }
        
        $deletar = $this->apagar("ID = {$this->ID}");
        return $deletar;
    }

    public function resultado(bool $todos = false)
    {
        try {
            $stmt = Conexao::getInstancia()->prepare($this->query . $this->ordem . $this->limite . $this->offset);
            // echo var_dump($stmt);
            $stmt->execute($this->parametros);
            if (!$stmt->rowCount()) {
                return null;
            }

            if ($todos) {
                return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
            }

            return $stmt->fetchObject(static::class);
        } catch (\PDOException $ex) {
            // echo $this->erro = $ex;
            throw new \Exception('Erro de sistema ao retornar os dados');
            // return null;
        }
        // Retorna null, todos os resultados como objeto da classe em que a função foi chamada
        // ou 1 resultado como objeto da classe em que a função foi chamada
    }

    public function total(): int
    {
        $stmt = Conexao::getInstancia()->prepare($this->query);
        $stmt->execute($this->parametros);

        return $stmt->rowCount();
        // Retorna total de registros encontrados
    }

    private function filtro(array $dados)
    {
        $filtro = [];

        foreach ($dados as $chave => $valor) {
            $filtro[$chave] = (is_null($valor) ? null : filter_var($valor, FILTER_DEFAULT));
        }

        return $filtro;
    }

    protected function armazenar()
    {
        $dados = (array) $this->dados;
        return $dados;
    }

    public function preencher(array $dados): void
    {
        foreach ($dados as $campo => $valor) {
            // if (property_exists($this, $campo)) { // Verifica se a propriedade existe na classe
                $campo_upper = strtoupper($campo);
                $this->$campo_upper = $valor; 
            // }
        }
    }


    public function salvar()
    {
        $auditoria = new Auditoria();

        //CADASTRAR
        if (empty($this->ID)) {
            $ID = $this->cadastrar($this->armazenar());
            
            if ($this->erro) {
                echo "\n SALVAR \n".var_dump($this);
                throw new \Exception('Erro de sistema ao tentar cadastrar os dados');
            }
            
            // Passa operação e objeto
            $auditoria->gravar('INCLUSÃO', $this);
        }

        //ATUALIZAR
        if (!empty($this->ID)) {
            $ID = $this->ID;
            $this->atualizar($this->armazenar(), "ID = {$ID}");
            if ($this->erro) {
               throw new \Exception('Erro de sistema ao tentar atualizar os dados');
            }
            $auditoria->gravar('ATUALIZAÇÃO', $this);
        }

        $this->dados = $this->buscaPorId($ID)->dados();
        return true;
    }

    public function salvarCredencial()
    {
        $auditoria = new Auditoria();
        //ATUALIZAR
        if (!empty($this->REGISTRO)) {
            $REGISTRO = $this->REGISTRO;
            $ANO = $this->ANO;
            $this->atualizar($this->armazenar(), "REGISTRO = {$REGISTRO} AND ANO = {$ANO}");
            
            if ($this->erro) {
               throw new \Exception('Erro de sistema ao tentar atualizar os dados');
            }

            $auditoria->gravar('ATUALIZAÇÃO', $this);

        }

        //CADASTRAR
        if (empty($this->REGISTRO)) {
            $REGISTRO = $this->cadastrar($this->armazenar());
            $ANO = $this->ANO;
           
            if ($this->erro) {
                // echo "\n SALVAR \n".var_dump($this);
                throw new \Exception('Erro de sistema ao tentar cadastrar os dados');
            }
            $this->REGISTRO = $REGISTRO;
            $auditoria->gravar('INCLUSÃO', $this);

        }

        $this->dados = $this->buscaPorRegistro($REGISTRO, $ANO)->dados();
        return true;
    }

    private function ultimoId(): int {
        return Conexao::getInstancia()->query("SELECT MAX(ID) as maximo FROM {$this->tabela}")->fetch()->maximo + 1;
    }

}
