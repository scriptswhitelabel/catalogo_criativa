<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    
                    <h2 class="text-success">Pedido Realizado com Sucesso!</h2>
                    <p class="lead">Seu pedido foi registrado e será processado em breve.</p>
                    
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Informações do Pedido</h5>
                        <p><strong>Número do Pedido:</strong> #<?= $order['id'] ?></p>
                        <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                        <p><strong>Total:</strong> R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-warning"><?= ucfirst($order['status']) ?></span>
                        </p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Itens do Pedido</h5>
                            <?php foreach ($items as $item): ?>
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span><?= htmlspecialchars($item['product_name']) ?></span>
                                    <span>R$ <?= number_format($item['total_price'], 2, ',', '.') ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Próximos Passos</h5>
                            <ol class="text-start">
                                <li>Envie o pedido via WhatsApp</li>
                                <li>Aguarde a confirmação</li>
                                <li>Combine a forma de pagamento</li>
                                <li>Acompanhe o status do pedido</li>
                            </ol>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="<?= BASE_URL ?>?controller=cart&action=whatsapp" class="btn btn-success btn-lg me-3" target="_blank">
                            <i class="fab fa-whatsapp"></i> Enviar via WhatsApp
                        </a>
                        
                        <a href="<?= BASE_URL ?>?controller=client&action=orders" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> Meus Pedidos
                        </a>
                    </div>
                    
                    <div class="mt-4">
                        <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-home"></i> Voltar ao Início
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
