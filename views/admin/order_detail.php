<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-shopping-cart"></i> Detalhes do Pedido #<?= $order['id'] ?></h2>
                <a href="<?= BASE_URL ?>?controller=admin&action=orders" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-list"></i> Itens do Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Valor Unitário</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                            <?php if ($item['product_description']): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars(substr($item['product_description'], 0, 100)) ?>...</small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>R$ <?= number_format($item['unit_price'], 2, ',', '.') ?></td>
                                        <td><strong>R$ <?= number_format($item['total_price'], 2, ',', '.') ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <th colspan="3">Total do Pedido:</th>
                                    <th>R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-user"></i> Informações do Cliente</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nome:</strong> <?= htmlspecialchars($order['user_name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($order['user_email']) ?></p>
                    <p><strong>Telefone:</strong> <?= htmlspecialchars($order['user_phone'] ?? 'Não informado') ?></p>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Informações do Pedido</h5>
                </div>
                <div class="card-body">
                    <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                    <p><strong>Status Atual:</strong> 
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
                    </p>
                    
                    <?php if ($order['notes']): ?>
                        <p><strong>Observações:</strong><br><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-cog"></i> Alterar Status</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>?controller=admin&action=updateOrderStatus">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Novo Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pendente</option>
                                <option value="approved" <?= $order['status'] === 'approved' ? 'selected' : '' ?>>Aprovado</option>
                                <option value="in_progress" <?= $order['status'] === 'in_progress' ? 'selected' : '' ?>>Em Andamento</option>
                                <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Finalizado</option>
                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Atualizar Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
