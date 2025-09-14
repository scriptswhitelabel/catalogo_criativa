<?php

class Settings {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Busca uma configuração específica por chave
     */
    public function get($key) {
        $result = $this->db->fetch("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
        return $result ? $result['setting_value'] : null;
    }
    
    /**
     * Busca todas as configurações
     */
    public function getAll() {
        return $this->db->fetchAll("SELECT * FROM settings ORDER BY setting_key");
    }
    
    /**
     * Atualiza uma configuração específica
     */
    public function update($key, $value) {
        $stmt = $this->db->query("UPDATE settings SET setting_value = ?, updated_at = CURRENT_TIMESTAMP WHERE setting_key = ?", [$value, $key]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Insere uma nova configuração
     */
    public function create($key, $value, $description = '') {
        $stmt = $this->db->query("INSERT INTO settings (setting_key, setting_value, description) VALUES (?, ?, ?)", [$key, $value, $description]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Atualiza múltiplas configurações
     */
    public function updateMultiple($settings) {
        $connection = $this->db->getConnection();
        $connection->beginTransaction();
        
        try {
            foreach ($settings as $key => $value) {
                $stmt = $this->db->query("UPDATE settings SET setting_value = ?, updated_at = CURRENT_TIMESTAMP WHERE setting_key = ?", [$value, $key]);
            }
            
            $connection->commit();
            return true;
        } catch (Exception $e) {
            $connection->rollback();
            return false;
        }
    }
    
    /**
     * Verifica se uma configuração existe
     */
    public function exists($key) {
        $result = $this->db->fetch("SELECT COUNT(*) as count FROM settings WHERE setting_key = ?", [$key]);
        return $result['count'] > 0;
    }
}
?>
