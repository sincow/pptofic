<?php
class Router {
    public function route() {
        $requestUri = $_SERVER['REQUEST_URI'];
        $scriptName = $_SERVER['SCRIPT_NAME'];
        
        // Obtener la ruta base del proyecto
        $basePath = dirname($scriptName);
        
        // Si estamos en una subcarpeta, removerla de la request URI
        if ($basePath != '/' && strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }
        
        // Limpiar la ruta
        $path = trim(parse_url($requestUri, PHP_URL_PATH), '/');
        
        // Si la ruta está vacía, ir al dashboard
        if (empty($path)) {
            $controllerName = 'DashboardController';
            $action = 'index';
        } else {
            // Dividir la ruta en partes
            $parts = explode('/', $path);
            
            // Determinar controlador y acción
            $controllerName = !empty($parts[0]) ? $this->getControllerName($parts[0]) : 'DashboardController';
            $action = !empty($parts[1]) ? $parts[1] : 'index';
        }
        
        $controllerFile = APP_PATH . "/controllers/{$controllerName}.php";
        
        if (APP_DEBUG) {
            error_log("Controller: {$controllerName}");
            error_log("Action: {$action}");
            error_log("File: {$controllerFile}");
        }
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            if (class_exists($controllerName)) {
                $controllerInstance = new $controllerName();
                
                if (method_exists($controllerInstance, $action)) {
                    call_user_func_array(array($controllerInstance, $action), array_slice($parts, 2));
                } else {
                    $this->showError("Método {$action} no encontrado en {$controllerName}");
                }
            } else {
                $this->showError("Controlador {$controllerName} no encontrado");
            }
        } else {
            $this->showError("Controlador no encontrado: {$controllerFile}");
        }
    }
    
    private function getControllerName($route) {
        // Convertir kebab-case o snake_case a CamelCase
        $route = str_replace(['-', '_'], ' ', $route);
        $route = ucwords($route);
        $route = str_replace(' ', '', $route);
        
        return $route . 'Controller';
    }
    
    private function showError($message) {
        http_response_code(404);
        if (APP_DEBUG) {
            die("Error: " . $message);
        } else {
            echo "<h1>Página no encontrada</h1>";
        }
    }
}