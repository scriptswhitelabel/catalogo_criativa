<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-credit-card"></i> Finalizar Pedido</h2>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-shopping-cart"></i> Itens do Pedido</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="row align-items-center mb-3 pb-3 border-bottom">
                            <div class="col-md-2">
                                <?php 
                                $productModel = new Product();
                                $images = $productModel->getImages($item['product']['id']);
                                $primaryImage = null;
                                foreach ($images as $image) {
                                    if ($image['is_primary']) {
                                        $primaryImage = $image;
                                        break;
                                    }
                                }
                                if (!$primaryImage && !empty($images)) {
                                    $primaryImage = $images[0];
                                }
                                ?>
                                
                                <?php if ($primaryImage): ?>
                                    <img src="<?= BASE_URL ?>/uploads/products/<?= $primaryImage['image_path'] ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($item['product']['name']) ?>">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 60px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <h6><?= htmlspecialchars($item['product']['name']) ?></h6>
                                <small class="text-muted">
                                    <?= htmlspecialchars($item['product']['brand_name'] ?? 'Sem marca') ?>
                                </small>
                            </div>
                            
                            <div class="col-md-2">
                                <span>Qtd: <?= $item['quantity'] ?></span>
                            </div>
                            
                            <div class="col-md-2">
                                <strong>R$ <?= number_format($item['total'], 2, ',', '.') ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-comment"></i> Observações</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>?controller=cart&action=finish">
                        <div class="mb-3">
                            <textarea class="form-control" name="notes" rows="3" placeholder="Alguma observação sobre o pedido?"></textarea>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check"></i> Confirmar Pedido
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-calculator"></i> Resumo do Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <strong>R$ <?= number_format($total, 2, ',', '.') ?></strong>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>Frete:</span>
                        <span class="text-success">Grátis</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <h5>Total:</h5>
                        <h5 class="text-primary">R$ <?= number_format($total, 2, ',', '.') ?></h5>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Forma de Pagamento:</strong><br>
                        O pagamento será acertado via WhatsApp após a confirmação do pedido.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
