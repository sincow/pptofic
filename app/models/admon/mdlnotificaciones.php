<?php
if (!defined('CONFIG_PATH')) {
   define("CONFIG_PATH", "../config");
}
if (!isset($_SESSION)) {
	session_start();
}
if (isset($_SESSION['reportPath'])) {
   require_once $_SESSION['reportPath'].CONFIG_PATH . "/Database.php";
} else {
   require_once CONFIG_PATH . "/Database.php";
}
// $lang = Language::getInstance("../app/languages/");
// $lang->loadTranslations();

class NotificacionesModel {

   //**************************************************************************************
   static public function getAll() {
      $connection = Database::getConnection();
      $sql = "SELECT a.*, b.name, c.name as user_name 
         FROM GrNotifi a
         LEFT JOIN users b ON a.id_user = b.id_user
         LEFT JOIN users c ON a.user_id_user = c.id_user
         WHERE a.id_empresa = :idEmpresa
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }



   //**************************************************************************************
	static public function filter($data) {
		try {
			$filters = [];
			$params = [];

			$filters = [
            'empresaSearch' => isset($_SESSION["id_empresa"]) ? $_SESSION["id_empresa"] : null,
				'numberSearch' => isset($data['numberSearch']) ? trim($data['numberSearch']) : null,
            'statusSearch' => isset($data['statusSearch']) ? trim($data['statusSearch']) : null,
            'fechaSearchFrom' => isset($data['fechaSearchFrom']) && !empty($data['fechaSearchFrom']) ? DateTime::createFromFormat(DATE_FORMAT, $data['fechaSearchFrom'])->format('Y-m-d') : null,
            'fechaSearchTo' => isset($data['fechaSearchTo']) && !empty($data['fechaSearchTo']) ? DateTime::createFromFormat(DATE_FORMAT, $data['fechaSearchTo'])->format('Y-m-d') : null,
            'empleadoSearch' => isset($data['empleadoSearch']) ? trim($data['empleadoSearch']) : null,
            'entregaSearchFrom' => isset($data['entregaSearchFrom']) && !empty($data['entregaSearchFrom']) ? DateTime::createFromFormat(DATE_FORMAT, $data['entregaSearchFrom'])->format('Y-m-d') : null,
				'entregaSearchTo' => isset($data['entregaSearchTo']) && !empty($data['entregaSearchTo']) ? DateTime::createFromFormat(DATE_FORMAT, $data['entregaSearchTo'])->format('Y-m-d') : null,

            'tituloSearch' => isset($data['tituloSearch']) ? trim($data['tituloSearch']) : null,

            'poCompanySearch' => isset($data['poCompanySearch']) && is_numeric($data['poCompanySearch']) ? (int)$data['poCompanySearch'] : null,
				'poVendorSearch' => isset($data['poVendorSearch']) && is_numeric($data['poVendorSearch']) ? (int)$data['poVendorSearch'] : null,
				'poWarehouseSearch' => isset($data['poWarehouseSearch']) && is_numeric($data['poWarehouseSearch']) ? (int)$data['poWarehouseSearch'] : null,
				'minCostSearch' => isset($data['minCostSearch']) && is_numeric($data['minCostSearch']) ? (float)$data['minCostSearch'] : null,
				'maxCostSearch' => isset($data['maxCostSearch']) && is_numeric($data['maxCostSearch']) ? (float)$data['maxCostSearch'] : null
			];

			$query = "SELECT a.*, b.name, c.name as user_name, 
            COALESCE((SELECT MAX(n.fecha) FROM GrNotiSeg n WHERE a.id_empresa = n.id_empresa AND 
            a.id_notifi = n.id_notifi AND n.tipo = '2'), a.fecha_entrega) as UltEntrega, 
            COALESCE((SELECT MAX(n.fecha) FROM GrNotiSeg n WHERE a.id_empresa = n.id_empresa AND 
            a.id_notifi = n.id_notifi AND n.tipo = '3'), null) as fecha_cierre 
            FROM GrNotifi a 
            LEFT JOIN companies d ON a.id_empresa = d.id_empresa
            LEFT JOIN users b     ON a.id_user = b.id_user
            LEFT JOIN users c     ON a.user_id_user = c.id_user
				WHERE 1=1";

         if (!empty($filters['empresaSearch'])) {
				$query .= " AND a.id_empresa = ?";
				$params[] = $filters['empresaSearch'];
			}

			if (!empty($filters['numberSearch'])) {
				$query .= " AND a.numero LIKE ?";
				$params[] = '%' . $filters['numberSearch'] . '%';
			}
         if (!empty($filters['statusSearch'])) {
            if ($filters['statusSearch'] == '*') {
               $query .= " AND a.status <> ?";
            } else {               
               $query .= " AND a.status = ?";
            }
				$params[] = $filters['statusSearch'];
         }
         if (!empty($filters['fechaSearchFrom'])) {
				$query .= " AND a.fecha >= ?";
				$params[] = $filters['fechaSearchFrom'];
			}
			if (!empty($filters['fechaSearchTo'])) {
				$query .= " AND a.fecha <= ?";
				$params[] = $filters['fechaSearchTo'];
			}
         if (!empty($filters['empleadoSearch'])) {
				$query .= " AND a.id_user LIKE ?";
				$params[] = '%' . $filters['empleadoSearch'] . '%';
			}

         if (!empty($filters['entregaSearchFrom'])) {
				$query .= " AND a.fecha_entrega >= ?";
				$params[] = $filters['entregaSearchFrom'];
			}
			if (!empty($filters['entregaSearchTo'])) {
				$query .= " AND a.fecha_entrega <= ?";
				$params[] = $filters['entregaSearchTo'];
			}

			if (!empty($filters['tituloSearch'])) {
				$query .= " AND a.titulo LIKE ?";
				$params[] = '%' . $filters['tituloSearch'] . '%';
			}

         $query .= " ORDER BY a.fecha DESC";
         $connection = Database::getConnection();
			$stmt = $connection->prepare($query);
			$stmt->execute($params);
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt = null;
			$success = json_encode([
				'success' => true,
				'data' => $result,
				'count' => count($result),
				'query' => $query,
				'applied_filters' => array_filter($filters, function($value) {
        			return !empty($value);
				})
			]);

      } catch (Exception $e) {
			$code = $e->getCode();
			//http_response_code(is_numeric($code) ? (int)$code : 500);
			$success = json_encode([
				'success' => false,
				'data' => [],
				'count' => 0,
				'error' => $e->getMessage(),
				'code' => $code,
				'applied_filters' => array_filter($filters, function($value) {
        			return !empty($value);
				}),
				'trace' => $e->getTrace() // Solo para desarrollo
			]);
		}

      return json_decode($success);

   }


