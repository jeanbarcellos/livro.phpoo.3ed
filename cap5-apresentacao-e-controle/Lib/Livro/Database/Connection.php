<?php

namespace Livro\Database;

use PDO;
use Exception;

/**
 * Cria conexões com bancos de dados
 * 
 * @author Pablo Dall'Oglio
 */
final class Connection {

    /**
     * Não podem existir instâncias de TConnection
     */
    private function __construct() {
        
    }

    /**
     * Recebe o nome do banco de dados e instancia o objeto PDO correspondente
     * 
     * @param String $arquivo Nome do arquivo de configurações
     * @return Object Objeto PDO
     */
    public static function open($arquivo) {
        // verifica se existe arquivo de configuração para este banco de dados
        if (file_exists("App/Config/{$arquivo}.ini")) {
            // lê o INI e retorna um array
            $db = parse_ini_file("App/Config/{$arquivo}.ini");
        } else {
            // se não existir, lança um erro
            throw new Exception("Arquivo '$arquivo' não encontrado");
        }

        // lê as informações contidas no arquivo
        $user = isset($db['user']) ? $db['user'] : NULL;
        $pass = isset($db['pass']) ? $db['pass'] : NULL;
        $name = isset($db['name']) ? $db['name'] : NULL;
        $host = isset($db['host']) ? $db['host'] : NULL;
        $type = isset($db['type']) ? $db['type'] : NULL;
        $port = isset($db['port']) ? $db['port'] : NULL;

        // descobre qual o tipo (driver) de banco de dados a ser utilizado
        switch ($type) {
            case 'pgsql':
                $port = $port ? $port : '5432';
                $conn = new PDO("pgsql:dbname={$name}; user={$user}; password={$pass};
                        host=$host;port={$port}");
                break;
            case 'mysql':
                $port = $port ? $port : '3306';
                $conn = new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass);
                break;
            case 'sqlite':
                $conn = new PDO("sqlite:{$name}");
                break;
            case 'ibase':
                $conn = new PDO("firebird:dbname={$name}", $user, $pass);
                break;
            case 'oci8':
                $conn = new PDO("oci:dbname={$name}", $user, $pass);
                break;
            case 'mssql':
                $conn = new PDO("mssql:host={$host},1433;dbname={$name}", $user, $pass);
                break;
        }
        // define para que o PDO lance exceções na ocorrência de erros
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // retorna o objeto PDO instanciado.
        return $conn;
    }

}
