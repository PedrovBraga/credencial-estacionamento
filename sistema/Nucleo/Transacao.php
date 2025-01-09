<?php

namespace sistema\Nucleo;

use PDO;
use Exception;

class Transacao
{
    private $db;
    
    public function __construct()
    {
        $this->db = Conexao::getInstancia(); // Obtém a instância da conexão
    }

    public function iniciar(): void
    {
        $this->db->beginTransaction();
    }

    public function confirmar(): void
    {
        $this->db->commit();
    }

    public function desfazer(): void
    {
        $this->db->rollBack();
    }

    // Método para executar operações como salvar dados
    public function executarQuery($query, $params = [])
    {
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }
}