<?php

class ClientController extends Controller {
    private $userModel;
    private $orderModel;
    
    public function __construct() {
        parent::__construct();
        Auth::requireLogin();
        
        $this->userModel = new User();
        $this->orderModel = new Order();
    }
    
    public function dashboard() {
        $userId = Auth::getUserId();
        $user = $this->userModel->findById($userId);
        $orders = $this->orderModel->getByUser($userId);
        
        $this->view('client/dashboard', [
            'user' => $user,
            'orders' => $orders
        ]);
    }
    
    public function profile() {
        $userId = Auth::getUserId();
        $user = $this->userModel->findById($userId);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'company_name' => $_POST['company_name'] ?? '',
                'cnpj' => $_POST['cnpj'] ?? ''
            ];
            
            $errors = $this->validateRequired(['name', 'email'], $data);
            
            if (empty($errors)) {
                if (!$this->validateEmail($data['email'])) {
                    $errors[] = 'Email inválido';
                }
                
                // Verificar se email já existe em outro usuário
                $existingUser = $this->userModel->findByEmail($data['email']);
                if ($existingUser && $existingUser['id'] != $userId) {
                    $errors[] = 'Este email já está sendo usado por outro usuário';
                }
            }
            
            if (empty($errors)) {
                $this->userModel->update($userId, $data);
                $this->redirect(BASE_URL . '?controller=client&action=profile');
            }
            
            $this->view('client/profile', [
                'user' => $user,
                'errors' => $errors,
                'data' => $data
            ]);
        } else {
            $this->view('client/profile', ['user' => $user]);
        }
    }
    
    public function orders() {
        $userId = Auth::getUserId();
        $orders = $this->orderModel->getByUser($userId);
        
        $this->view('client/orders', ['orders' => $orders]);
    }
    
    public function orderDetail() {
        $id = $_GET['id'] ?? null;
        $userId = Auth::getUserId();
        
        if (!$id) {
            $this->redirect(BASE_URL . '?controller=client&action=orders');
        }
        
        $order = $this->orderModel->findById($id);
        
        // Verificar se o pedido pertence ao usuário
        if (!$order || $order['user_id'] != $userId) {
            $this->redirect(BASE_URL . '?controller=client&action=orders');
        }
        
        $items = $this->orderModel->getItems($id);
        
        $this->view('client/order_detail', [
            'order' => $order,
            'items' => $items
        ]);
    }
}
?>
