<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-shopping-cart"></i> Meus Pedidos</h2>
                <a href="<?= BASE_URL ?>?controller=client&action=dashboard" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
    
    <?php if (empty($orders)): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Você ainda não realizou nenhum pedido.
            <br><br>
            <a href="<?= BASE_URL ?>" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Começar a Comprar
            </a>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
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
