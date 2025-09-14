    <footer class="footer py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>
                        <?php if (SettingsHelper::hasLogo()): ?>
                            <img src="<?= SettingsHelper::getLogoUrl() ?>" alt="<?= SettingsHelper::getStoreName() ?>" height="25" class="me-2">
                        <?php else: ?>
                            <i class="fas fa-store"></i>
                        <?php endif; ?>
                        <?= SettingsHelper::getStoreName() ?>
                    </h5>
                    <p>Sua loja online de confiança com os melhores produtos.</p>
                    <?php if (SettingsHelper::getStoreAddress()): ?>
                        <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars(SettingsHelper::getStoreAddress()) ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 text-end">
                    <h6>Contato</h6>
                    <p>
                        <?php if (SettingsHelper::getStoreEmail()): ?>
                            <i class="fas fa-envelope"></i> <?= htmlspecialchars(SettingsHelper::getStoreEmail()) ?><br>
                        <?php endif; ?>
                        <?php if (SettingsHelper::getStorePhone()): ?>
                            <i class="fas fa-phone"></i> <?= htmlspecialchars(SettingsHelper::getStorePhone()) ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?= date('Y') ?> <?= SettingsHelper::getStoreName() ?>. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para adicionar ao carrinho via AJAX
        function addToCart(productId, quantity = 1) {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            
            fetch('<?= BASE_URL ?>?controller=cart&action=add', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        }
        
        // Função para atualizar quantidade no carrinho
        function updateCartQuantity(productId, quantity) {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            
            fetch('<?= BASE_URL ?>?controller=cart&action=update', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        }
        
        // Função para remover do carrinho
        function removeFromCart(productId) {
            if (confirm('Deseja remover este item do carrinho?')) {
                window.location.href = '<?= BASE_URL ?>?controller=cart&action=remove&id=' + productId;
            }
        }
    </script>
</body>
</html>
