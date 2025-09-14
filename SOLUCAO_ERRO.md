# Solução para o Erro "Internal Server Error"

## 🔍 Diagnóstico do Problema

O erro "Internal Server Error" geralmente indica problemas de configuração ou código PHP. Aqui estão as principais causas e soluções:

## 📋 Passos para Resolver

### 1. **Execute o Diagnóstico**
Acesse: `https://criativa.ultrawhats.com.br/debug.php`

Este script irá verificar:
- ✅ Versão do PHP
- ✅ Extensões necessárias
- ✅ Arquivos do sistema
- ✅ Permissões de diretórios
- ✅ Conexão com banco de dados
- ✅ Configurações do servidor

### 2. **Verifique o Banco de Dados**
```sql
-- Execute este script no MySQL
CREATE DATABASE IF NOT EXISTS criativa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE criativa;

-- Execute o resto do script database/schema.sql
```

### 3. **Configure as Credenciais**
Edite o arquivo `config.php` com as credenciais corretas:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'criativa');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
define('BASE_URL', 'https://criativa.ultrawhats.com.br');
```

### 4. **Verifique Permissões**
```bash
chmod 755 uploads/
chmod 755 uploads/products/
chmod 755 uploads/videos/
```

### 5. **Teste Básico**
Acesse: `https://criativa.ultrawhats.com.br/test.php`

## 🚨 Problemas Comuns

### **Problema 1: Banco de Dados**
- ❌ Banco não existe
- ❌ Credenciais incorretas
- ❌ Tabelas não criadas

**Solução:**
```sql
CREATE DATABASE criativa;
-- Execute database/schema.sql
```

### **Problema 2: Permissões**
- ❌ Diretórios sem permissão de escrita
- ❌ Arquivos protegidos

**Solução:**
```bash
chmod 755 uploads/
chmod 644 *.php
```

### **Problema 3: PHP**
- ❌ Versão muito antiga
- ❌ Extensões faltando

**Solução:**
- Atualize para PHP 8.0+
- Instale extensões: pdo, pdo_mysql, gd, mbstring

### **Problema 4: Configuração**
- ❌ URL base incorreta
- ❌ Caminhos relativos

**Solução:**
```php
define('BASE_URL', 'https://criativa.ultrawhats.com.br');
```

## 🔧 Arquivos de Configuração

### **Para Desenvolvimento:**
Use `config.php`

### **Para Produção:**
Use `config_production.php` (com error_reporting desabilitado)

## 📞 Suporte

Se o problema persistir:

1. **Execute o diagnóstico:** `debug.php`
2. **Verifique os logs de erro do servidor**
3. **Teste com:** `test.php`
4. **Verifique as permissões dos arquivos**

## ✅ Checklist de Verificação

- [ ] PHP 8.0+ instalado
- [ ] Extensões PDO, GD, mbstring instaladas
- [ ] Banco de dados 'criativa' criado
- [ ] Tabelas criadas (execute schema.sql)
- [ ] Credenciais corretas em config.php
- [ ] URL base configurada corretamente
- [ ] Permissões de upload configuradas
- [ ] Arquivos .htaccess funcionando

## 🎯 Próximos Passos

1. Execute `debug.php` para diagnóstico
2. Corrija os problemas identificados
3. Teste com `test.php`
4. Acesse o sistema principal

---

**Importante:** Sempre faça backup antes de fazer alterações!
