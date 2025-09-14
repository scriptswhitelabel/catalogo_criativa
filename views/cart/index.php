<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-shopping-cart"></i> Carrinho de Compras</h2>
            
            <?php if (empty($cartItems)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                    <h4>Seu carrinho está vazio</h4>
                    <p>Adicione alguns produtos para começar suas compras!</p>
                    <a href="<?= BASE_URL ?>" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Continuar Comprando
                    </a>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
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
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <h6><?= htmlspecialchars($item['product']['name']) ?></h6>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($item['product']['brand_name'] ?? 'Sem marca') ?>
                                            </small>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <strong>R$ <?= number_format($item['product']['unit_price'], 2, ',', '.') ?></strong>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <div class="input-group input-group-sm">
                                                <button class="btn btn-outline-secondary" type="button" onclick="updateCartQuantity(<?= $item['product']['id'] ?>, <?= $item['quantity'] - 1 ?>)">-</button>
                                                <input type="number" class="form-control text-center" value="<?= $item['quantity'] ?>" min="1" onchange="updateCartQuantity(<?= $item['product']['id'] ?>, this.value)">
                                                <button class="btn btn-outline-secondary" type="button" onclick="updateCartQuantity(<?= $item['product']['id'] ?>, <?= $item['quantity'] + 1 ?>)">+</button>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-1">
                                            <strong>R$ <?= number_format($item['total'], 2, ',', '.') ?></strong>
                                        </div>
                                        
                                        <div class="col-md-1">
                                            <button class="btn btn-outline-danger btn-sm" onclick="removeFromCart(<?= $item['product']['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
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
                                
                                <div class="d-grid gap-2">
                                    <?php if (Auth::isLoggedIn()): ?>
                                        <a href="<?= BASE_URL ?>?controller=cart&action=checkout" class="btn btn-success btn-lg">
                                            <i class="fas fa-credit-card"></i> Finalizar Pedido
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= BASE_URL ?>?controller=auth&action=login" class="btn btn-warning btn-lg">
                                            <i class="fas fa-sign-in-alt"></i> Entrar para Finalizar
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="<?= BASE_URL ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-arrow-left"></i> Continuar Comprando
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
