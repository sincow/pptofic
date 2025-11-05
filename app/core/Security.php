<?php
class Security {
    public static function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map(array('Security', 'sanitizeInput'), $data);
        }
        
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }
    
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            if (function_exists('random_bytes')) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            } else {
                $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
            }
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    // MÉTODOS NUEVOS PARA PASSWORDS
    public static function passwordHash($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    public static function passwordVerify($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public static function generateRandomPassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }
}