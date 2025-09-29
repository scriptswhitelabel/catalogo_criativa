<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-copyright"></i> Marcas</h2>
        <a class="btn btn-primary" href="<?= BASE_URL ?>?controller=admin&action=createBrand">
            <i class="fas fa-plus"></i> Nova Marca
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
                        <?php if (!empty($brands)): ?>
                            <?php foreach ($brands as $brand): ?>
                                <tr>
                                    <td><?= $brand['id'] ?></td>
                                    <td><?= htmlspecialchars($brand['name']) ?></td>
                                    <td><?= htmlspecialchars($brand['description']) ?></td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL ?>?controller=admin&action=editBrand&id=<?= $brand['id'] ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <a class="btn btn-sm btn-outline-danger" href="<?= BASE_URL ?>?controller=admin&action=deleteBrand&id=<?= $brand['id'] ?>" onclick="return confirm('Excluir esta marca?');">
                                            <i class="fas fa-trash"></i> Excluir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted">Nenhuma marca cadastrada.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>


