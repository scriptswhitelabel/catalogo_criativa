# Criativa Loja - Sistema de E-commerce

Sistema completo de e-commerce desenvolvido em PHP com arquitetura MVC, incluindo painel administrativo, área do cliente e loja online.

## 🚀 Funcionalidades

### Painel Administrativo
- ✅ Login com autenticação segura
- ✅ Dashboard com estatísticas
- ✅ CRUD completo de produtos
- ✅ Upload de múltiplas imagens
- ✅ Upload de vídeos e URLs
- ✅ Gerenciamento de categorias e marcas
- ✅ Gerenciamento de clientes
- ✅ Gerenciamento de pedidos
- ✅ Controle de status dos pedidos

### Área do Cliente
- ✅ Cadastro e login de clientes
- ✅ Painel pessoal com dados
- ✅ Histórico de pedidos
- ✅ Visualização detalhada de pedidos
- ✅ Edição de perfil

### Loja Online
- ✅ Catálogo de produtos responsivo
- ✅ Filtros por categoria e marca
- ✅ Busca de produtos
- ✅ Página de detalhes do produto
- ✅ Carrinho de compras
- ✅ Finalização de pedidos
- ✅ Integração com WhatsApp

## 🛠️ Tecnologias Utilizadas

- **Backend:** PHP 8+
- **Banco de Dados:** MySQL/MariaDB
- **Frontend:** Bootstrap 5, Font Awesome
- **Arquitetura:** MVC (Model-View-Controller)
- **Segurança:** Prepared Statements, Password Hash

## 📋 Requisitos

- PHP 8.0 ou superior
- MySQL 5.7 ou MariaDB 10.3+
- Servidor web (Apache/Nginx)
- Extensões PHP: PDO, GD, mbstring

## 🔧 Instalação

1. **Clone o repositório:**
   ```bash
   git clone <url-do-repositorio>
   cd criativaLoja
   ```

2. **Configure o banco de dados:**
   - Crie um banco de dados MySQL
   - Execute o script `database/schema.sql`
   - Configure as credenciais em `config.php`

3. **Configure o servidor web:**
   - Configure o DocumentRoot para a pasta do projeto
   - Certifique-se de que o mod_rewrite está habilitado

4. **Configure as permissões:**
   ```bash
   chmod 755 uploads/
   chmod 755 uploads/products/
   chmod 755 uploads/videos/
   ```

5. **Configurar .env (Whaticket):**
   Crie um arquivo `.env` na raiz do projeto com:
   ```
   WHATICKET_API="https://apiweb.ultrawhats.com.br"
   WHATICKET_TOKEN="ultranotificacoes"
   WHATICKET_NUMBER="5518998020650"  # Número E.164 (sem +)
   WHATICKET_USER_ID=""   # Opcional
   WHATICKET_QUEUE_ID="15"  # Opcional
   ```

6. **Acesse o sistema:**
   - URL: `http://localhost/criativaLoja`
   - Admin: `admin@criativa.com` / `password`

## 📁 Estrutura do Projeto

```
criativaLoja/
├── controllers/          # Controladores MVC
├── models/              # Modelos de dados
├── views/               # Templates de visualização
├── core/                # Classes principais
├── database/            # Scripts de banco
├── uploads/             # Arquivos enviados
├── config.php           # Configurações
├── index.php            # Ponto de entrada
└── README.md            # Documentação
```

## 🔐 Segurança

- Senhas criptografadas com `password_hash()`
- Prepared statements para evitar SQL Injection
- Validação de entrada de dados
- Headers de segurança configurados
- Proteção contra upload de arquivos maliciosos

## 📱 Responsividade

O sistema é totalmente responsivo e funciona perfeitamente em:
- Desktop
- Tablet
- Smartphone

## 🎨 Interface

- Design moderno com Bootstrap 5
- Ícones Font Awesome
- Interface intuitiva e amigável
- Tema consistente em todas as páginas

## 📞 Suporte

Para suporte e dúvidas:
- Email: contato@criativa.com
- WhatsApp: (11) 99999-9999

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo LICENSE para mais detalhes.

## 🔄 Atualizações Futuras

- [ ] Sistema de cupons de desconto
- [ ] Relatórios de vendas
- [ ] Integração com gateway de pagamento
- [ ] Sistema de avaliações de produtos
- [ ] Notificações por email
- [ ] API REST para integrações

---

**Desenvolvido com ❤️ para Criativa Loja**
