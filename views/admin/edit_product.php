<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-edit"></i> Editar Produto</h2>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Informações Básicas</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome do Produto *</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($data['name'] ?? $product['name']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="available" <?= ($data['status'] ?? $product['status']) === 'available' ? 'selected' : '' ?>>Disponível</option>
                                        <option value="unavailable" <?= ($data['status'] ?? $product['status']) === 'unavailable' ? 'selected' : '' ?>>Indisponível</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Marca</label>
                                    <select class="form-select" id="brand_id" name="brand_id">
                                        <option value="">Selecione uma marca</option>
                                        <?php foreach ($brands as $brand): ?>
                                            <option value="<?= $brand['id'] ?>" <?= ($data['brand_id'] ?? $product['brand_id']) == $brand['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($brand['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Categoria</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">Selecione uma categoria</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>" <?= ($data['category_id'] ?? $product['category_id']) == $category['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($data['description'] ?? $product['description']) ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="unit_price" class="form-label">Valor Unitário *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control" id="unit_price" name="unit_price" step="0.01" min="0" value="<?= $data['unit_price'] ?? $product['unit_price'] ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="package_price" class="form-label">Valor do Pacote</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control" id="package_price" name="package_price" step="0.01" min="0" value="<?= $data['package_price'] ?? $product['package_price'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upload de Novas Imagens -->
                        <div class="mb-3">
                            <label for="images" class="form-label">
                                <i class="fas fa-images"></i> Adicionar Novas Imagens
                            </label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">
                                Selecione uma ou mais imagens para adicionar ao produto. Formatos aceitos: JPG, PNG, GIF.
                            </div>
                        </div>
                        
                        <!-- Upload de Vídeo -->
                        <div class="mb-3">
                            <label for="video" class="form-label">
                                <i class="fas fa-video"></i> Vídeo do Produto
                            </label>
                            <input type="file" class="form-control" id="video" name="video" accept="video/*">
                            <div class="form-text">
                                Upload de arquivo de vídeo (opcional).
                            </div>
                        </div>
                        
                        <!-- URL do Vídeo -->
                        <div class="mb-3">
                            <label for="video_url" class="form-label">
                                <i class="fas fa-link"></i> URL do Vídeo (YouTube, Vimeo, etc.)
                            </label>
                            <input type="url" class="form-control" id="video_url" name="video_url" placeholder="https://www.youtube.com/watch?v=...">
                            <div class="form-text">
                                Cole aqui a URL do vídeo do produto (opcional).
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= BASE_URL ?>?controller=admin&action=products" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-image"></i> Imagens Atuais</h5>
                </div>
                <div class="card-body">
                    <?php 
                    $images = $productModel->getImages($product['id']);
                    if (!empty($images)): 
                    ?>
                        <?php foreach ($images as $image): ?>
                            <div class="mb-2">
                                <img src="<?= BASE_URL ?>/uploads/products/<?= $image['image_path'] ?>" class="img-thumbnail w-100" alt="Imagem do produto">
                                <?php if ($image['is_primary']): ?>
                                    <small class="text-success"><i class="fas fa-star"></i> Imagem Principal</small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Nenhuma imagem cadastrada.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Vídeos Atuais -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-video"></i> Vídeos Atuais</h5>
                </div>
                <div class="card-body">
                    <?php 
                    $videos = $productModel->getVideos($product['id']);
                    if (!empty($videos)): 
                    ?>
                        <?php foreach ($videos as $video): ?>
                            <div class="mb-2">
                                <?php if ($video['video_path']): ?>
                                    <video controls class="w-100" style="max-height: 200px;">
                                        <source src="<?= BASE_URL ?>uploads/videos/<?= $video['video_path'] ?>" type="video/mp4">
                                        Seu navegador não suporta vídeos.
                                    </video>
                                <?php elseif ($video['video_url']): ?>
                                    <div class="alert alert-info">
                                        <i class="fas fa-external-link-alt"></i> 
                                        <a href="<?= htmlspecialchars($video['video_url']) ?>" target="_blank" class="text-decoration-none">
                                            Ver vídeo externo
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Nenhum vídeo cadastrado.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
