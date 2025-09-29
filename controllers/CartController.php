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
        // Usa cookie separado por usuário logado: cart_u_{userId}; para visitantes: cart_guest
        $cookieName = $this->getCartCookieName();
        $initializedFlag = 'cart_initialized_from_cookie_' . $cookieName;
        // Somente quando o carrinho da sessão estiver vazio e ainda não tenha sido importado
        if (empty($_SESSION['cart']) && empty($_SESSION[$initializedFlag]) && !empty($_COOKIE[$cookieName])) {
            $cookieCart = json_decode($_COOKIE[$cookieName], true);
            if (is_array($cookieCart)) {
                $_SESSION['cart'] = [];
                foreach ($cookieCart as $pid => $qty) {
                    $pid = (string)$pid;
                    $qty = (int)$qty;
                    if ($qty > 0) {
                        $_SESSION['cart'][$pid] = $qty;
                    }
                }
                $_SESSION[$initializedFlag] = true;
            }
        }
    }

    private function getCartCookieName() {
        if (Auth::isLoggedIn()) {
            $userId = Auth::getUserId();
            return 'cart_u_' . $userId;
        }
        return 'cart_guest';
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
        $cookieName = $this->getCartCookieName();
        setcookie($cookieName, json_encode($cart), $expires, '/');
    }

    private function normalizePhoneToE164Digits($raw) {
        // Remove tudo que não for número
        $digits = preg_replace('/\D+/', '', (string)$raw);
        if ($digits === '') { return ''; }
        // Remove zeros à esquerda comuns em algumas entradas
        $digits = ltrim($digits, '0');
        // Se já começar com 55 (Brasil), mantém
        if (strpos($digits, '55') === 0) {
            return $digits;
        }
        // Se tiver 10 ou 11 dígitos (DDD + número), prefixa 55
        $len = strlen($digits);
        if ($len === 10 || $len === 11) {
            return '55' . $digits;
        }
        // Caso tenha outro comprimento, retorna como está (já pode estar internacional ex: 1..., 351..., etc.)
        return $digits;
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
                
                // Enviar notificação do pedido via Whaticket
                try {
                    require_once 'core/Env.php';
                    require_once 'core/WhaticketClient.php';
                    $client = new WhaticketClient();
                    // Números de destino: .env WHATICKET_NUMBERS (lista separada por vírgula) ou WHATICKET_NUMBER único; inclui telefone da loja como fallback
                    $numbersCsv = Env::get('WHATICKET_NUMBERS', '');
                    $numbers = [];
                    if (!empty($numbersCsv)) {
                        $parts = explode(',', $numbersCsv);
                        foreach ($parts as $p) {
                            $p = trim($p);
                            if ($p !== '') { $numbers[] = $this->normalizePhoneToE164Digits($p); }
                        }
                    } else {
                        $single = Env::get('WHATICKET_NUMBER', '');
                        if (!empty($single)) { $numbers[] = $this->normalizePhoneToE164Digits($single); }
                    }
                    // Adicionar telefone da loja se existir
                    $storePhone = SettingsHelper::getStorePhone();
                    $digits = $this->normalizePhoneToE164Digits($storePhone);
                    if (!empty($digits)) { $numbers[] = $digits; }
                    // Adicionar telefone do cliente do pedido, se existir
                    if (!empty($order) && !empty($order['user_phone'])) {
                        $clientDigits = $this->normalizePhoneToE164Digits($order['user_phone']);
                        if (!empty($clientDigits)) { $numbers[] = $clientDigits; }
                    }
                    // Remover duplicados
                    $numbers = array_values(array_unique($numbers));
                    // Buscar dados do pedido para incluir cliente e data/hora
                    $order = $this->orderModel->findById($orderId);
                    $orderDate = '';
                    $customerName = '';
                    if ($order) {
                        $orderDate = !empty($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : date('d/m/Y H:i');
                        $customerName = $order['user_name'] ?? '';
                    }

                    // Mensagem com resumo do pedido
                    $lines = [];
                    $lines[] = "🚨 ALERTA! NOVO PEDIDO REALIZADO PELO SISTEMA";
                    $lines[] = "Pedido #{$orderId}";
                    if (!empty($customerName)) { $lines[] = "Cliente: {$customerName}"; }
                    if (!empty($orderDate)) { $lines[] = "Data/Hora: {$orderDate}"; }
                    $lines[] = "Total: R$ " . number_format($total, 2, ',', '.');
                    foreach ($cartItems as $item) {
                        $lines[] = "- {$item['product']['name']} x{$item['quantity']} (R$ " . number_format($item['product']['unit_price'], 2, ',', '.') . ")";
                    }
                    if (!empty($notes)) {
                        $lines[] = "Obs: " . $notes;
                    }
                    $message = implode("\n", $lines);
                    if (!empty($numbers)) {
                        $client->sendToMany($numbers, $message);
                    }
                } catch (Exception $e) {
                    // Silenciar falha de notificação para não quebrar o fluxo do pedido
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

