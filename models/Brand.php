<?php

class Brand {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM brands ORDER BY name";
        return $this->db->fetchAll($sql);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM brands WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO brands (name, description) VALUES (?, ?)";
        return $this->db->query($sql, [$data['name'], $data['description']]);
    }
}
?>
