<?php
namespace app\models;

abstract class BaseModel {
    protected $db;
    protected $f3;
    
    public function __construct() {
        $this->f3 = \Base::instance();
        $this->db = $this->f3->get('DB');
    }
    
    protected function getDb() {
        if (!$this->db) {
            $this->db = $this->f3->get('DB');
        }
        return $this->db;
    }
}