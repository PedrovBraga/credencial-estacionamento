<?php

namespace sistema\Nucleo;

use DateTime;
use Exception;
use sistema\Modelo\Usuario;

/**
 * Classe Helper - Classe auxiliar responsável por prover métodos estáticos para manipular e validar dados no sistema.
 *
 * @author Ronaldo Aires <ceo@unset.com.br>
 * @copyright 2022 UnSet
 */
class Helpers
{

    /**
     * Redireciona para a url informada
     * @param string $url
     * @return void
     */
    public static function redirecionar(string $url = null): void
    {
        header('HTTP/1.1 302 Found');

        $local = ($url ? self::url($url) : self::url());

        header("Location: {$local} ");
        exit();
    }

    // Trata formato de data para ser exibido no front sem erro e sem perder hora, minuto e segundo.
    // Usar sempre que enviar e receber data do front 
    public static function trataFormatoData($data){
        // Se estiver no formato do front-end
        if(strpos($data,'T') !== false){
            return $data->format('Y-m-d H:i:s');
        } else {
            // Se estiver no formato do back
            return (new DateTime($data))->format('Y-m-d');
        }
    }

    // Substring procurada no name recebido do form é utilizada como chave para um dos arrays de separação
    public static function separarDadosFormulario(array $dados, string $substringA, string $substringB = null): array
    {
        $separados = [
            $substringA => [],
            $substringB => [],
            'outro' => []
        ];

        foreach ($dados as $campo => $valor) {
            $campo_uppercase = mb_strtoupper($campo, 'UTF-8');
            $valor_uppercase = mb_strtoupper($valor, 'UTF-8');
 
            // Verifica se name (dentro de $dados) contém string que identifica a qual Objeto ele pertence
            if (strpos($campo, $substringA) !== false) {
                $tam_susbstring = mb_strlen($substringA, 'UTF-8');
                
                // torna valor negativo para remover substring através de substr() e contar o tamanho de trás pra frente
                // - 1 é referente ao '_' que vem na $string mas não utilizamos na $substring
                $tam_susbstr = - $tam_susbstring - 1;
                
                // remove '_$substring' do fim da string
                $campo_upper_tratado = substr($campo_uppercase, 0, $tam_susbstr);

                $separados[$substringA][$campo_upper_tratado] = $valor_uppercase;
            } else {
                // Verifica se name ($dados['name']) contém string que identifica a qual Objeto ele pertence
                if (strpos($campo, $substringB) !== false) {
                    $tam_susbstring = mb_strlen($substringB, 'UTF-8');
                    
                    // torna valor negativo para remover substring através de substr() e contar o tamanho de trás pra frente
                    // - 1 é referente ao '_' que vem na $string mas não utilizamos na $substring
                    $tam_susbstr = - $tam_susbstring - 1;
                    
                    // remove '_$substring' do fim da string
                    $campo_upper_tratado = substr($campo_uppercase, 0, $tam_susbstr);
    
                    $separados[$substringB][$campo_upper_tratado] = $valor_uppercase;
                } else $separados['outro'][$campo_uppercase] = $valor_uppercase;
            }
        }

        return $separados;
    }

    /**
     * Método para verificar se o usuário pode acessar as funções do controlador
    */
    public static function usuarioPodeAcessar(): bool {
        $sessao = new Sessao();
    
        // Verifica se o usuário está logado
        if (!$sessao->checar('usuarioId')) {
            return false;
        }
    
        // Obtém os dados do usuário logado
        $usuario = (new Usuario())->buscaPorId($sessao->usuarioId);
    
        // Permitir acesso à página de alteração de senha
        $rotaAtual = $_SERVER['REQUEST_URI'];
        if (strpos($rotaAtual, 'usuario/alterar-senha') !== false) {
            return true;
        }
    
        // Bloquear acesso se o nível for "U"
        if ($usuario && $usuario->COD_PERFIL === 'U') {
            return false;
        }
    
        return true;
    }

