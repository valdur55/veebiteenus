<?php
abstract class Entity {
    public $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function getData(){
        return $this->data;
    }

}