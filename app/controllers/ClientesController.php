<?php
class ClientesController {
    private $db;
    
    public function __construct() {
        require_once APP_PATH . '/core/Database.php';
        $this->db = new Database();
    }
    
    public function index() {
        $data = [
            'title' => 'Maestro de Clientes',
            'current_page' => 'maestro-clientes'
        ];
        
        // Obtener lista de clientes
        $sql = "SELECT c.*, t.nombre as tipo_documento 
                FROM DvClient c 
                LEFT JOIN DvTipoDocumento t ON c.TerDocId = t.id_tipodoc 
                WHERE c.id_empresa = ? AND c.status = '1' 
                ORDER BY c.creado_el DESC";
        $stmt = $this->db->query($sql, [$_SESSION['empresa_id']]);
        $data['clientes'] = $stmt ? $stmt->fetchAll() : [];
        
        $this->view('clientes/index', $data);
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente = $this->sanitizeClienteData($_POST);
            
            if ($this->guardarCliente($cliente)) {
                $_SESSION['success'] = 'Cliente creado exitosamente';
                header('Location: ' . BASE_URL . '/cliente');
                exit;
            } else {
                $data['error'] = 'Error al crear el cliente';
            }
        }
        
        $data = [
            'title' => 'Nuevo Cliente',
            'current_page' => 'maestro-clientes',
            'cliente' => $this->getEmptyCliente()
        ];
        
