<?php

/**
 * Class Mapper
 */

use Slim\PDO\Database;

abstract class Mapper {

    protected $db;
    protected $table;
    protected $cols;
    
    /**
     * Mapper constructor.
     * @param Database $db
     */
    public function __construct(Database $db) {
        $this->db = $db;
    }


    /**
     * @param string $col
     * @param $val
     * @param array $cols
     * @return mixed
     */
    function filter(string $col, $val, $cols = []) {
        $cols = empty($cols) ? $this->cols : $cols;
        return $this->db->select($cols)->where($col, "=", $val)->from($this->table)->execute()->fetch();
    }

    /**
     * @param $id
     * @param array $cols
     * @return mixed
     */
    function getOne($id, $cols = []) {
        return $this->filter("id", $id, $cols);
    }

    /**
     * @param array $cols
     * @return mixed
     * @internal param $statement
     */
    function getAll(array $cols = []){
        $cols = empty($cols) ? $this->cols : $cols;
        return $this->db->select($cols)->from($this->table)->execute()->fetchAll();
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

    /**
     * @param array $data
     * @return int
     * @throws Exception
     */
    function updateRow(array $data){
        $values = [];
        $cols = array_diff($this->cols, ["id"]);

        foreach ($cols as $value){
            if(!empty($data[$value]) and true){
                $values [$value]= $data[$value];
            }
        }
        $statement = $this->db->update($values)->table($this->table)->where("id", "=", $data["id"]);
        $result = $statement->execute();

        if (!$result) {
            throw new Exception("could not update record");
        }

        return $result;
    }

    /**
     * @param $id
     * @return int
     */
    function remove(int $id){
        $statement = $this->db->delete()->from($this->table)->where('id', '=', $id);
        $result = $statement->execute();
        return $result;
    }
}
