<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-user-edit"></i> Editar Perfil</h2>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Informações Pessoais</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo *</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($data['name'] ?? $user['name']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Nome da Empresa</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" value="<?= htmlspecialchars($data['company_name'] ?? ($user['company_name'] ?? '')) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="cnpj" class="form-label">CNPJ</label>
                            <input type="text" class="form-control" id="cnpj" name="cnpj" value="<?= htmlspecialchars($data['cnpj'] ?? ($user['cnpj'] ?? '')) ?>" placeholder="00.000.000/0000-00">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($data['email'] ?? $user['email']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($data['phone'] ?? $user['phone']) ?>" placeholder="(11) 99999-9999">
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= BASE_URL ?>?controller=client&action=dashboard" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-shield-alt"></i> Segurança</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Para alterar sua senha, entre em contato conosco.</p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Dica de Segurança:</strong><br>
                        Mantenha seus dados sempre atualizados para receber informações importantes sobre seus pedidos.
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-history"></i> Histórico</h5>
                </div>
                <div class="card-body">
                    <p><strong>Membro desde:</strong> <?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
                    <p><strong>Última atualização:</strong> <?= date('d/m/Y H:i', strtotime($user['updated_at'])) ?></p>
                    
                    <div class="d-grid">
                        <a href="<?= BASE_URL ?>?controller=client&action=orders" class="btn btn-outline-primary">
                            <i class="fas fa-shopping-cart"></i> Ver Meus Pedidos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
