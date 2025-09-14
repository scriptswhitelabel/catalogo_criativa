    <footer class="footer py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-store"></i> Criativa Loja</h5>
                    <p>Sua loja online de confiança com os melhores produtos.</p>
                </div>
                <div class="col-md-6 text-end">
                    <h6>Contato</h6>
                    <p>
                        <i class="fas fa-envelope"></i> contato@criativa.com<br>
                        <i class="fas fa-phone"></i> (11) 99999-9999
                    </p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?= date('Y') ?> Criativa Loja. Todos os direitos reservados.</p>
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
