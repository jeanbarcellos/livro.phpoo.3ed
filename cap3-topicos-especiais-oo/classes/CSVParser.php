<?php

class CSVParser {

    private $filename;
    private $separator;
    private $counter;
    private $data; // Vetor com as linhas do arquivo CSV
    private $header; // Vetor com as colunas do cabecalho do arquivo CSV (primeira linha)    

    public function __construct($filename, $separator = ',') {
        $this->filename = $filename;
        $this->separator = $separator;
        $this->counter = 1;
    }

    /**
     * Lê o arquivo, carrega-o em memória e transforma-o em um vetor
     * onde cada linha lida do arquivo será uma posição do array.
     * 
     * @return boolean
     * @throws Exception
     */
    public function parse() {
        if (!file_exists($this->filename)) {
            throw new Exception("Arquivo {$this->filename} não encontrado");
        }
        if (!is_readable($this->filename)) {
            throw new Exception("Arquivo {$this->filename} não pode ser lido");
        }
        $this->data = file($this->filename);
        $this->header = str_getcsv($this->data[0], $this->separator);
        return TRUE;
    }

    /**
     * Efetuará a carga do arquivo em memória.
     * 
     * @return Array Vetor com as colunas de uma linha
     */
    public function fetch() {
        if (isset($this->data[$this->counter])) {
            $row = str_getcsv($this->data[$this->counter ++], $this->separator);
            foreach ($row as $key => $value) {
                $row[trim($this->header[$key])] = trim($value);
            }
            return $row;
        }
    }

}

/*

file - Carrega todo um arquivo em um array onde cada linha ficará em um indice
(PHP 4, PHP 5, PHP 7)

str_getcsv — Analisa uma string CSV e retorna os dados em um array 
(PHP 5 >= 5.3.0, PHP 7)
 
 */