        $this->view('clientes/form', $data);
    }
    
    public function editar($id = null) {
        if (!$id) {
            header('Location: ' . BASE_URL . '/cliente');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente = $this->sanitizeClienteData($_POST);
            $cliente['id_dvcliente'] = $id;
            
            if ($this->actualizarCliente($cliente)) {
                $_SESSION['success'] = 'Cliente actualizado exitosamente';
                header('Location: ' . BASE_URL . '/cliente');
                exit;
            } else {
                $data['error'] = 'Error al actualizar el cliente';
            }
        }
        
        // Obtener cliente existente
        $sql = "SELECT * FROM DvClient WHERE id_dvcliente = ? AND id_empresa = ?";
        $stmt = $this->db->query($sql, [$id, $_SESSION['empresa_id']]);
        $cliente = $stmt ? $stmt->fetch() : null;
        
        if (!$cliente) {
            $_SESSION['error'] = 'Cliente no encontrado';
            header('Location: ' . BASE_URL . '/cliente');
            exit;
        }
        
        $data = [
            'title' => 'Editar Cliente',
            'current_page' => 'maestro-clientes',
            'cliente' => $cliente
        ];
        
        $this->view('clientes/form', $data);
    }
    
    private function sanitizeClienteData($postData) {
        return [
            'id_empresa' => $_SESSION['empresa_id'],
            'TerDocId' => intval($postData['TerDocId'] ?? 0),
            'direccion_residencia' => trim($postData['direccion_residencia'] ?? ''),
            'fecha_nacimiento' => $postData['fecha_nacimiento'] ?? null,
            'ciudad_nacimiento' => trim($postData['ciudad_nacimiento'] ?? ''),
            'referencia_comercial' => trim($postData['referencia_comercial'] ?? ''),
            'telefono_refcomercial' => trim($postData['telefono_refcomercial'] ?? ''),
            'referencia_personal' => trim($postData['referencia_personal'] ?? ''),
            'telefono_refpersonal' => trim($postData['telefono_refpersonal'] ?? ''),
            'valor_cupo' => floatval($postData['valor_cupo'] ?? 0),
            'valor_cupotemporal' => floatval($postData['valor_cupotemporal'] ?? 0),
            'id_actividad' => intval($postData['id_actividad'] ?? 0),
            'tipo_telefono' => $postData['tipo_telefono'] ?? '1',
            'persona_responde' => trim($postData['persona_responde'] ?? ''),
            'origen_recursos' => trim($postData['origen_recursos'] ?? ''),
            'niel_riezgo' => intval($postData['niel_riezgo'] ?? 1),
            'pep' => isset($postData['pep']) ? 1 : 0,
            'id_user' => $_SESSION['user_id']
        ];
    }
    
    private function guardarCliente($cliente) {
        $sql = "INSERT INTO DvClient (
            id_empresa, TerDocId, direccion_residencia, fecha_nacimiento, 
            ciudad_nacimiento, referencia_comercial, telefono_refcomercial, 
            referencia_personal, telefono_refpersonal, valor_cupo, valor_cupotemporal,
            id_actividad, tipo_telefono, persona_responde, origen_recursos,
            niel_riezgo, pep, id_user
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        return $this->db->query($sql, [
            $cliente['id_empresa'], $cliente['TerDocId'], $cliente['direccion_residencia'],
            $cliente['fecha_nacimiento'], $cliente['ciudad_nacimiento'], $cliente['referencia_comercial'],
            $cliente['telefono_refcomercial'], $cliente['referencia_personal'], $cliente['telefono_refpersonal'],
            $cliente['valor_cupo'], $cliente['valor_cupotemporal'], $cliente['id_actividad'],
            $cliente['tipo_telefono'], $cliente['persona_responde'], $cliente['origen_recursos'],
            $cliente['niel_riezgo'], $cliente['pep'], $cliente['id_user']
        ]);
    }
    
    private function actualizarCliente($cliente) {
        $sql = "UPDATE DvClient SET 
            TerDocId = ?, direccion_residencia = ?, fecha_nacimiento = ?,
            ciudad_nacimiento = ?, referencia_comercial = ?, telefono_refcomercial = ?,
            referencia_personal = ?, telefono_refpersonal = ?, valor_cupo = ?, 
            valor_cupotemporal = ?, id_actividad = ?, tipo_telefono = ?, 
            persona_responde = ?, origen_recursos = ?, niel_riezgo = ?, pep = ?,
            actualizado_el = NOW(), id_user = ?
            WHERE id_dvcliente = ? AND id_empresa = ?";
        
        return $this->db->query($sql, [
            $cliente['TerDocId'], $cliente['direccion_residencia'], $cliente['fecha_nacimiento'],
            $cliente['ciudad_nacimiento'], $cliente['referencia_comercial'], $cliente['telefono_refcomercial'],
            $cliente['referencia_personal'], $cliente['telefono_refpersonal'], $cliente['valor_cupo'],
            $cliente['valor_cupotemporal'], $cliente['id_actividad'], $cliente['tipo_telefono'],
            $cliente['persona_responde'], $cliente['origen_recursos'], $cliente['niel_riezgo'],
            $cliente['pep'], $cliente['id_user'], $cliente['id_dvcliente'], $cliente['id_empresa']
        ]);
    }
    
    private function getEmptyCliente() {
        return [
            'id_dvcliente' => '',
            'TerDocId' => '',
            'direccion_residencia' => '',
            'fecha_nacimiento' => '',
            'ciudad_nacimiento' => '',
            'referencia_comercial' => '',
            'telefono_refcomercial' => '',
            'referencia_personal' => '',
            'telefono_refpersonal' => '',
            'valor_cupo' => '0.00',
            'valor_cupotemporal' => '0.00',
            'id_actividad' => '',
            'tipo_telefono' => '1',
            'persona_responde' => '',
            'origen_recursos' => '',
            'niel_riezgo' => '1',
            'pep' => '0'
        ];
    }
    
    private function getRiskBadgeClass($nivelRiesgo) {
        switch($nivelRiesgo) {
            case 1: return 'success';
            case 2: return 'info';
            case 3: return 'warning';
            case 4: return 'danger';
            default: return 'secondary';
        }
    }
    
    private function view($view, $data = array()) {
        // Datos básicos para todas las vistas
        $defaultData = array(
            'base_url' => BASE_URL,
            'assets_url' => ASSETS_URL
        );
        $data = array_merge($defaultData, $data);
        
        extract($data);
        
        // Cargar layout
        require_once APP_PATH . '/views/layout/header.php';
        require_once APP_PATH . "/views/{$view}.php";
        require_once APP_PATH . '/views/layout/footer.php';
    }
}