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
            $settingsModel = new Settings();
            $allSettings = $settingsModel->getAll();
            
            self::$settings = [];
            foreach ($allSettings as $setting) {
                self::$settings[$setting['setting_key']] = $setting['setting_value'];
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
        return self::get('store_phone');
    }
    
    /**
     * Obtém o email da loja
     */
    public static function getStoreEmail() {
        return self::get('store_email');
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
}
?>
