<?php require_once 'views/layout/header.php'; ?>

<div class="container mt-4">
	<div class="row">
		<div class="col-12">
			<h2><i class="fas fa-user"></i> Detalhes do Cliente #<?= $client['id'] ?></h2>
		</div>
	</div>
	
	<div class="row mt-3">
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					<h5><i class="fas fa-id-card"></i> Informações</h5>
				</div>
				<div class="card-body">
					<p><strong>Nome:</strong> <?= htmlspecialchars($client['name']) ?></p>
					<p><strong>Email:</strong> <?= htmlspecialchars($client['email']) ?></p>
					<p><strong>Telefone:</strong> <?= htmlspecialchars($client['phone'] ?? 'Não informado') ?></p>
					<p><strong>Empresa:</strong> <?= htmlspecialchars($client['company_name'] ?? 'Não informado') ?></p>
					<p><strong>CNPJ:</strong> <?= htmlspecialchars($client['cnpj'] ?? 'Não informado') ?></p>
					<p><strong>Cadastrado em:</strong> <?= date('d/m/Y H:i', strtotime($client['created_at'])) ?></p>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h5><i class="fas fa-shopping-cart"></i> Pedidos do Cliente</h5>
				</div>
				<div class="card-body">
					<?php if (empty($orders)): ?>
						<p class="text-muted">Nenhum pedido encontrado para este cliente.</p>
					<?php else: ?>
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>ID</th>
										<th>Total</th>
										<th>Data</th>
										<th>Status</th>
										<th>Ações</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($orders as $order): ?>
										<tr>
											<td>#<?= $order['id'] ?></td>
											<td>R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></td>
											<td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
											<td><span class="badge bg-secondary"><?= ucfirst($order['status']) ?></span></td>
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

