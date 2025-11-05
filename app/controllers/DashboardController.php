<?php
class DashboardController {
    
    public function index() {
        $data = [
            'title' => 'Dashboard',
            'user_name' => $_SESSION['user_name'] ?? 'Usuario'
        ];
        
        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}