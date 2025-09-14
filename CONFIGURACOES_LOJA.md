# Configurações da Loja

## Funcionalidade Implementada

Foi adicionada uma nova funcionalidade no Painel Administrativo que permite gerenciar as configurações básicas da loja, incluindo:

- **Logomarca da loja** - Upload de imagem
- **Telefone de contato** - Número para contato
- **Email de contato** - Email para contato
- **Nome da loja** - Nome que aparece no site
- **Endereço da loja** - Endereço físico da loja

## Como Acessar

1. Faça login como administrador
2. No dashboard administrativo, clique em "Configurações da Loja"
3. Ou acesse diretamente: `?controller=admin&action=settings`

## Funcionalidades

### Upload de Logomarca
- Suporta formatos: JPG, PNG, GIF
- Tamanho máximo: 2MB
- Preview da imagem antes do upload
- Substitui automaticamente a logomarca anterior

### Informações de Contato
- Campos para telefone e email
- Validação de formato de email
- Informações são exibidas no footer do site

### Nome da Loja
- Aparece no título das páginas
- Substitui o nome padrão "Criativa Loja"

### Endereço
- Campo de texto livre para endereço completo
- Exibido no footer quando preenchido

## Arquivos Criados/Modificados

### Novos Arquivos:
- `models/Settings.php` - Modelo para gerenciar configurações
- `views/admin/settings.php` - Interface de configurações
- `core/SettingsHelper.php` - Helper para facilitar uso das configurações
- `uploads/.htaccess` - Proteção do diretório de uploads

### Arquivos Modificados:
- `database/schema.sql` - Adicionada tabela `settings`
- `controllers/AdminController.php` - Métodos para gerenciar configurações
- `views/admin/dashboard.php` - Link para configurações
- `views/layout/header.php` - Usa configurações da loja
- `views/layout/footer.php` - Exibe informações de contato

## Banco de Dados

### Tabela `settings`
```sql
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Configurações Padrão
- `store_logo` - Logomarca da loja
- `store_phone` - Telefone de contato
- `store_email` - Email de contato
- `store_name` - Nome da loja
- `store_address` - Endereço da loja

## Como Usar no Código

### Usando o SettingsHelper:
```php
// Obter nome da loja
$storeName = SettingsHelper::getStoreName();

// Obter logomarca
$logoUrl = SettingsHelper::getLogoUrl();

// Verificar se tem logomarca
if (SettingsHelper::hasLogo()) {
    // Exibir logomarca
}

// Obter informações de contato
$phone = SettingsHelper::getStorePhone();
$email = SettingsHelper::getStoreEmail();
$address = SettingsHelper::getStoreAddress();
```

### Usando o Model Settings diretamente:
```php
$settings = new Settings();

// Obter uma configuração específica
$logo = $settings->get('store_logo');

// Atualizar uma configuração
$settings->update('store_name', 'Novo Nome');

// Obter todas as configurações
$allSettings = $settings->getAll();
```

## Segurança

- Upload de arquivos protegido por .htaccess
- Validação de tipos de arquivo
- Sanitização de dados de entrada
- Proteção contra execução de PHP nos uploads

## Próximos Passos

Para expandir esta funcionalidade, você pode:

1. Adicionar mais campos de configuração
2. Implementar backup/restore de configurações
3. Adicionar validação mais robusta
4. Implementar cache para melhor performance
5. Adicionar configurações por tema/cor
