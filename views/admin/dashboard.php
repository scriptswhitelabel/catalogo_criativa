<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-tachometer-alt"></i> Dashboard Administrativo</h2>
        </div>
    </div>
    
    <!-- Cards de estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?= $totalProducts ?></h4>
                            <p>Produtos</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?= $totalClients ?></h4>
                            <p>Clientes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?= $totalOrders ?></h4>
                            <p>Pedidos</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?= count($recentOrders) ?></h4>
                            <p>Pendentes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Links rápidos -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-bolt"></i> Ações Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="<?= BASE_URL ?>?controller=admin&action=createProduct" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-plus"></i> Novo Produto
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= BASE_URL ?>?controller=admin&action=products" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-box"></i> Gerenciar Produtos
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= BASE_URL ?>?controller=admin&action=clients" class="btn btn-outline-success w-100 mb-2">
                                <i class="fas fa-users"></i> Gerenciar Clientes
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= BASE_URL ?>?controller=admin&action=orders" class="btn btn-outline-warning w-100 mb-2">
                                <i class="fas fa-shopping-cart"></i> Gerenciar Pedidos
                            </a>
                        </div>
                    </div>
                    
                    <!-- Segunda linha de ações -->
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <a href="<?= BASE_URL ?>?controller=admin&action=settings" class="btn btn-outline-info w-100 mb-2">
                                <i class="fas fa-cog"></i> Configurações da Loja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pedidos recentes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-clock"></i> Pedidos Pendentes</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recentOrders)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Nenhum pedido pendente no momento.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Total</th>
                                        <th>Data</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td>#<?= $order['id'] ?></td>
                                            <td><?= htmlspecialchars($order['user_name']) ?></td>
                                            <td>R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                            <td>
                                                <span class="badge bg-warning"><?= ucfirst($order['status']) ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= BASE_URL ?>?controller=admin&action=orderDetail&id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
