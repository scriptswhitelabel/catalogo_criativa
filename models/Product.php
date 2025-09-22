<?php

class Product {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        $sql = "INSERT INTO products (name, brand_id, category_id, description, unit_price, package_price, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $data['name'],
            $data['brand_id'],
            $data['category_id'],
            $data['description'],
            $data['unit_price'],
            $data['package_price'] ?? null,
            $data['status'] ?? 'available'
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    public function findById($id) {
        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name 
                FROM products p 
                LEFT JOIN brands b ON p.brand_id = b.id 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name 
                FROM products p 
                LEFT JOIN brands b ON p.brand_id = b.id 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND p.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category_id'];
        }
        
        if (!empty($filters['brand_id'])) {
            $sql .= " AND p.brand_id = ?";
            $params[] = $filters['brand_id'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getAvailable() {
        return $this->getAll(['status' => 'available']);
    }
    
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }
        
        $params[] = $id;
        $sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE id = ?";
        
        return $this->db->query($sql, $params);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM products WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    public function addImage($productId, $imagePath, $isPrimary = false) {
        // Normalize boolean to integer to satisfy strict MySQL (0/1 instead of empty string)
        $isPrimaryInt = $isPrimary ? 1 : 0;
        $sql = "INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, ?)";
        return $this->db->query($sql, [$productId, $imagePath, $isPrimaryInt]);
    }
    
    public function getImages($productId) {
        $sql = "SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC";
        return $this->db->fetchAll($sql, [$productId]);
    }
    
    public function addVideo($productId, $videoPath = null, $videoUrl = null) {
        $sql = "INSERT INTO product_videos (product_id, video_path, video_url) VALUES (?, ?, ?)";
        return $this->db->query($sql, [$productId, $videoPath, $videoUrl]);
    }
    
    public function getVideos($productId) {
        $sql = "SELECT * FROM product_videos WHERE product_id = ?";
        return $this->db->fetchAll($sql, [$productId]);
    }
}
?>
