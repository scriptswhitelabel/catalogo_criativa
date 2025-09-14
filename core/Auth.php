<?php

class Auth {
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public static function isAdmin() {
        return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . '?controller=auth&action=login');
            exit;
        }
    }
    
    public static function requireAdmin() {
        self::requireLogin();
        if (!self::isAdmin()) {
            header('Location: ' . BASE_URL . '?controller=home');
            exit;
        }
    }
    
    public static function login($userId, $userType = 'client') {
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_type'] = $userType;
    }
    
    public static function logout() {
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }
    
    public static function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    public static function getUserType() {
        return $_SESSION['user_type'] ?? null;
    }
}
?>
