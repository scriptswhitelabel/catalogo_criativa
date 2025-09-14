<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Início</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?controller=home&action=index&category=<?= $product['category_id'] ?>"><?= htmlspecialchars($product['category_name']) ?></a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Imagens -->
        <div class="col-md-6">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php if (!empty($images)): ?>
                        <?php foreach ($images as $index => $image): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="<?= BASE_URL ?>/uploads/products/<?= $image['image_path'] ?>" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Imagem do produto">
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="carousel-item active">
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 400px;">
                                <i class="fas fa-image fa-5x text-muted"></i>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (count($images) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                <?php endif; ?>
            </div>
            
            <!-- Vídeos -->
            <?php if (!empty($videos)): ?>
                <div class="mt-4">
                    <h5><i class="fas fa-video"></i> Vídeos</h5>
                    <?php foreach ($videos as $video): ?>
                        <?php if ($video['video_url']): ?>
                            <div class="ratio ratio-16x9 mb-3">
                                <iframe src="<?= htmlspecialchars($video['video_url']) ?>" allowfullscreen></iframe>
                            </div>
                        <?php elseif ($video['video_path']): ?>
                            <video controls class="w-100 mb-3">
                                <source src="<?= BASE_URL ?>/uploads/videos/<?= $video['video_path'] ?>" type="video/mp4">
                                Seu navegador não suporta vídeos.
                            </video>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Informações do produto -->
        <div class="col-md-6">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            
            <div class="mb-3">
                <span class="badge bg-secondary me-2">
                    <i class="fas fa-tag"></i> <?= htmlspecialchars($product['brand_name'] ?? 'Sem marca') ?>
                </span>
                <span class="badge bg-info">
                    <i class="fas fa-folder"></i> <?= htmlspecialchars($product['category_name'] ?? 'Sem categoria') ?>
                </span>
            </div>
            
            <div class="mb-4">
                <h3 class="text-primary">R$ <?= number_format($product['unit_price'], 2, ',', '.') ?></h3>
                <?php if ($product['package_price']): ?>
                    <h5 class="text-success">Pacote: R$ <?= number_format($product['package_price'], 2, ',', '.') ?></h5>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <h5>Descrição</h5>
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Quantidade:</label>
                <div class="input-group" style="max-width: 150px;">
                    <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">-</button>
                    <input type="number" class="form-control text-center" id="quantity" value="1" min="1">
                    <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">+</button>
                </div>
            </div>
            
            <div class="d-grid gap-2">
                <button class="btn btn-success btn-lg" onclick="addToCart(<?= $product['id'] ?>, document.getElementById('quantity').value)">
                    <i class="fas fa-cart-plus"></i> Adicionar ao Carrinho
                </button>
                <a href="<?= BASE_URL ?>?controller=cart&action=index" class="btn btn-outline-primary">
                    <i class="fas fa-shopping-cart"></i> Ver Carrinho
                </a>
            </div>
            
            <div class="mt-4">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Status:</strong> 
                    <?= $product['status'] === 'available' ? 'Disponível' : 'Indisponível' ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeQuantity(delta) {
    const input = document.getElementById('quantity');
    const newValue = parseInt(input.value) + delta;
    if (newValue >= 1) {
        input.value = newValue;
    }
}
</script>

<?php require_once 'views/layout/footer.php'; ?>
