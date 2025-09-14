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
        $stmt = $this->db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['setting_value'] : null;
    }
    
    /**
     * Busca todas as configurações
     */
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM settings ORDER BY setting_key");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Atualiza uma configuração específica
     */
    public function update($key, $value) {
        $stmt = $this->db->prepare("UPDATE settings SET setting_value = ?, updated_at = CURRENT_TIMESTAMP WHERE setting_key = ?");
        return $stmt->execute([$value, $key]);
    }
    
    /**
     * Insere uma nova configuração
     */
    public function create($key, $value, $description = '') {
        $stmt = $this->db->prepare("INSERT INTO settings (setting_key, setting_value, description) VALUES (?, ?, ?)");
        return $stmt->execute([$key, $value, $description]);
    }
    
    /**
     * Atualiza múltiplas configurações
     */
    public function updateMultiple($settings) {
        $this->db->beginTransaction();
        
        try {
            foreach ($settings as $key => $value) {
                $stmt = $this->db->prepare("UPDATE settings SET setting_value = ?, updated_at = CURRENT_TIMESTAMP WHERE setting_key = ?");
                $stmt->execute([$value, $key]);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    /**
     * Verifica se uma configuração existe
     */
    public function exists($key) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        return $stmt->fetchColumn() > 0;
    }
}
?>
