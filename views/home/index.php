<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <!-- Filtros -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-filter"></i> Filtros</h5>
                </div>
                <div class="card-body">
                    <form method="GET">
                        <div class="mb-3">
                            <label class="form-label">Buscar</label>
                            <input type="text" class="form-control" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Nome do produto...">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Categoria</label>
                            <select class="form-select" name="category">
                                <option value="">Todas as categorias</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= ($filters['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Marca</label>
                            <select class="form-select" name="brand">
                                <option value="">Todas as marcas</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= $brand['id'] ?>" <?= ($filters['brand_id'] ?? '') == $brand['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($brand['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        
                        <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-times"></i> Limpar
                        </a>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Produtos -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-box"></i> Produtos</h2>
                <span class="badge bg-primary"><?= count($products) ?> produtos encontrados</span>
            </div>
            
            <?php if (empty($products)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Nenhum produto encontrado com os filtros aplicados.
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <?php 
                                $productModel = new Product();
                                $images = $productModel->getImages($product['id']);
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
                                    <img src="<?= BASE_URL ?>/uploads/products/<?= $primaryImage['image_path'] ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($product['name']) ?>">
                                <?php else: ?>
                                    <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                    <p class="card-text text-muted small">
                                        <i class="fas fa-tag"></i> <?= htmlspecialchars($product['brand_name'] ?? 'Sem marca') ?><br>
                                        <i class="fas fa-folder"></i> <?= htmlspecialchars($product['category_name'] ?? 'Sem categoria') ?>
                                    </p>
                                    <p class="card-text"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <strong class="text-primary">R$ <?= number_format($product['unit_price'], 2, ',', '.') ?></strong>
                                                <?php if ($product['package_price']): ?>
                                                    <br><small class="text-success">Pacote: R$ <?= number_format($product['package_price'], 2, ',', '.') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="d-grid gap-2">
                                            <a href="<?= BASE_URL ?>?controller=home&action=product&id=<?= $product['id'] ?>" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i> Ver Detalhes
                                            </a>
                                            <button class="btn btn-success" onclick="addToCart(<?= $product['id'] ?>)">
                                                <i class="fas fa-cart-plus"></i> Adicionar ao Carrinho
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
