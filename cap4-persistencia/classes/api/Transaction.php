<?php

/**
 * Classe Transaction
 * 
 * Esta classe provê os métodos necessários para manipular transações
 */
final class Transaction {

    private static $conn; // conexão ativa
    private static $logger; // logger

    /**
     * Não existirão instâncias de Transaction
     */
    private function __construct() {
        
    }

    /**
     * Abre uma transação e uma conexão ao BD
     * @param String $database Nome do banco de dados
     */
    public static function open($database = 'default') {
        // Verifica se há conexão
        if (empty(self::$conn)) {
            // abre uma conexão e armazena na propriedade estática $conn
            self::$conn = Connection::open($database);
            
            // inicia a transação
            self::$conn->beginTransaction();
            
            // inicia a estratégia de log como nula
            self::$logger = NULL;
        }
    }

    /**
     * Retorna a conexão ativa da transação
     */
    public static function get() {
        return self::$conn;
    }

    /**
     * Desfaz todas operações realizadas na transação e fecha a conexão
     */
    public static function rollback() {        
        if (self::$conn) {
            self::$conn->rollback(); // desfaz as operações realizadas
            self::$conn = NULL; // fecha a conexão
        }
    }

    /**
     * Faz commit em todas as operações realizadas na transação e fechaa conexão
     */
    public static function close() {
        if (self::$conn) {
            self::$conn->commit(); // aplica as operações realizadas
            self::$conn = NULL; // fecha a conexão
        }
    }

    /**
     * Define qual estratégia (algoritmo) de LOG será usada.
     */
    public static function setLogger(Logger $logger) {
        self::$logger = $logger;
    }

    /**
     * Armazena uma mensagem no arquivo de LOG
     * baseada na estratégia ($logger) atual
     */
    public static function log($message) {
        // verifica existe um logger
        if (self::$logger) {
            self::$logger->write($message);
        }
    }

}
