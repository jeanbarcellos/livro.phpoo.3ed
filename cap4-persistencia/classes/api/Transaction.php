<?php

/**
 * classe Transaction
 * esta classe provê os métodos necessários manipular transações
 */
final class Transaction {

    private static $conn; // conexão ativa
    private static $logger; // logger

    /**
     * método __construct()
     * Está declarado como private para impedir que se crie instâncias de Transaction
     */
    private function __construct() {
        
    }

    /**
     * método open()
     * Abre uma transação e uma conexão ao BD
     * @param $database = nome do banco de dados
     */
    public static function open($database) {
        if (empty(self::$conn)) {
            // abre uma conexão e armazena na propriedade estática $conn
            self::$conn = Connection::open($database);
            // inicia a transação
            self::$conn->beginTransaction(); // inicia a transação
            
            self::$logger = NULL;
        }
    }

    /**
     * método get()
     * retorna a conexão ativa da transação
     */
    public static function get() {
        return self::$conn; // retorna a conexão ativa
    }

    /**
     * método rollback()
     * desfaz todas operações realizadas na transação e fecha a conexão
     */
    public static function rollback() {        
        if (self::$conn) {
            self::$conn->rollback(); // desfaz as operações realizadas
            self::$conn = NULL; // fecha a conexão
        }
    }

    public static function close() {
        if (self::$conn) {
            self::$conn->commit(); // aplica as operações realizadas
            self::$conn = NULL; // fecha a conexão
        }
    }

    /**
     * método setLogger()
     * define qual estratégia (algoritmo de LOG será usado)
     */
    public static function setLogger(Logger $logger) {
        self::$logger = $logger;
    }

    /**
     * método log()
     * armazena uma mensagem no arquivo de LOG
     * baseada na estratégia ($logger) atual
     */
    public static function log($message) {
        // verifica existe um logger
        if (self::$logger) {
            self::$logger->write($message);
        }
    }

}
