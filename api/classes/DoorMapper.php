<?php

class DoorMapper extends Mapper
{
    //get, post, delete, put
    protected $table = "door";
    protected $cols = ["id", "name", "price"];

    function one($door_id) {
        $statement= $this->db->select($this->cols)->where("id", "=", $door_id);
        return $this->getOne($statement);
    }

    function all() {
        $statement= $this->db->select($this->cols);
        return $this->getAll($statement);
    }

    function newDoor($data) {
        return count($this->newRow($data));
    }

    function put($id, $data){
        return $this->updateRow($id, $data);
    }
}