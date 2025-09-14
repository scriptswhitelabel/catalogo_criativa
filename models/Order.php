<?php

class Order {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        $sql = "INSERT INTO orders (user_id, total_amount, status, notes) VALUES (?, ?, ?, ?)";
        $params = [
            $data['user_id'],
            $data['total_amount'],
            $data['status'] ?? 'pending',
            $data['notes'] ?? null
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    public function addItem($orderId, $productId, $quantity, $unitPrice, $totalPrice) {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)";
        return $this->db->query($sql, [$orderId, $productId, $quantity, $unitPrice, $totalPrice]);
    }
    
    public function findById($id) {
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function getItems($orderId) {
        $sql = "SELECT oi.*, p.name as product_name, p.description as product_description 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?";
        return $this->db->fetchAll($sql, [$orderId]);
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['user_id'])) {
            $sql .= " AND o.user_id = ?";
            $params[] = $filters['user_id'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND o.status = ?";
            $params[] = $filters['status'];
        }
        
        $sql .= " ORDER BY o.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function updateStatus($id, $status) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        return $this->db->query($sql, [$status, $id]);
    }
    
    public function getByUser($userId) {
        return $this->getAll(['user_id' => $userId]);
    }
}
?>
