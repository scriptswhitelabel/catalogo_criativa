<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-shopping-cart"></i> Pedido #<?= $order['id'] ?></h2>
                <a href="<?= BASE_URL ?>?controller=client&action=orders" class="btn btn-outline-secondary">
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
                    <h5><i class="fas fa-info-circle"></i> Informações do Pedido</h5>
                </div>
                <div class="card-body">
                    <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                    <p><strong>Status:</strong> 
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
                    <h5><i class="fas fa-info-circle"></i> Status do Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item <?= in_array($order['status'], ['pending', 'approved', 'in_progress', 'completed']) ? 'active' : '' ?>">
                            <i class="fas fa-clock"></i>
                            <span>Pedido Realizado</span>
                        </div>
                        <div class="timeline-item <?= in_array($order['status'], ['approved', 'in_progress', 'completed']) ? 'active' : '' ?>">
                            <i class="fas fa-check"></i>
                            <span>Pedido Aprovado</span>
                        </div>
                        <div class="timeline-item <?= in_array($order['status'], ['in_progress', 'completed']) ? 'active' : '' ?>">
                            <i class="fas fa-cog"></i>
                            <span>Em Andamento</span>
                        </div>
                        <div class="timeline-item <?= $order['status'] === 'completed' ? 'active' : '' ?>">
                            <i class="fas fa-check-circle"></i>
                            <span>Finalizado</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if ($order['status'] === 'pending'): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fab fa-whatsapp"></i> Enviar via WhatsApp</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Envie este pedido via WhatsApp para confirmação.</p>
                        <a href="<?= BASE_URL ?>?controller=cart&action=whatsapp" class="btn btn-success w-100" target="_blank">
                            <i class="fab fa-whatsapp"></i> Enviar Pedido
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 30px;
}

.timeline-item i {
    position: absolute;
    left: -25px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
}

.timeline-item.active i {
    background: #007bff;
    color: white;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: -16px;
    top: 20px;
    width: 2px;
    height: 20px;
    background: #dee2e6;
}

.timeline-item.active:not(:last-child)::after {
    background: #007bff;
}
</style>

<?php require_once 'views/layout/footer.php'; ?>
