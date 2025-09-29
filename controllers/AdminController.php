<?php

class AdminController extends Controller {
    private $productModel;
    private $userModel;
    private $orderModel;
    private $categoryModel;
    private $brandModel;
    private $settingsModel;
    
    public function __construct() {
        parent::__construct();
        Auth::requireAdmin();
        
        $this->productModel = new Product();
        $this->userModel = new User();
        $this->orderModel = new Order();
        $this->categoryModel = new Category();
        $this->brandModel = new Brand();
        $this->settingsModel = new Settings();
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
            'brands' => $brands,
            'productModel' => $this->productModel
        ]);
    }

    // ====== CATEGORIES CRUD ======
    public function categories() {
        $categories = $this->categoryModel->getAll();
        $this->view('admin/categories', ['categories' => $categories]);
    }

    public function createCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
            $errors = $this->validateRequired(['name'], $data);
            if (empty($errors)) {
                $this->categoryModel->create($data);
                $this->redirect(BASE_URL . '?controller=admin&action=categories');
            }
            $this->view('admin/category_form', ['errors' => $errors, 'data' => $data]);
        } else {
            $this->view('admin/category_form');
        }
    }

    public function editCategory() {
        $id = $_GET['id'] ?? null;
        if (!$id) { $this->redirect(BASE_URL . '?controller=admin&action=categories'); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
            $errors = $this->validateRequired(['name'], $data);
            if (empty($errors)) {
                $this->categoryModel->update($id, $data);
                $this->redirect(BASE_URL . '?controller=admin&action=categories');
            }
            $category = $this->categoryModel->findById($id);
            $this->view('admin/category_form', ['errors' => $errors, 'data' => $data, 'category' => $category]);
        } else {
            $category = $this->categoryModel->findById($id);
            if (!$category) { $this->redirect(BASE_URL . '?controller=admin&action=categories'); }
            $this->view('admin/category_form', ['category' => $category]);
        }
    }

    public function deleteCategory() {
        $id = $_GET['id'] ?? null;
        if ($id) { $this->categoryModel->delete($id); }
        $this->redirect(BASE_URL . '?controller=admin&action=categories');
    }

    // ====== BRANDS CRUD ======
    public function brands() {
        $brands = $this->brandModel->getAll();
        $this->view('admin/brands', ['brands' => $brands]);
    }

    public function createBrand() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
            $errors = $this->validateRequired(['name'], $data);
            if (empty($errors)) {
                $this->brandModel->create($data);
                $this->redirect(BASE_URL . '?controller=admin&action=brands');
            }
            $this->view('admin/brand_form', ['errors' => $errors, 'data' => $data]);
        } else {
            $this->view('admin/brand_form');
        }
    }

    public function editBrand() {
        $id = $_GET['id'] ?? null;
        if (!$id) { $this->redirect(BASE_URL . '?controller=admin&action=brands'); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
            $errors = $this->validateRequired(['name'], $data);
            if (empty($errors)) {
                $this->brandModel->update($id, $data);
                $this->redirect(BASE_URL . '?controller=admin&action=brands');
            }
            $brand = $this->brandModel->findById($id);
            $this->view('admin/brand_form', ['errors' => $errors, 'data' => $data, 'brand' => $brand]);
        } else {
            $brand = $this->brandModel->findById($id);
            if (!$brand) { $this->redirect(BASE_URL . '?controller=admin&action=brands'); }
            $this->view('admin/brand_form', ['brand' => $brand]);
        }
    }

    public function deleteBrand() {
        $id = $_GET['id'] ?? null;
        if ($id) { $this->brandModel->delete($id); }
        $this->redirect(BASE_URL . '?controller=admin&action=brands');
    }
    
    public function createProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Normalizações para evitar strings vazias em colunas inteiras/numéricas
            $brandIdRaw = $_POST['brand_id'] ?? '';
            $categoryIdRaw = $_POST['category_id'] ?? '';
            $packagePriceRaw = $_POST['package_price'] ?? '';
            $data = [
                'name' => $_POST['name'] ?? '',
                'brand_id' => ($brandIdRaw === '' ? null : (int)$brandIdRaw),
                'category_id' => ($categoryIdRaw === '' ? null : (int)$categoryIdRaw),
                'description' => $_POST['description'] ?? '',
                'unit_price' => isset($_POST['unit_price']) ? (float)$_POST['unit_price'] : 0,
                'package_price' => ($packagePriceRaw === '' ? null : (float)$packagePriceRaw),
                'status' => $_POST['status'] ?? 'available'
            ];
            
            $errors = $this->validateRequired(['name', 'unit_price'], $data);
            
            if (empty($errors)) {
                $productId = $this->productModel->create($data);
                
                // Upload de imagens (arquivos)
                if (!empty($_FILES['images']['name'][0])) {
                    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                        if (!empty($tmpName)) {
                            $file = [
                                'tmp_name' => $tmpName,
                                'name' => $_FILES['images']['name'][$key],
                                'type' => $_FILES['images']['type'][$key],
                                'size' => $_FILES['images']['size'][$key],
                                'error' => $_FILES['images']['error'][$key]
                            ];
                            $imagePath = $this->uploadFile($file, 'uploads/products/');
                            if ($imagePath) {
                                $this->productModel->addImage($productId, $imagePath, $key === 0);
                            }
                        }
                    }
                }

                // Download de imagens por URL (suporta múltiplas URLs separadas por quebra de linha)
                if (!empty($_POST['image_urls'])) {
                    $urls = preg_split('/\r\n|\r|\n/', trim($_POST['image_urls']));
                    $position = 0;
                    foreach ($urls as $imgUrl) {
                        $imgUrl = trim($imgUrl);
                        if ($imgUrl === '') { continue; }
                        $downloaded = $this->downloadFileFromUrl($imgUrl, 'uploads/products/', 'image');
                        if ($downloaded) {
                            $this->productModel->addImage($productId, $downloaded, $position === 0 && empty($_FILES['images']['name'][0]));
                            $position++;
                        }
                    }
                }
                
                // Upload de vídeo (arquivo)
                if (!empty($_FILES['video']['tmp_name'])) {
                    $videoPath = $this->uploadFile($_FILES['video'], 'uploads/videos/');
                    if ($videoPath) {
                        $this->productModel->addVideo($productId, $videoPath);
                    }
                }
                
                // Download de vídeo por URL OU salvar URL externa
                if (!empty($_POST['video_url'])) {
                    $videoUrl = trim($_POST['video_url']);
                    // Tentar baixar se for arquivo direto (mp4, webm etc.). Se falhar, salva URL externa.
                    $downloadedVideo = $this->downloadFileFromUrl($videoUrl, 'uploads/videos/', 'video');
                    if ($downloadedVideo) {
                        $this->productModel->addVideo($productId, $downloadedVideo, null);
                    } else {
                        $this->productModel->addVideo($productId, null, $videoUrl);
                    }
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
            $brandIdRaw = $_POST['brand_id'] ?? '';
            $categoryIdRaw = $_POST['category_id'] ?? '';
            $packagePriceRaw = $_POST['package_price'] ?? '';
            $data = [
                'name' => $_POST['name'] ?? '',
                'brand_id' => ($brandIdRaw === '' ? null : (int)$brandIdRaw),
                'category_id' => ($categoryIdRaw === '' ? null : (int)$categoryIdRaw),
                'description' => $_POST['description'] ?? '',
                'unit_price' => isset($_POST['unit_price']) ? (float)$_POST['unit_price'] : 0,
                'package_price' => ($packagePriceRaw === '' ? null : (float)$packagePriceRaw),
                'status' => $_POST['status'] ?? 'available'
            ];
            
            $errors = $this->validateRequired(['name', 'unit_price'], $data);
            
            if (empty($errors)) {
                $this->productModel->update($id, $data);
                
                $uploadMessages = [];
                
                // Upload de novas imagens (arquivos)
                if (!empty($_FILES['images']['name'][0])) {
                    $uploadedImages = 0;
                    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                        if (!empty($tmpName)) {
                            $file = [
                                'tmp_name' => $tmpName,
                                'name' => $_FILES['images']['name'][$key],
                                'type' => $_FILES['images']['type'][$key],
                                'size' => $_FILES['images']['size'][$key],
                                'error' => $_FILES['images']['error'][$key]
                            ];
                            $imagePath = $this->uploadFile($file, 'uploads/products/');
                            if ($imagePath) {
                                $this->productModel->addImage($id, $imagePath, false);
                                $uploadedImages++;
                            }
                        }
                    }
                    if ($uploadedImages > 0) {
                        $uploadMessages[] = "$uploadedImages imagem(ns) adicionada(s) com sucesso!";
                    }
                }

                // Download de novas imagens por URL (múltiplas linhas)
                if (!empty($_POST['image_urls'])) {
                    $urls = preg_split('/\r\n|\r|\n/', trim($_POST['image_urls']));
                    $downloadedImages = 0;
                    foreach ($urls as $imgUrl) {
                        $imgUrl = trim($imgUrl);
                        if ($imgUrl === '') { continue; }
                        $downloaded = $this->downloadFileFromUrl($imgUrl, 'uploads/products/', 'image');
                        if ($downloaded) {
                            $this->productModel->addImage($id, $downloaded, false);
                            $downloadedImages++;
                        }
                    }
                    if ($downloadedImages > 0) {
                        $uploadMessages[] = "$downloadedImages imagem(ns) baixada(s) por URL com sucesso!";
                    }
                }
                
                // Upload de vídeo (arquivo)
                if (!empty($_FILES['video']['tmp_name'])) {
                    $videoPath = $this->uploadFile($_FILES['video'], 'uploads/videos/');
                    if ($videoPath) {
                        $this->productModel->addVideo($id, $videoPath);
                        $uploadMessages[] = "Vídeo adicionado com sucesso!";
                    }
                }
                
                // Download de vídeo por URL OU salvar URL externa
                if (!empty($_POST['video_url'])) {
                    $videoUrl = trim($_POST['video_url']);
                    $downloadedVideo = $this->downloadFileFromUrl($videoUrl, 'uploads/videos/', 'video');
                    if ($downloadedVideo) {
                        $this->productModel->addVideo($id, $downloadedVideo, null);
                        $uploadMessages[] = "Vídeo baixado por URL com sucesso!";
                    } else {
                        $this->productModel->addVideo($id, null, $videoUrl);
                        $uploadMessages[] = "URL de vídeo adicionada com sucesso!";
                    }
                }
                
                if (!empty($uploadMessages)) {
                    $_SESSION['success_message'] = implode(' ', $uploadMessages);
                } else {
                    $_SESSION['success_message'] = 'Produto atualizado com sucesso!';
                }
                
                $this->redirect(BASE_URL . '?controller=admin&action=products');
            }
            
            $categories = $this->categoryModel->getAll();
            $brands = $this->brandModel->getAll();
            
            $this->view('admin/edit_product', [
                'product' => $product,
                'errors' => $errors,
                'data' => $data,
                'categories' => $categories,
                'brands' => $brands,
                'productModel' => $this->productModel
            ]);
        } else {
            $categories = $this->categoryModel->getAll();
            $brands = $this->brandModel->getAll();
            
            $this->view('admin/edit_product', [
                'product' => $product,
                'categories' => $categories,
                'brands' => $brands,
                'productModel' => $this->productModel
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
    
    public function clientDetail() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $this->redirect(BASE_URL . '?controller=admin&action=clients');
        }
        
        $client = $this->userModel->findById($id);
        if (!$client) {
            $this->redirect(BASE_URL . '?controller=admin&action=clients');
        }
        
        // Pedidos do cliente
        $orders = $this->orderModel->getAll(['user_id' => $id]);
        
        $this->view('admin/client_detail', [
            'client' => $client,
            'orders' => $orders
        ]);
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
    
    public function settings() {
        $settings = $this->settingsModel->getAll();
        
        // Converter array de configurações para formato mais fácil de usar
        $settingsData = [];
        foreach ($settings as $setting) {
            $settingsData[$setting['setting_key']] = $setting['setting_value'];
        }
        
        $this->view('admin/settings', ['settings' => $settingsData]);
    }
    
    public function updateSettings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settings = [
                'store_name' => $_POST['store_name'] ?? '',
                'store_phone' => $_POST['store_phone'] ?? '',
                'store_email' => $_POST['store_email'] ?? '',
                'store_address' => $_POST['store_address'] ?? ''
            ];
            
            // Upload da logomarca se fornecida
            if (!empty($_FILES['store_logo']['tmp_name'])) {
                $logoPath = $this->uploadFile($_FILES['store_logo'], 'uploads/settings/');
                if ($logoPath) {
                    $settings['store_logo'] = 'uploads/settings/' . $logoPath;
                }
            } else {
                // Manter a logomarca atual se não foi enviada nova
                $currentLogo = $this->settingsModel->get('store_logo');
                if ($currentLogo) {
                    $settings['store_logo'] = $currentLogo;
                }
            }
            
            $success = $this->settingsModel->updateMultiple($settings);
            
            if ($success) {
                $_SESSION['success_message'] = 'Configurações atualizadas com sucesso!';
            } else {
                $_SESSION['error_message'] = 'Erro ao atualizar configurações.';
            }
            
            $this->redirect(BASE_URL . '?controller=admin&action=settings');
        }
        
        $this->redirect(BASE_URL . '?controller=admin&action=settings');
    }
    
    /**
     * Método público para upload de arquivos
     */
    public function uploadFile($file, $directory = 'uploads/') {
        return parent::uploadFile($file, $directory);
    }
}
?>
