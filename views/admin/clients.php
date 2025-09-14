<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-users"></i> Gerenciar Clientes</h2>
        </div>
    </div>
    
    <?php if (empty($clients)): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Nenhum cliente cadastrado ainda.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Data de Cadastro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td>#<?= $client['id'] ?></td>
                                    <td><?= htmlspecialchars($client['name']) ?></td>
                                    <td><?= htmlspecialchars($client['email']) ?></td>
                                    <td><?= htmlspecialchars($client['phone'] ?? 'Não informado') ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($client['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?controller=admin&action=clientDetail&id=<?= $client['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Ver Detalhes
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layout/footer.php'; ?>
