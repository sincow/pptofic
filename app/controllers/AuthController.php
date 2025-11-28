<?php
class AuthController {
    private $db;
    
    public function __construct() {
        require_once APP_PATH . '/core/Database.php';
        $this->db = new Database();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Buscar usuario en BD
            $sql = "SELECT * FROM usuarios 
                    WHERE UsuCorreo = ? 
                    AND UsuEstad = '1' 
                    LIMIT 1";
            
            $stmt = $this->db->querySite($sql, [$username]);
            //var_dump($stmt->fetch());
            if ($stmt && $user = $stmt->fetch()) {
                if (password_verify($password, $user['UsuClave'])) {
                    // Login exitoso
						$_SESSION["user_id"]    = $user["id"];
						$_SESSION["user_name"]  = $user["UsuNombr"];
						$_SESSION["usuario"]    = $user["UsuCodig"];
						$_SESSION["foto"]       = $user["UsuFoto"];
						$_SESSION["user_role"]  = $user["UsuPerfi"];
						$_SESSION["bdemp"]      = $user["EmpCodig"];
						$_SESSION["empser"]     = $user["EmpServi"];
						$_SESSION["empusu"]     = $user["EmpUsuar"];
						$_SESSION["empcla"]     = $user["EmpClave"];
						$_SESSION["empresa_id"] = $user["EmpCoDef"];
						$_SESSION["connum"]     = $user["ConNumer"];
						$_SESSION["usuemail"]   = $user["UsuCorreo"];
						$_SESSION["usudepcod"]  = $user["UsuDepCod"];
						$_SESSION["Usu2fKey"]   = $user["Usu2fKey"];

                  define('DB_HOST','localhost');
                  define('DB_USER',$user["EmpUsuar"]);
                  define('DB_PASS',$user["EmpClave"]);
                  //define('DB_SERV','mysql:host=localhost;dbname=');
                  define('DB_NAME',  $user["EmpCodig"]);
                  define('DB_CHARSET', 'utf8');

                  //   $_SESSION['user_id'] = $user['id_user'];
                  //   $_SESSION['user_name'] = $user['nombre'];
                  //   $_SESSION['empresa_id'] = $user['id_empresa'];
                  //   $_SESSION['user_data'] = $user;
                    
                    // Actualizar último login
                    $this->updateLastLogin($user['id_user']);
                    
                    header('Location: ' . BASE_URL . '/dashboard');
                    exit;
                }
            }
            
            $error = "Usuario o contraseña incorrectos";
        }
        
        require_once APP_PATH . '/views/auth/login.php';
    }
    
    private function updateLastLogin($userId) {
        $sql = "UPDATE usuarios SET UsuFecUl = NOW() WHERE id = ?";
        $this->db->query($sql, [$userId]);
    }
    
    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
}