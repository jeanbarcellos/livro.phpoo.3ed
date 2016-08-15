<?php

/**
 * Classe Record
 *
 * Pprovê os métodos necessários para persistir e
 * recuperar objetos da base de dados (Active Record)
 */
abstract class Record {

    protected $data; // array contendo os dados do objeto

    /**
     *  método __construct()
     * instancia um Active Record. Se passado o $id, já carrega o objeto
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
     * método __clone()
     * executado quando o objeto for clonado.
     * limpa o ID para que seja gerado um novo ID para o clone.
     */
    public function __clone() {
        unset($this->data['id']);
    }

    /**
     * método __set()
     * executado sempre que uma propriedade for atribuída.
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
     * método __get()
     * executado sempre que uma propriedade for requerida
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
     * método __isset()
     * executado sempre que se testar a presença de um valor no objeto
     */
    public function __isset($prop) {
        return isset($this->data[$prop]);
    }

    /**
     * método getEntity()
     * retorna o nome da entidade (tabela)
     */
    private function getEntity() {
        // obtém o nome da classe
        $class = get_class($this);

        // retorna a constante de classe TABLENAME
        return constant("{$class}::TABLENAME");
    }

    /**
     * método fromArray
     * preenche os dados do objeto com um array
     */
    public function fromArray($data) {
        $this->data = $data;
    }

    /**
     * método toArray
     * retorna os dados do objeto como array
     */
    public function toArray() {
        return $this->data;
    }

    /**
     * método store()
     * 
     * armazena o objeto na base de dados e retorna
     * o número de linhas afetadas pela instrução SQL (zero ou um)
     */
    public function store() {
        $prepared = $this->prepare($this->data);

        // verifica se tem ID ou se existe na base de dados
        if (empty($this->data['id']) or ( !$this->load($this->id))) {
            // incrementa o ID
            if (empty($this->data['id'])) {
                $this->id = $this->getLast() + 1;
                $prepared['id'] = $this->id;
            }

            // cria uma instrução de INSERT
            $sql  = "INSERT INTO {$this->getEntity()} ";
            $sql .= "(" . implode(', ', array_keys($prepared)) . ")";
            $sql .= " VALUES ";
            $sql .= "(" . implode(', ', array_values($prepared)) . ");";
            
        } else {
            
            // monta a string de UPDATE
            $sql  = "UPDATE {$this->getEntity()}";
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
     * método load()
     * recupera (retorna) um objeto da base de dados
     * através de seu ID e instancia ele na memória
     * @param $id = ID do objeto
     */
    public function load($id) {
        
        // monta instrução de SELECT
        $sql  = "SELECT * FROM {$this->getEntity()}";
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
     * método delete()
     * exclui um objeto da base de dados através de seu ID.
     * @param $id = ID do objeto
     */
    public function delete($id = NULL) {
        // o ID é o parâmetro ou a propriedade ID
        $id = $id ? $id : $this->id;

        // monsta a string de UPDATE
        $sql  = "DELETE FROM {$this->getEntity()}";
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
     * método getLast()
     * retorna o último ID
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
     * 
     */
    public static function all() {
        $classname = get_called_class();
        $rep = new Repository($classname);
        return $rep->load(new Criteria);
    }

    /**
     * método find()
     * Será utilizado para buscarmos um objeto a partir da base de dados.
     * Ele na verdade é só um atalho para o método load(), com a facilidade
     * de ser executado estaticamente.
     * @param $id = ID do objeto
     */
    public static function find($id) {
        $classname = get_called_class();
        $ar = new $classname;
        return $ar->load($id);
    }

    /**
     * Função prepare()
     * Prepara os dados antes de serem inseridos na base de dados.
     * Para tal, percorre o array $data, testando cada um dos valores presentes.
     * O Resultado desta transformação é outro array $prepared
     * que é utilizado para montarmos a instrução SQL.
     * @param mixed $data Array $data
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
     * Função scape()
     * Recebeum valor e formata-o conforme o seu tipo.
     * @param type $value
     */   
    public function escape($value) {
        // verifica se o dado é uma string e se não está fazia
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
