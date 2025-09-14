<?php
session_start();

// Incluir configurações
require_once 'config.php';

// Autoloader simples
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

// Router simples
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Verificar se o controller existe
$controllerFile = 'controllers/' . ucfirst($controller) . 'Controller.php';
if (file_exists($controllerFile)) {
    $controllerClass = ucfirst($controller) . 'Controller';
    $controllerInstance = new $controllerClass();
    
    if (method_exists($controllerInstance, $action)) {
        $controllerInstance->$action();
    } else {
        echo "Ação não encontrada: $action";
    }
} else {
    echo "Controller não encontrado: $controller";
}
?>
