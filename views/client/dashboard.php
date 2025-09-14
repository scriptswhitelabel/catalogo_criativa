<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-user"></i> Minha Conta</h2>
            <p class="text-muted">Bem-vindo, <?= htmlspecialchars($user['name']) ?>!</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-user-circle"></i> Meus Dados</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nome:</strong> <?= htmlspecialchars($user['name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Telefone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Não informado') ?></p>
                    <p><strong>Membro desde:</strong> <?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
                    
                    <div class="d-grid">
                        <a href="<?= BASE_URL ?>?controller=client&action=profile" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Editar Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-shopping-cart"></i> Meus Pedidos</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($orders)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Você ainda não realizou nenhum pedido.
                            <br><br>
                            <a href="<?= BASE_URL ?>" class="btn btn-primary">
                                <i class="fas fa-shopping-bag"></i> Começar a Comprar
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Pedido</th>
                                        <th>Data</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>#<?= $order['id'] ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                            <td>R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></td>
                                            <td>
                                                <?php
                                                $statusClass = '';
                                                switch ($order['status']) {
                                                    case 'pending':
                                                        $statusClass = 'bg-warning';
                                                        break;
                                                    case 'approved':
                                                        $statusClass = 'bg-info';
                                                        break;
                                                    case 'in_progress':
                                                        $statusClass = 'bg-primary';
                                                        break;
                                                    case 'completed':
                                                        $statusClass = 'bg-success';
                                                        break;
                                                    case 'cancelled':
                                                        $statusClass = 'bg-danger';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $statusClass ?>"><?= ucfirst($order['status']) ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= BASE_URL ?>?controller=client&action=orderDetail&id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="<?= BASE_URL ?>?controller=client&action=orders" class="btn btn-outline-primary">
                                <i class="fas fa-list"></i> Ver Todos os Pedidos
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
