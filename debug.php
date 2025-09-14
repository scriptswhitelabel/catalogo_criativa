<?php
// Script de diagnóstico para identificar problemas
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnóstico do Sistema Criativa Loja</h1>";

// 1. Verificar versão do PHP
echo "<h2>1. Versão do PHP</h2>";
echo "Versão: " . phpversion() . "<br>";
if (version_compare(PHP_VERSION, '8.0.0') >= 0) {
    echo "✅ PHP 8.0+ detectado<br>";
} else {
    echo "❌ PHP 8.0+ necessário<br>";
}

// 2. Verificar extensões necessárias
echo "<h2>2. Extensões PHP</h2>";
$required_extensions = ['pdo', 'pdo_mysql', 'gd', 'mbstring', 'fileinfo'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext carregada<br>";
    } else {
        echo "❌ $ext não encontrada<br>";
    }
}

// 3. Verificar arquivos principais
echo "<h2>3. Arquivos do Sistema</h2>";
$required_files = [
    'config.php',
    'core/Database.php',
    'core/Auth.php',
    'core/Controller.php',
    'models/User.php',
    'models/Product.php',
    'controllers/HomeController.php',
    'controllers/AuthController.php'
];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "✅ $file existe<br>";
    } else {
        echo "❌ $file não encontrado<br>";
    }
}

// 4. Verificar permissões de diretórios
echo "<h2>4. Permissões de Diretórios</h2>";
$directories = ['uploads', 'uploads/products', 'uploads/videos'];
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "📁 Diretório $dir criado<br>";
    }
    
    if (is_writable($dir)) {
        echo "✅ $dir tem permissão de escrita<br>";
    } else {
        echo "❌ $dir sem permissão de escrita<br>";
    }
}

// 5. Testar conexão com banco de dados
echo "<h2>5. Conexão com Banco de Dados</h2>";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=criativa", 'root', '');
    echo "✅ Conexão com banco de dados estabelecida<br>";
    
    // Verificar tabelas
    $tables = ['users', 'products', 'categories', 'brands', 'orders', 'order_items', 'product_images', 'product_videos'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Tabela $table existe<br>";
        } else {
            echo "❌ Tabela $table não encontrada<br>";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . "<br>";
}

// 6. Testar autoloader
echo "<h2>6. Teste do Autoloader</h2>";
try {
    spl_autoload_register(function ($class) {
        $directories = ['controllers/', 'models/', 'core/'];
        
        foreach ($directories as $directory) {
            $file = $directory . $class . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    });
    
    // Testar carregamento de classes
    if (class_exists('Database')) {
        echo "✅ Classe Database carregada<br>";
    } else {
        echo "❌ Classe Database não encontrada<br>";
    }
    
    if (class_exists('HomeController')) {
        echo "✅ Classe HomeController carregada<br>";
    } else {
        echo "❌ Classe HomeController não encontrada<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro no autoloader: " . $e->getMessage() . "<br>";
}

// 7. Verificar configurações do servidor
echo "<h2>7. Configurações do Servidor</h2>";
echo "Servidor: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";

// 8. Verificar logs de erro
echo "<h2>8. Logs de Erro</h2>";
$error_log = ini_get('error_log');
if ($error_log && file_exists($error_log)) {
    echo "Log de erro: $error_log<br>";
    $errors = file_get_contents($error_log);
    if (strlen($errors) > 0) {
        echo "<pre>" . htmlspecialchars(substr($errors, -1000)) . "</pre>";
    } else {
        echo "Nenhum erro recente encontrado<br>";
    }
} else {
    echo "Log de erro não configurado ou não encontrado<br>";
}

echo "<hr>";
echo "<h2>Próximos Passos</h2>";
echo "<ol>";
echo "<li>Se houver erros ❌, corrija-os primeiro</li>";
echo "<li>Execute o script database/schema.sql no MySQL</li>";
echo "<li>Configure as credenciais do banco em config.php</li>";
echo "<li>Configure a URL base em config.php</li>";
echo "<li>Teste o acesso ao sistema</li>";
echo "</ol>";

echo "<p><a href='index.php'>Tentar acessar o sistema</a></p>";
?>