   //**************************************************************************************
   static public function getMisNotificaciones($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.*, b.name, c.name as user_name 
         FROM GrNotifi a
         LEFT JOIN users b ON a.id_user = b.id_user
         LEFT JOIN users c ON a.user_id_user = c.id_user
         WHERE a.id_empresa = :idEmpresa AND a.id_user = :id_user
      ";
      if (isset($data["revisada"])) {
         $sql .= " AND a.revisada = :revisada";
      }
      if (isset($data["status"])) {
         $sql .= " AND a.status = :status";
      }
      $sql .= " ORDER BY a.fecha_entrega DESC";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_user", $_SESSION["user_id"], PDO::PARAM_INT);
      if (isset($data["revisada"])) {
         $stmt->bindParam(":revisada", $data["revisada"], PDO::PARAM_INT);
      }
      if (isset($data["status"])) {
         $stmt->bindParam(":status", $data["status"], PDO::PARAM_INT);
      }
      $stmt->execute();
      $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;      
   }


   //**************************************************************************************
   static public function getOne($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.*, b.name, c.name as user_name 
         FROM GrNotifi a
         LEFT JOIN users b ON a.id_user = b.id_user
         LEFT JOIN users c ON a.user_id_user = c.id_user
         WHERE a.id_empresa = :idEmpresa AND a.id_notifi = :id_notifi
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_notifi", $data["id_notifi"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //**************************************************************************************
   static public function getLastNotificacion() {
      $connection = Database::getConnection();
      $tipo = 1;
      $sql = "SELECT * FROM GrNotifi 
         WHERE numero = (SELECT MAX(numero) FROM DvConsig 
         WHERE id_empresa = :idEmpresa AND tipo = :tipo)
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":tipo", $tipo, PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }

}