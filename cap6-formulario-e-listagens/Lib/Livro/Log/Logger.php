<?php

namespace Livro\Database;

/**
 * Fornece uma interface abstrata para definição de algoritmos de LOG
 * 
 * @author Pablo Dall'Oglio
 */
abstract class Logger {

    /**
     * Local do arquivo de LOG
     * @var type 
     */
    protected $filename;

    /**
     * Instancia um logger
     * 
     * @param $filename Local do arquivo de LOG
     */
    public function __construct($filename) {
        $this->filename = $filename;
        // reseta o conteúdo do arquivo
        file_put_contents($filename, '');
    }

    /**
     * Define o método write como obrigatório
     */
    abstract function write($message);
}
