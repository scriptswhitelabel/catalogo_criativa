<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-tags"></i> Categorias</h2>
        <a class="btn btn-primary" href="<?= BASE_URL ?>?controller=admin&action=createCategory">
            <i class="fas fa-plus"></i> Nova Categoria
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= $category['id'] ?></td>
                                    <td><?= htmlspecialchars($category['name']) ?></td>
                                    <td><?= htmlspecialchars($category['description']) ?></td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL ?>?controller=admin&action=editCategory&id=<?= $category['id'] ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <a class="btn btn-sm btn-outline-danger" href="<?= BASE_URL ?>?controller=admin&action=deleteCategory&id=<?= $category['id'] ?>" onclick="return confirm('Excluir esta categoria?');">
                                            <i class="fas fa-trash"></i> Excluir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted">Nenhuma categoria cadastrada.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>


