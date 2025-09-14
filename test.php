<?php
// Teste simples do PHP
echo "<h1>Teste PHP</h1>";
echo "<p>PHP está funcionando!</p>";
echo "<p>Versão: " . phpversion() . "</p>";
echo "<p>Data/Hora: " . date('Y-m-d H:i:s') . "</p>";

// Teste de conexão com banco
echo "<h2>Teste de Conexão com Banco</h2>";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=criativa", 'root', '');
    echo "<p style='color: green;'>✅ Conexão com banco OK!</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Erro na conexão: " . $e->getMessage() . "</p>";
}

echo "<p><a href='debug.php'>Executar diagnóstico completo</a></p>";
echo "<p><a href='index.php'>Tentar acessar o sistema</a></p>";
?>
