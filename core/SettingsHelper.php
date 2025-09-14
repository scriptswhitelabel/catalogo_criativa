<?php

/**
 * Helper para facilitar o acesso às configurações da loja
 */
class SettingsHelper {
    private static $settings = null;
    
    /**
     * Carrega todas as configurações uma única vez
     */
    private static function loadSettings() {
        if (self::$settings === null) {
            try {
                $settingsModel = new Settings();
                $allSettings = $settingsModel->getAll();
                
                self::$settings = [];
                foreach ($allSettings as $setting) {
                    self::$settings[$setting['setting_key']] = $setting['setting_value'];
                }
            } catch (Exception $e) {
                // Se houver erro, usar valores padrão
                self::$settings = [
                    'store_name' => 'Catálogo Criativa',
                    'store_logo' => '',
                    'store_phone' => '(11) 99999-9999',
                    'store_email' => 'contato@criativa.com',
                    'store_address' => ''
                ];
            }
        }
    }
    
    /**
     * Obtém uma configuração específica
     */
    public static function get($key, $default = '') {
        self::loadSettings();
        return self::$settings[$key] ?? $default;
    }
    
    /**
     * Obtém o nome da loja
     */
    public static function getStoreName() {
        return self::get('store_name', 'Catálogo Criativa');
    }
    
    /**
     * Obtém a logomarca da loja
     */
    public static function getStoreLogo() {
        return self::get('store_logo');
    }
    
    /**
     * Obtém o telefone da loja
     */
    public static function getStorePhone() {
        return self::get('store_phone', '(11) 99999-9999');
    }
    
    /**
     * Obtém o email da loja
     */
    public static function getStoreEmail() {
        return self::get('store_email', 'contato@criativa.com');
    }
    
    /**
     * Obtém o endereço da loja
     */
    public static function getStoreAddress() {
        return self::get('store_address');
    }
    
    /**
     * Verifica se existe uma logomarca
     */
    public static function hasLogo() {
        return !empty(self::getStoreLogo());
    }
    
    /**
     * Obtém a URL completa da logomarca
     */
    public static function getLogoUrl() {
        $logo = self::getStoreLogo();
        return $logo ? BASE_URL . $logo : '';
    }
    
    /**
     * Verifica se tem telefone configurado
     */
    public static function hasPhone() {
        return !empty(self::getStorePhone());
    }
    
    /**
     * Verifica se tem email configurado
     */
    public static function hasEmail() {
        return !empty(self::getStoreEmail());
    }
    
    /**
     * Verifica se tem endereço configurado
     */
    public static function hasAddress() {
        return !empty(self::getStoreAddress());
    }
}
?>
