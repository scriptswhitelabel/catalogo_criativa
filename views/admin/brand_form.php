<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-copyright"></i>
                        <?= isset($brand) ? 'Editar Marca' : 'Nova Marca' ?>
                    </h5>
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
                            <label for="name" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($data['name'] ?? ($brand['name'] ?? '')) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($data['description'] ?? ($brand['description'] ?? '')) ?></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>?controller=admin&action=brands" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>


