<?php
// Configurações para produção
error_reporting(0);
ini_set('display_errors', 0);

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'criativa');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configurações gerais - AJUSTE AQUI PARA SEU DOMÍNIO
define('BASE_URL', 'https://criativa.ultrawhats.com.br');
define('UPLOAD_PATH', 'uploads/');

// Configurações do WhatsApp
define('WHATSAPP_NUMBER', '5511999999999'); // Substitua pelo número real

// Configurações de upload
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('ALLOWED_VIDEO_TYPES', ['mp4', 'avi', 'mov']);

// Configurações de segurança
define('SESSION_TIMEOUT', 3600); // 1 hora
define('PASSWORD_MIN_LENGTH', 6);

// Configurações de paginação
define('ITEMS_PER_PAGE', 12);
?>
