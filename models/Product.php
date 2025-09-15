<?php
require_once __DIR__ . '/../core/Database.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Retorna todos os produtos
     */
    public function getAll() {
        $sql = "SELECT * FROM products ORDER BY id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Retorna um produto pelo ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        return $this->db->query($sql, [$id])->fetch();
    }

    /**
     * Adiciona um novo produto
     */
    public function add($name, $description, $price, $stock) {
        $sql = "INSERT INTO products (name, description, price, stock, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        return $this->db->query($sql, [$name, $description, $price, $stock]);
    }

    /**
     * Atualiza um produto existente
     */
    public function update($id, $name, $description, $price, $stock) {
        $sql = "UPDATE products 
                SET name = ?, description = ?, price = ?, stock = ? 
                WHERE id = ?";
        return $this->db->query($sql, [$name, $description, $price, $stock, $id]);
    }

    /**
     * Remove um produto
     */
    public function delete($id) {
        // Exclui imagens associadas
        $this->db->query("DELETE FROM product_images WHERE product_id = ?", [$id]);
        // Exclui produto
        return $this->db->query("DELETE FROM products WHERE id = ?", [$id]);
    }

    /**
     * Retorna todas as imagens de um produto
     */
    public function getImages($productId) {
        $sql = "SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC";
        return $this->db->query($sql, [$productId])->fetchAll();
    }

    /**
     * Adiciona imagem ao produto
     */
    public function addImage($productId, $imagePath, $isPrimary = 0) {
        // Garante que seja 0 ou 1
        $isPrimary = (int) (empty($isPrimary) ? 0 : $isPrimary);

        // Se for marcada como principal, remove principal anterior
        if ($isPrimary === 1) {
            $this->db->query("UPDATE product_images SET is_primary = 0 WHERE product_id = ?", [$productId]);
        }

        $sql = "INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, ?)";
        return $this->db->query($sql, [$productId, $imagePath, $isPrimary]);
    }

    /**
     * Define imagem como principal
     */
    public function setPrimaryImage($imageId, $productId) {
        // Remove principal anterior
        $this->db->query("UPDATE product_images SET is_primary = 0 WHERE product_id = ?", [$productId]);
        // Define nova principal
        return $this->db->query("UPDATE product_images SET is_primary = 1 WHERE id = ?", [$imageId]);
    }

    /**
     * Remove imagem do produto
     */
    public function deleteImage($imageId) {
        return $this->db->query("DELETE FROM product_images WHERE id = ?", [$imageId]);
    }
}