    /**
     * Válida um número de CPF
     * @param string $cpf
     * @return bool
     */
    public static function validarCpf(string $cpf): bool
    {
        $cpf = self::limparNumero($cpf);

        if (mb_strlen($cpf) != 11 or preg_match('/(\d)\1{10}/', $cpf)) {
            throw new Exception('O CPF precisa ter 11 digitos');
        }
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                throw new Exception('CPF Inválido');
            }
        }
        return true;
    }

    /**
     * Limpa todos os caracteres não numéricos
     * @param string $numero
     * @return string
     */
    public static function limparNumero(string $numero): string
    {
        return preg_replace('/[^0-9]/', '', $numero);
    }

    /**
     * Gera url amigável
     * @param string $string
     * @return string slug
     */
    public static function slug(string $string): string
    {
        $mapa['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?¨|;:.,\\\'<>°ºª  ';

        $mapa['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';
        $slug = strtr(utf8_decode($string), utf8_decode($mapa['a']), $mapa['b']);
        $slug = strip_tags(trim($slug));
        $slug = str_replace(' ', '-', $slug);
        $slug = str_replace(['-----', '----', '---', '--', '-'], '-', $slug);

        return strtolower(utf8_decode($slug));
    }

    /**
     * Data atual formatada 
     * @return string
     */
    public static function dataAtual(): string
    {
        $diaMes = date('d');
        $diaSemana = date('w');
        $mes = date('n') - 1;
        $ano = date('Y');

        $nomesDiasDaSemana = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sabádo'];

        $nomesDosMeses = [
            'janeiro',
            'fevereiro',
            'março',
            'abril',
            'maio',
            'junho',
            'julho',
            'agosto',
            'setembro',
            'outubro',
            'novembro',
            'dezembro'
        ];

        $dataFormatada = $nomesDiasDaSemana[$diaSemana] . ', ' . $diaMes . ' de ' . $nomesDosMeses[$mes] . ' de ' . $ano;

        return $dataFormatada;
    }

    /**
     * Monta url de acordo com o ambiente
     * @param string $url parte da url ex. admin
     * @return string url completa
     */
    public static function url(string $url = null): string
    {
        $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');
        $ambiente = ($servidor == 'localhost' ? URL_DESENVOLVIMENTO : URL_PRODUCAO);

        if (str_starts_with($url, '/')) {
            return $ambiente . $url;
        }
        return $ambiente . '/' . $url;
    }

    /**
     * Checa se o servidor é localhost
     * @return bool
     */
    public static function localhost(): bool
    {
        $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');

        if ($servidor == 'localhost') {
            return true;
        }
        return false;
    }

    /**
     * Valida uma url
     * @param string $url
     * @return bool
     */
    public static function validarUrl(string $url): bool
    {
        if (mb_strlen($url) < 10) {
            return false;
        }
        if (!str_contains($url, '.')) {
            return false;
        }
        if (str_contains($url, 'http://') or str_contains($url, 'https://')) {
            return true;
        }
        return false;
    }

    public static function validarUrlComFiltro(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Valida um endereço de e-mail
     * @param string $email
     * @return bool
     */
    public static function validarEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Conta o tempo decorrido de uma data
     * @param string $data
     * @return string
     */
    public static function contarTempo(string $data): string
    {
        $agora = strtotime(date('Y-m-d H:i:s'));
        $tempo = strtotime($data);
        $diferenca = $agora - $tempo;

        $segundos = $diferenca;
        $minutos = round($diferenca / 60);
        $horas = round($diferenca / 3600);
        $dias = round($diferenca / 86400);
        $semanas = round($diferenca / 604800);
        $meses = round($diferenca / 2419200);
        $anos = round($diferenca / 29030400);

        if ($segundos <= 60) {
            return 'agora';
        } elseif ($minutos <= 60) {
            return $minutos == 1 ? 'há 1 minuto' : 'há ' . $minutos . ' minutos';
        } elseif ($horas <= 24) {
            return $horas == 1 ? 'há 1 hora' : 'há ' . $horas . ' horas';
        } elseif ($dias <= 7) {
            return $dias == 1 ? 'ontem' : 'há ' . $dias . ' dias';
        } elseif ($semanas <= 4) {
            return $semanas == 1 ? 'há 1 semana' : 'há ' . $semanas . ' semanas';
        } elseif ($meses <= 12) {
            return $meses == 1 ? 'há 1 mês' : 'há ' . $meses . ' meses';
        } else {
            return $anos == 1 ? 'há 1 ano' : 'há ' . $anos . ' anos';
        }
    }

    /**
     * Formata um valor com ponto e virgula
     * @param float $valor
     * @return string
     */
    public static function formatarValor(float $valor = null): string
    {
        return number_format(($valor ? $valor : 0), 2, ',', '.');
    }

    /**
     * Formata um número com pontos
     * @param int $numero
     * @return string
     */
    public static function formatarNumero(int $numero = null): string
    {
        return number_format($numero ?: 0, 0, '.', '.');
    }

    /**
     * Saudação de acordo com o horário
     * @return string saudação
     */
    public static function saudacao(): string
    {
        $hora = date('H');

        $saudacao = match (true) {
            $hora >= 0 and $hora <= 5 => 'boa madrugada',
            $hora >= 6 and $hora <= 12 => 'bom dia',
            $hora >= 13 and $hora <= 18 => 'boa tarde',
            default => 'boa noite'
        };

        return $saudacao;
    }

    /**
     * Resume um texto
     * 
     * @param string $texto texto para resumir
     * @param int $limite quantidade de caracteres
     * @param string $continue opcional - o que deve ser exibido ao final do resumo
     * @return string texto resumido
     */
    public static function resumirTexto(string $texto, int $limite, string $continue = '...'): string
    {
        $textoLimpo = trim(strip_tags($texto));
        if (mb_strlen($textoLimpo) <= $limite) {
            return $textoLimpo;
        }

        $resumirTexto = mb_substr($textoLimpo, 0, mb_strrpos(mb_substr($textoLimpo, 0, $limite), ''));

        return $resumirTexto . $continue;
    }

}
