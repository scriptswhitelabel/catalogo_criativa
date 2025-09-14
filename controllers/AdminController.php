<?php

class AdminController extends Controller {
    private $productModel;
    private $userModel;
    private $orderModel;
    private $categoryModel;
    private $brandModel;
    
    public function __construct() {
        parent::__construct();
        Auth::requireAdmin();
        
        $this->productModel = new Product();
        $this->userModel = new User();
        $this->orderModel = new Order();
        $this->categoryModel = new Category();
        $this->brandModel = new Brand();
    }
    
    public function dashboard() {
        $totalProducts = count($this->productModel->getAll());
        $totalClients = count($this->userModel->getAllClients());
        $totalOrders = count($this->orderModel->getAll());
        $recentOrders = $this->orderModel->getAll(['status' => 'pending']);
        
        $this->view('admin/dashboard', [
            'totalProducts' => $totalProducts,
            'totalClients' => $totalClients,
            'totalOrders' => $totalOrders,
            'recentOrders' => $recentOrders
        ]);
    }
    
    public function products() {
        $products = $this->productModel->getAll();
        $categories = $this->categoryModel->getAll();
        $brands = $this->brandModel->getAll();
        
        $this->view('admin/products', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands
        ]);
    }
    
    public function createProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'brand_id' => $_POST['brand_id'] ?? null,
                'category_id' => $_POST['category_id'] ?? null,
                'description' => $_POST['description'] ?? '',
                'unit_price' => $_POST['unit_price'] ?? 0,
                'package_price' => $_POST['package_price'] ?? null,
                'status' => $_POST['status'] ?? 'available'
            ];
            
            $errors = $this->validateRequired(['name', 'unit_price'], $data);
            
            if (empty($errors)) {
                $productId = $this->productModel->create($data);
                
                // Upload de imagens
                if (!empty($_FILES['images']['name'][0])) {
                    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                        if (!empty($tmpName)) {
                            $file = [
                                'tmp_name' => $tmpName,
                                'name' => $_FILES['images']['name'][$key]
                            ];
                            $imagePath = $this->uploadFile($file, 'uploads/products/');
                            if ($imagePath) {
                                $this->productModel->addImage($productId, $imagePath, $key === 0);
                            }
                        }
                    }
                }
                
                // Upload de vídeo
                if (!empty($_FILES['video']['tmp_name'])) {
                    $videoPath = $this->uploadFile($_FILES['video'], 'uploads/videos/');
                    if ($videoPath) {
                        $this->productModel->addVideo($productId, $videoPath);
                    }
                }
                
                // URL de vídeo
                if (!empty($_POST['video_url'])) {
                    $this->productModel->addVideo($productId, null, $_POST['video_url']);
                }
                
                $this->redirect(BASE_URL . '?controller=admin&action=products');
            }
            
            $categories = $this->categoryModel->getAll();
            $brands = $this->brandModel->getAll();
            
            $this->view('admin/create_product', [
                'errors' => $errors,
                'data' => $data,
                'categories' => $categories,
                'brands' => $brands
            ]);
        } else {
            $categories = $this->categoryModel->getAll();
            $brands = $this->brandModel->getAll();
            
            $this->view('admin/create_product', [
                'categories' => $categories,
                'brands' => $brands
            ]);
        }
    }
    
    public function editProduct() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $this->redirect(BASE_URL . '?controller=admin&action=products');
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product) {
            $this->redirect(BASE_URL . '?controller=admin&action=products');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'brand_id' => $_POST['brand_id'] ?? null,
                'category_id' => $_POST['category_id'] ?? null,
                'description' => $_POST['description'] ?? '',
                'unit_price' => $_POST['unit_price'] ?? 0,
                'package_price' => $_POST['package_price'] ?? null,
                'status' => $_POST['status'] ?? 'available'
            ];
            
            $errors = $this->validateRequired(['name', 'unit_price'], $data);
            
            if (empty($errors)) {
                $this->productModel->update($id, $data);
                $this->redirect(BASE_URL . '?controller=admin&action=products');
            }
            
            $categories = $this->categoryModel->getAll();
            $brands = $this->brandModel->getAll();
            
            $this->view('admin/edit_product', [
                'product' => $product,
                'errors' => $errors,
                'data' => $data,
                'categories' => $categories,
                'brands' => $brands
            ]);
        } else {
            $categories = $this->categoryModel->getAll();
            $brands = $this->brandModel->getAll();
            
            $this->view('admin/edit_product', [
                'product' => $product,
                'categories' => $categories,
                'brands' => $brands
            ]);
        }
    }
    
    public function deleteProduct() {
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $this->productModel->delete($id);
        }
        
        $this->redirect(BASE_URL . '?controller=admin&action=products');
    }
    
    public function clients() {
        $clients = $this->userModel->getAllClients();
        
        $this->view('admin/clients', ['clients' => $clients]);
    }
    
    public function orders() {
        $orders = $this->orderModel->getAll();
        
        $this->view('admin/orders', ['orders' => $orders]);
    }
    
    public function orderDetail() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $this->redirect(BASE_URL . '?controller=admin&action=orders');
        }
        
        $order = $this->orderModel->findById($id);
        $items = $this->orderModel->getItems($id);
        
        if (!$order) {
            $this->redirect(BASE_URL . '?controller=admin&action=orders');
        }
        
        $this->view('admin/order_detail', [
            'order' => $order,
            'items' => $items
        ]);
    }
    
    public function updateOrderStatus() {
        $id = $_POST['order_id'] ?? null;
        $status = $_POST['status'] ?? null;
        
        if ($id && $status) {
            $this->orderModel->updateStatus($id, $status);
        }
        
        $this->redirect(BASE_URL . '?controller=admin&action=orderDetail&id=' . $id);
    }
}
?>
