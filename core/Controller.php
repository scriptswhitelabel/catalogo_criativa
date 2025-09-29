<?php

class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    protected function view($view, $data = []) {
        extract($data);
        require_once "views/$view.php";
    }
    
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function validateRequired($fields, $data) {
        $errors = [];
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                $errors[] = "O campo $field é obrigatório";
            }
        }
        return $errors;
    }
    
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    protected function uploadFile($file, $directory = 'uploads/') {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return null;
        }
        
        $uploadDir = $directory;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filename;
        }
        
        return null;
    }

    /**
     * Faz o download de um arquivo remoto por URL e salva localmente.
     * Retorna o nome do arquivo salvo (sem diretório) em caso de sucesso ou null em falha.
     * $type pode ser 'image' ou 'video' para validação de MIME.
     */
    protected function downloadFileFromUrl($url, $directory = 'uploads/', $type = 'image') {
        if (empty($url)) {
            return null;
        }

        // Normalizar diretório e garantir existência
        $downloadDir = rtrim($directory, '/').'/';
        if (!is_dir($downloadDir)) {
            mkdir($downloadDir, 0777, true);
        }

        // Baixar conteúdo com cURL (segue redirects)
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $binaryData = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($binaryData === false || $httpCode < 200 || $httpCode >= 300) {
            return null;
        }

        // Validar tipo MIME a partir do conteúdo
        $tmpPath = tempnam(sys_get_temp_dir(), 'dl_');
        if ($tmpPath === false) {
            return null;
        }
        file_put_contents($tmpPath, $binaryData);

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmpPath);
        $isValid = false;
        if ($type === 'image' && strpos((string)$mime, 'image/') === 0) {
            $isValid = true;
        }
        if ($type === 'video' && strpos((string)$mime, 'video/') === 0) {
            $isValid = true;
        }

        if (!$isValid) {
            @unlink($tmpPath);
            return null;
        }

        // Mapear extensão a partir do MIME, com fallback para URL
        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
            'video/ogg' => 'ogv'
        ];
        $extension = $mimeToExt[$mime] ?? pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION);
        if (empty($extension)) {
            // Último fallback por tipo
            $extension = $type === 'image' ? 'jpg' : 'mp4';
        }

        $filename = uniqid('', true) . '.' . $extension;
        $finalPath = $downloadDir . $filename;

        if (!@rename($tmpPath, $finalPath)) {
            // Caso não consiga mover, tente copiar
            $saved = @copy($tmpPath, $finalPath);
            @unlink($tmpPath);
            if (!$saved) {
                return null;
            }
        }

        return $filename;
    }
}
?>
