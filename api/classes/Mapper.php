<?php

/**
 * Class Mapper
 */

use Slim\PDO\Database;
use Slim\PDO\Statement\SelectStatement;

abstract class Mapper {

    protected $db;
    protected $table;
    protected $cols;
    
    /**
     * Mapper constructor.
     * @param Database $db
     * @param $table
     */
    public function __construct(Database $db) {
        $this->db = $db;
    }


    /**
     * @param $statement
     * @return mixed
     */
    function getOne(SelectStatement $statement) {
        return $statement->from($this->table)->execute()->fetch();
    }

    /**
     * @param $statement
     * @return mixed
     */
    function getAll(SelectStatement $statement){
        return $statement->from($this->table)->execute()->fetchAll();
    }

    function newRow(array $data){
        $values = [];
        $cols = array_diff($this->cols, ["id"]);
        foreach ($cols as $value){
            $values []= $data[$value];
        }

        $statement = $this->db->insert($cols)->into($this->table)->values($values);
        $result = $statement->execute();

        if (!$result) {
            throw new Exception("could not save record");
        }

        return $result;
    }

    function updateRow(array $data){
        $values = [];
        $cols = array_diff($this->cols, ["id"]);

        foreach ($cols as $value){
            $values [$value]= $data[$value];
        }
        $statement = $this->db->update($values)->table($this->table)->where("id", "=", $data["id"]);
        $result = $statement->execute();

        if (!$result) {
            throw new Exception("could not update record");
        }

        return $result;
    }

    function remove($id){
        $statement = $this->db->delete()->from($this->table)->where('id', '=', $id);
        $result = $statement->execute();
        return $result;
    }
}
