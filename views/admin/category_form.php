<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-tag"></i>
                        <?= isset($category) ? 'Editar Categoria' : 'Nova Categoria' ?>
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
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($data['name'] ?? ($category['name'] ?? '')) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($data['description'] ?? ($category['description'] ?? '')) ?></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>?controller=admin&action=categories" class="btn btn-outline-secondary">
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


