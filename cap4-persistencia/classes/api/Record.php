<?php

/**
 * Classe Record
 *
 * Provê os métodos necessários para persistir e
 * recuperar objetos da base de dados (Active Record)
 */
abstract class Record {

    /**
     * Array contendo os dados do objeto.
     * @var array
     */
    protected $data;

    
    /**
     * Instancia um Active Record. Se passado o $id, já carrega o objeto
     * @param $id = ID do objeto
     */
    public function __construct($id = NULL) {
        // se o ID for informado
        if ($id) {
            // carrega o objeto correspondente
            $object = $this->load($id);
            if ($object) {
                $this->fromArray($object->toArray());
            }
        }
    }

    
    /**
     * Executado quando o objeto for clonado.
     * Limpa o ID para que seja gerado um novo ID para o clone.
     */
    public function __clone() {
        unset($this->data['id']);
    }

    /**
     * Executado sempre que uma propriedade for atribuída.
     */
    public function __set($prop, $value) {
        // verifica se existe método set_<propriedade>
        if (method_exists($this, 'set_' . $prop)) {
            // executa o método set_<propriedade>
            call_user_func(array($this, 'set_' . $prop), $value);
        } else {
            if ($value === NULL) {
                unset($this->data[$prop]);
            } else {
                $this->data[$prop] = $value; // atribui o valor da propriedade
            }
        }
    }

    /**
     * Executado sempre que uma propriedade for requerida
     */
    public function __get($prop) {
        if (method_exists($this, 'get_' . $prop)) {
            // executa o método get_<propriedade>
            return call_user_func(array($this, 'get_' . $prop));
        } else {
            if (isset($this->data[$prop])) {
                return $this->data[$prop];
            }
        }
    }

    /**
     * Executado sempre que se testar a presença de um valor no objeto
     */
    public function __isset($prop) {
        return isset($this->data[$prop]);
    }

    
    /**
     * Retorna o nome da entidade (tabela)
     */
    private function getEntity() {
        // obtém o nome da classe
        $class = get_class($this);

        // retorna a constante de classe TABLENAME
        return constant("{$class}::TABLENAME");
    }

    
    /**
     * Preenche os dados do objeto com um array
     */
    public function fromArray($data) {
        $this->data = $data;
    }

