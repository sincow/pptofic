<?php
class Auth {
    private $db;
    private $session;
    
    public function __construct() {
        $this->db = new Database();
        $this->session = new Session();
    }
    
    public function login($username, $password) {
        // Sanitizar inputs
        $username = trim($username);
        $password = trim($password);
        
        if (empty($username) || empty($password)) {
            return false;
        }
        
        // Buscar usuario
        $sql = "SELECT * FROM DvUsuarios 
                WHERE id_user = :username 
                AND id_empresa = '001' 
                AND status = '1' 
                LIMIT 1";
        
        $stmt = $this->db->query($sql, array(':username' => $username));
        
        if ($stmt) {
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Actualizar último login
                $this->updateLastLogin($user['id_user']);
                
                // Guardar en sesión (sin password)
                unset($user['password']);
                $this->session->set('user', $user);
                $this->session->set('user_id', $user['id_user']);
                $this->session->set('empresa_id', $user['id_empresa']);
                $this->session->set('user_role', $user['role']);
                
                return true;
            }
        }
        
        return false;
    }
    
    public function logout() {
        $this->session->destroy();
    }
    
    public function isAuthenticated() {
        return $this->session->isLoggedIn();
    }
    
    public function getUser() {
        return $this->session->getUser();
    }
    
    public function checkPermission($requiredRole = null) {
        if (!$this->isAuthenticated()) {
            return false;
        }
        
        if ($requiredRole) {
            $userRole = $this->session->get('user_role');
            return $userRole === 'admin' || $userRole === $requiredRole;
        }
        
        return true;
    }
    
    private function updateLastLogin($userId) {
        $sql = "UPDATE DvUsuarios SET ultimo_login = NOW() 
                WHERE id_user = :user_id AND id_empresa = '001'";
        $this->db->query($sql, array(':user_id' => $userId));
    }
    
    public function changePassword($userId, $currentPassword, $newPassword) {
        $sql = "SELECT password FROM DvUsuarios 
                WHERE id_user = :user_id AND id_empresa = '001' 
                LIMIT 1";
        $stmt = $this->db->query($sql, array(':user_id' => $userId));
        
        if ($stmt) {
            $user = $stmt->fetch();
            
            if ($user && password_verify($currentPassword, $user['password'])) {
                // LÍNEA CORREGIDA - Usar Security::passwordHash
                $hashedPassword = Security::passwordHash($newPassword);
                $sql = "UPDATE DvUsuarios SET password = :password 
                        WHERE id_user = :user_id AND id_empresa = '001'";
                return $this->db->query($sql, array(
                    ':password' => $hashedPassword,
                    ':user_id' => $userId
                ));
            }
        }
        
        return false;
    }
}