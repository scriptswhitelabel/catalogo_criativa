<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-cog"></i> Configurações da Loja</h2>
            <p class="text-muted">Gerencie as informações básicas da sua loja</p>
        </div>
    </div>
    
    <!-- Mensagens de feedback -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-store"></i> Informações da Loja</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>?controller=admin&action=updateSettings" enctype="multipart/form-data">
                        
                        <!-- Nome da Loja -->
                        <div class="mb-3">
                            <label for="store_name" class="form-label">
                                <i class="fas fa-signature"></i> Nome da Loja
                            </label>
                            <input type="text" class="form-control" id="store_name" name="store_name" 
                                   value="<?= htmlspecialchars($settings['store_name'] ?? '') ?>" 
                                   placeholder="Digite o nome da sua loja">
                        </div>
                        
                        <!-- Logomarca -->
                        <div class="mb-3">
                            <label for="store_logo" class="form-label">
                                <i class="fas fa-image"></i> Logomarca da Loja
                            </label>
                            <input type="file" class="form-control" id="store_logo" name="store_logo" 
                                   accept="image/*">
                            <div class="form-text">
                                Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB
                            </div>
                            
                            <!-- Preview da logomarca atual -->
                            <?php if (!empty($settings['store_logo'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted">Logomarca atual:</small><br>
                                    <img src="<?= BASE_URL . $settings['store_logo'] ?>" 
                                         alt="Logomarca atual" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px; max-height: 100px;">
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Telefone -->
                        <div class="mb-3">
                            <label for="store_phone" class="form-label">
                                <i class="fas fa-phone"></i> Telefone de Contato
                            </label>
                            <input type="tel" class="form-control" id="store_phone" name="store_phone" 
                                   value="<?= htmlspecialchars($settings['store_phone'] ?? '') ?>" 
                                   placeholder="(11) 99999-9999">
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="store_email" class="form-label">
                                <i class="fas fa-envelope"></i> Email de Contato
                            </label>
                            <input type="email" class="form-control" id="store_email" name="store_email" 
                                   value="<?= htmlspecialchars($settings['store_email'] ?? '') ?>" 
                                   placeholder="contato@sualoja.com">
                        </div>
                        
                        <!-- Endereço -->
                        <div class="mb-3">
                            <label for="store_address" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Endereço da Loja
                            </label>
                            <textarea class="form-control" id="store_address" name="store_address" 
                                      rows="3" placeholder="Digite o endereço completo da sua loja"><?= htmlspecialchars($settings['store_address'] ?? '') ?></textarea>
                        </div>
                        
                        <!-- Botões -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>?controller=admin&action=dashboard" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Painel lateral com informações -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Informações</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb"></i> Dicas:</h6>
                        <ul class="mb-0">
                            <li>Use uma logomarca de alta qualidade</li>
                            <li>Mantenha as informações de contato atualizadas</li>
                            <li>O endereço será exibido para os clientes</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Importante:</h6>
                        <p class="mb-0">
                            As alterações serão aplicadas imediatamente em todo o sistema.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Preview das configurações -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-eye"></i> Preview</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <?php if (!empty($settings['store_logo'])): ?>
                            <img src="<?= BASE_URL . $settings['store_logo'] ?>" 
                                 alt="Logomarca" 
                                 class="img-fluid mb-2" 
                                 style="max-height: 60px;">
                        <?php endif; ?>
                        
                        <h6><?= htmlspecialchars($settings['store_name'] ?? 'Nome da Loja') ?></h6>
                        
                        <?php if (!empty($settings['store_phone'])): ?>
                            <p class="mb-1">
                                <i class="fas fa-phone"></i> <?= htmlspecialchars($settings['store_phone']) ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['store_email'])): ?>
                            <p class="mb-1">
                                <i class="fas fa-envelope"></i> <?= htmlspecialchars($settings['store_email']) ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['store_address'])): ?>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($settings['store_address']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Preview da imagem antes do upload
document.getElementById('store_logo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Criar ou atualizar preview
            let preview = document.getElementById('logo-preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.id = 'logo-preview';
                preview.className = 'mt-2';
                preview.innerHTML = '<small class="text-muted">Nova logomarca:</small><br>';
                document.getElementById('store_logo').parentNode.appendChild(preview);
            }
            
            preview.innerHTML = '<small class="text-muted">Nova logomarca:</small><br>' +
                               '<img src="' + e.target.result + '" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 100px;">';
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php require_once 'views/layout/footer.php'; ?>