    /**
     * Retorna os dados do objeto como array
     *
     * @return array
     */
    public function toArray() {
        return $this->data;
    }

    
    /**
     * Armazena o objeto na base de dados e retorna
     * o número de linhas afetadas pela instrução SQL (zero ou um)
     *
     * @return type
     * @throws Exception
     */
    public function store() {
        // Prepara os dados antes de serem inseridos na base de dados
        $prepared = $this->prepare($this->data);

        // verifica se tem ID ou se existe na base de dados
        if (empty($this->data['id']) or ( !$this->load($this->id))) {
            // incrementa o ID
            if (empty($this->data['id'])) {
                $this->id = $this->getLast() + 1;
                $prepared['id'] = $this->id;
            }

            // Cria uma instrução de INSERT
            $sql = "INSERT INTO {$this->getEntity()} ";
            $sql .= "(" . implode(', ', array_keys($prepared)) . ")";
            $sql .= " VALUES ";
            $sql .= "(" . implode(', ', array_values($prepared)) . ");";
        } else {

            // Cria uma instrução de UPDATE
            $sql = "UPDATE {$this->getEntity()}";
            // monta os pares: coluna=valor,...
            if ($prepared) {
                foreach ($prepared as $column => $value) {
                    if ($column !== 'id') {
                        $set[] = "{$column} = {$value}";
                    }
                }
            }
            $sql .= " SET " . implode(', ', $set);
            $sql .= " WHERE id=" . (int) $this->data['id'] . ";";
        }

        // obtém transação ativa
        if ($conn = Transaction::get()) {
            // faz o log ...
            Transaction::log($sql);

            // e executa o SQL
            $result = $conn->exec($sql);

            // retorna o resultado
            return $result;
        } else {
            // se não houver uma transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }

    /**
     * Recupera (retorna) um objeto da base de dados
     * através de seu ID e instancia ele na memória
     *
     * @param int $id ID do objeto
     * @return type
     * @throws Exception
     */
    public function load($id) {

        // monta instrução de SELECT
        $sql = "SELECT * FROM {$this->getEntity()}";
        $sql .= " WHERE id=" . (int) $id . ";";

        // obtém transação ativa
        if ($conn = Transaction::get()) {
            // cria mensagem de log
            Transaction::log($sql);

            // e executa a consulta
            $result = $conn->query($sql);

            // se retornou algum dado
            if ($result) {
                // retorna os dados em forma de objeto
                $object = $result->fetchObject(get_class($this));
            }
            return $object;
        } else {
            // se não houver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }

    /**
     * Exclui um objeto da base de dados através de seu ID.
     * @param int $id ID do objeto
     */
    public function delete($id = NULL) {
        // o ID é o parâmetro ou a propriedade ID
        $id = $id ? $id : $this->id;

        // monsta a string de DELETE
        $sql = "DELETE FROM {$this->getEntity()}";
        $sql .= " WHERE id=" . (int) $this->data['id'] . ";";

        // obtém transação ativa
        if ($conn = Transaction::get()) {
            // faz o log
            Transaction::log($sql);

            // e executa o SQL
            $result = $conn->exec($sql);

            // retorna o resultado
            return $result;
        } else {
            // se não houver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }

    
    /**
     * Retorna o último ID
     */
    private function getLast() {
        // inicia transação
        if ($conn = Transaction::get()) {

            // monta a instrução de SELECT
            $sql = "SELECT MAX(id) as id FROM {$this->getEntity()};";

            // cria log
            Transaction::log($sql);

            // executa instrução SQL
            $result = $conn->query($sql);

            // retorna os dados do banco
            $row = $result->fetch();
            return $row[0];
        } else {
            // se não houver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }

    
    /**
     * Retorna todos os dados
     *
     * @return type
     */
    public static function all() {
        $classname = get_called_class();
        $rep = new Repository($classname);
        return $rep->load(new Criteria);
    }

    /**
     * Será utilizado para buscarmos um objeto a partir da base de dados.<br>
     * Ele na verdade é só um atalho para o método load(), com a facilidade
     * de ser executado estaticamente.
     *
     * @param $id = ID do objeto
     * @return type
     */
    public static function find($id) {
        echo $classname = get_called_class();
        $ar = new $classname;
        return $ar->load($id);
    }

    
    /**
     * Prepara os dados antes de serem inseridos na base de dados.<br>
     * Para tal, percorre o array $data, testando cada um dos valores presentes.<br>
     * O Resultado desta transformação é outro array $prepared
     * que é utilizado para montarmos a instrução SQL.
     * 
     * @param mixed $data Array $data
     * @return type
     */
    public function prepare($data) {
        $prepared = array();
        foreach ($data as $key => $value) {
            // verifica se é um dado escalar (string, inteiro,...)
            if (is_scalar($value)) {
                $prepared[$key] = $this->escape($value);
            }
        }
        return $prepared;
    }

    /**
     * Recebeum valor e formata-o conforme o seu tipo.
     * @param type $value
     */
    public function escape($value) {
        // verifica se o dado é uma string e se não está vazia
        if (is_string($value) and ( !empty($value))) {
            // adiciona \ em aspas
            $value = addslashes($value);
            // caso seja uma string
            return "'$value'";
            // verifica se o dado é um boolean
        } else if (is_bool($value)) {
            // caso seja um boolean
            return $value ? 'TRUE' : 'FALSE';
        } else if ($value !== '') {
            // caso seja outro tipo de dado
            return $value;
        } else {
            // caso seja NULL
            return "NULL";
        }
    }

}
