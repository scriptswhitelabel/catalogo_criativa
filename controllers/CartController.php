<?php

class CartController extends Controller {
    private $productModel;
    private $orderModel;
    
    public function __construct() {
        parent::__construct();
        $this->productModel = new Product();
        $this->orderModel = new Order();
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Inicializar carrinho da sessão a partir do cookie persistente (30 dias)
        if (!empty($_COOKIE['cart'])) {
            $cookieCart = json_decode($_COOKIE['cart'], true);
            if (is_array($cookieCart)) {
                foreach ($cookieCart as $pid => $qty) {
                    $pid = (string)$pid;
                    $qty = (int)$qty;
                    if ($qty > 0) {
                        if (isset($_SESSION['cart'][$pid])) {
                            $_SESSION['cart'][$pid] += $qty;
                        } else {
                            $_SESSION['cart'][$pid] = $qty;
                        }
                    }
                }
                $this->saveCartCookie();
            }
        }
    }

    private function saveCartCookie() {
        // Persistir por 30 dias
        $expires = time() + (60 * 60 * 24 * 30);
        // Garantir apenas inteiros positivos no cookie
        $cart = [];
        foreach ($_SESSION['cart'] as $pid => $qty) {
            $qty = (int)$qty;
            if ($qty > 0) {
                $cart[(string)$pid] = $qty;
            }
        }
        setcookie('cart', json_encode($cart), $expires, '/');
    }
    
    public function add() {
        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if ($productId && $quantity > 0) {
            $product = $this->productModel->findById($productId);
            
            if ($product && $product['status'] === 'available') {
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId] += $quantity;
                } else {
                    $_SESSION['cart'][$productId] = $quantity;
                }
                $this->saveCartCookie();
            }
        }
        
        // Suporte a requisições AJAX: retorna o total de itens no carrinho
        $isAjax = (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) || (!empty($_POST['ajax']) && $_POST['ajax'] == '1');
        
        if ($isAjax) {
            $count = array_sum($_SESSION['cart']);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'count' => $count
            ]);
            exit;
        }
        
        $this->redirect(BASE_URL . '?controller=cart&action=index');
    }
    
    public function remove() {
        $productId = $_GET['id'] ?? null;
        
        if ($productId && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            $this->saveCartCookie();
        }
        
        $this->redirect(BASE_URL . '?controller=cart&action=index');
    }
    
    public function update() {
        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 0);
        
        if ($productId) {
            if ($quantity > 0) {
                $_SESSION['cart'][$productId] = $quantity;
            } else {
                unset($_SESSION['cart'][$productId]);
            }
            $this->saveCartCookie();
        }
        
        $this->redirect(BASE_URL . '?controller=cart&action=index');
    }
    
    public function index() {
        $cartItems = [];
        $total = 0;
        
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $this->productModel->findById($productId);
            if ($product && $product['status'] === 'available') {
                $itemTotal = $product['unit_price'] * $quantity;
                $total += $itemTotal;
                
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'total' => $itemTotal
                ];
            } else {
                // Remove produtos indisponíveis do carrinho
                unset($_SESSION['cart'][$productId]);
            }
        }
        
        $this->view('cart/index', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }
    
    public function checkout() {
        Auth::requireLogin();
        
        $cartItems = [];
        $total = 0;
        
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $this->productModel->findById($productId);
            if ($product && $product['status'] === 'available') {
                $itemTotal = $product['unit_price'] * $quantity;
                $total += $itemTotal;
                
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'total' => $itemTotal
                ];
            }
        }
        
        if (empty($cartItems)) {
            $this->redirect(BASE_URL . '?controller=cart&action=index');
        }
        
        $this->view('cart/checkout', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }
    
    public function finish() {
        Auth::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = Auth::getUserId();
            $notes = $_POST['notes'] ?? '';
            
            $cartItems = [];
            $total = 0;
            
            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $product = $this->productModel->findById($productId);
                if ($product && $product['status'] === 'available') {
                    $itemTotal = $product['unit_price'] * $quantity;
                    $total += $itemTotal;
                    
                    $cartItems[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'total' => $itemTotal
                    ];
                }
            }
            
            if (!empty($cartItems)) {
                // Criar pedido
                $orderId = $this->orderModel->create([
                    'user_id' => $userId,
                    'total_amount' => $total,
                    'status' => 'pending',
                    'notes' => $notes
                ]);
                
                // Adicionar itens ao pedido
                foreach ($cartItems as $item) {
                    $this->orderModel->addItem(
                        $orderId,
                        $item['product']['id'],
                        $item['quantity'],
                        $item['product']['unit_price'],
                        $item['total']
                    );
                }
                
                // Limpar carrinho (sessão e cookie persistente)
                $_SESSION['cart'] = [];
                $this->saveCartCookie();
                
                $this->redirect(BASE_URL . '?controller=cart&action=success&order_id=' . $orderId);
            }
        }
        
        $this->redirect(BASE_URL . '?controller=cart&action=index');
    }
    
    public function success() {
        $orderId = $_GET['order_id'] ?? null;
        
        if (!$orderId) {
            $this->redirect(BASE_URL);
        }
        
        $order = $this->orderModel->findById($orderId);
        $items = $this->orderModel->getItems($orderId);
        
        if (!$order) {
            $this->redirect(BASE_URL);
        }
        
        $this->view('cart/success', [
            'order' => $order,
            'items' => $items
        ]);
    }
    
    public function whatsapp() {
        $cartItems = [];
        $total = 0;
        $message = "🛒 *PEDIDO - CRIATIVA LOJA*\n\n";
        
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $this->productModel->findById($productId);
            if ($product && $product['status'] === 'available') {
                $itemTotal = $product['unit_price'] * $quantity;
                $total += $itemTotal;
                
                $message .= "📦 *{$product['name']}*\n";
                $message .= "   Quantidade: {$quantity}\n";
                $message .= "   Valor unitário: R$ " . number_format($product['unit_price'], 2, ',', '.') . "\n";
                $message .= "   Subtotal: R$ " . number_format($itemTotal, 2, ',', '.') . "\n\n";
            }
        }
        
        $message .= "💰 *TOTAL: R$ " . number_format($total, 2, ',', '.') . "*\n\n";
        $message .= "Por favor, confirme este pedido e informe a forma de pagamento.";
        
        $whatsappNumber = "5511999999999"; // Substitua pelo número real
        $encodedMessage = urlencode($message);
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$encodedMessage}";
        
        header("Location: $whatsappUrl");
        exit;
    }
}
?>
