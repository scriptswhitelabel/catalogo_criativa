# Teste das Configurações da Loja

## Como Testar

1. **Acesse o painel administrativo:**
   - URL: `?controller=admin&action=dashboard`
   - Faça login como administrador

2. **Acesse as configurações:**
   - Clique no botão "Configurações da Loja"
   - Ou acesse diretamente: `?controller=admin&action=settings`

3. **Teste os campos:**
   - Nome da Loja
   - Upload de Logomarca (imagem)
   - Telefone de Contato
   - Email de Contato
   - Endereço da Loja

4. **Verifique se salva:**
   - Preencha os campos
   - Clique em "Salvar Configurações"
   - Deve aparecer mensagem de sucesso

## Se Houver Erro

### Erro de Método Não Encontrado:
- Verifique se o arquivo `models/Settings.php` existe
- Verifique se o arquivo `controllers/AdminController.php` foi atualizado

### Erro de Banco de Dados:
- Execute o SQL da tabela `settings` no banco
- Verifique se as configurações padrão foram inseridas

### Erro de Upload:
- Verifique se a pasta `uploads/settings/` existe
- Verifique permissões da pasta (deve permitir escrita)

## SQL para Criar a Tabela

```sql
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO settings (setting_key, setting_value, description) VALUES 
('store_logo', '', 'Logomarca da loja'),
('store_phone', '', 'Telefone de contato da loja'),
('store_email', '', 'Email de contato da loja'),
('store_name', 'Catálogo Criativa', 'Nome da loja'),
('store_address', '', 'Endereço da loja');
```

## Funcionalidades Implementadas

✅ **Interface de Configurações**
- Formulário completo para editar configurações
- Preview das configurações em tempo real
- Validação de campos
- Upload de logomarca com preview

✅ **Banco de Dados**
- Tabela `settings` criada
- Configurações padrão inseridas
- Métodos para buscar e atualizar

✅ **Controller**
- Método `settings()` para exibir a página
- Método `updateSettings()` para salvar alterações
- Upload de arquivos integrado

✅ **Segurança**
- Validação de tipos de arquivo
- Sanitização de dados
- Proteção do diretório de uploads

## Próximos Passos

Após testar e confirmar que está funcionando:

1. **Integrar com o site:**
   - Usar as configurações no header/footer
   - Exibir logomarca personalizada
   - Mostrar informações de contato

2. **Melhorias:**
   - Cache das configurações
   - Validação mais robusta
   - Backup/restore de configurações
