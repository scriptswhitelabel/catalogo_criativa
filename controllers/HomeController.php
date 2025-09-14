<?php

class HomeController extends Controller {
    private $productModel;
    private $categoryModel;
    private $brandModel;
    
    public function __construct() {
        parent::__construct();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->brandModel = new Brand();
    }
    
    public function index() {
        $filters = [
            'status' => 'available'
        ];
        
        if (!empty($_GET['category'])) {
            $filters['category_id'] = $_GET['category'];
        }
        
        if (!empty($_GET['brand'])) {
            $filters['brand_id'] = $_GET['brand'];
        }
        
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        $products = $this->productModel->getAll($filters);
        $categories = $this->categoryModel->getAll();
        $brands = $this->brandModel->getAll();
        
        $this->view('home/index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $filters
        ]);
    }
    
    public function product() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $this->redirect(BASE_URL);
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product) {
            $this->redirect(BASE_URL);
        }
        
        $images = $this->productModel->getImages($id);
        $videos = $this->productModel->getVideos($id);
        
        $this->view('home/product', [
            'product' => $product,
            'images' => $images,
            'videos' => $videos
        ]);
    }
}
?>
