<?php

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $errors = $this->validateRequired(['email', 'password'], $_POST);
            
            if (empty($errors)) {
                if (!$this->validateEmail($email)) {
                    $errors[] = 'Email inválido';
                }
            }
            
            if (empty($errors)) {
                $user = $this->userModel->findByEmail($email);
                
                if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
                    Auth::login($user['id'], $user['user_type']);
                    // Migrar carrinho de convidado para carrinho do usuário
                    $guestCookie = $_COOKIE['cart_guest'] ?? null;
                    if ($guestCookie) {
                        $guestCart = json_decode($guestCookie, true);
                        if (is_array($guestCart)) {
                            // Carregar eventual cookie do usuário e mesclar
                            $userCookieName = 'cart_u_' . $user['id'];
                            $userCart = [];
                            if (!empty($_COOKIE[$userCookieName])) {
                                $tmp = json_decode($_COOKIE[$userCookieName], true);
                                if (is_array($tmp)) { $userCart = $tmp; }
                            }
                            foreach ($guestCart as $pid => $qty) {
                                $qty = (int)$qty;
                                if ($qty <= 0) { continue; }
                                if (isset($userCart[$pid])) { $userCart[$pid] += $qty; }
                                else { $userCart[$pid] = $qty; }
                            }
                            $expires = time() + (60 * 60 * 24 * 30);
                            setcookie($userCookieName, json_encode($userCart), $expires, '/');
                        }
                        // Limpar cookie de convidado
                        setcookie('cart_guest', '', time() - 3600, '/');
                    }
                    
                    if ($user['user_type'] === 'admin') {
                        $this->redirect(BASE_URL . '?controller=admin&action=dashboard');
                    } else {
                        $this->redirect(BASE_URL . '?controller=client&action=dashboard');
                    }
                } else {
                    $errors[] = 'Email ou senha incorretos';
                }
            }
            
            $this->view('auth/login', ['errors' => $errors]);
        } else {
            $this->view('auth/login');
        }
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'company_name' => $_POST['company_name'] ?? '',
                'cnpj' => $_POST['cnpj'] ?? '',
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? ''
            ];
            
            $errors = $this->validateRequired(['name', 'email', 'password', 'confirm_password'], $data);
            
            if (empty($errors)) {
                if (!$this->validateEmail($data['email'])) {
                    $errors[] = 'Email inválido';
                }
                
                if ($data['password'] !== $data['confirm_password']) {
                    $errors[] = 'As senhas não coincidem';
                }
                
                if (strlen($data['password']) < 6) {
                    $errors[] = 'A senha deve ter pelo menos 6 caracteres';
                }
                
                $existingUser = $this->userModel->findByEmail($data['email']);
                if ($existingUser) {
                    $errors[] = 'Este email já está cadastrado';
                }
            }
            
            if (empty($errors)) {
                unset($data['confirm_password']);
                $userId = $this->userModel->create($data);
                
                if ($userId) {
                    Auth::login($userId, 'client');
                    $this->redirect(BASE_URL . '?controller=client&action=dashboard');
                } else {
                    $errors[] = 'Erro ao criar conta';
                }
            }
            
            $this->view('auth/register', ['errors' => $errors, 'data' => $data]);
        } else {
            $this->view('auth/register');
        }
    }
    
    public function logout() {
        Auth::logout();
    }
}
?>
