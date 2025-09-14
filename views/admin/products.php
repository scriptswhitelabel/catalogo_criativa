<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-box"></i> Gerenciar Produtos</h2>
                <a href="<?= BASE_URL ?>?controller=admin&action=createProduct" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Produto
                </a>
            </div>
            
            <?php if (empty($products)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Nenhum produto cadastrado ainda.
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Imagem</th>
                                        <th>Nome</th>
                                        <th>Marca</th>
                                        <th>Categoria</th>
                                        <th>Preço</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td>
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
                                                    <img src="<?= BASE_URL ?>/uploads/products/<?= $primaryImage['image_path'] ?>" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;" alt="<?= htmlspecialchars($product['name']) ?>">
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($product['name']) ?></strong>
                                                <br><small class="text-muted"><?= htmlspecialchars(substr($product['description'], 0, 50)) ?>...</small>
                                            </td>
                                            <td><?= htmlspecialchars($product['brand_name'] ?? 'Sem marca') ?></td>
                                            <td><?= htmlspecialchars($product['category_name'] ?? 'Sem categoria') ?></td>
                                            <td>
                                                <strong>R$ <?= number_format($product['unit_price'], 2, ',', '.') ?></strong>
                                                <?php if ($product['package_price']): ?>
                                                    <br><small class="text-success">Pacote: R$ <?= number_format($product['package_price'], 2, ',', '.') ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($product['status'] === 'available'): ?>
                                                    <span class="badge bg-success">Disponível</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Indisponível</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= BASE_URL ?>?controller=home&action=product&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-info" target="_blank">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>?controller=admin&action=editProduct&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>?controller=admin&action=deleteProduct&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja realmente excluir este produto?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
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
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
