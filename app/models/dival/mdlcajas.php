<?php
if (!defined('CONFIG_PATH')) {
   // require_once "../config/config.php";
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

class CajasModel {

   //**************************************************************************************
	static public function filter($data) {
		try {
			$filters = [];
			$params = [];
			//$requestToken = $_SERVER['HTTP_X_CSRF_TOKEN'];
			// var_dump($requestToken);
			// var_dump($_POST['pyt']);
			// var_dump($_SESSION['csrf_token']);
			// if (!validateCSRFToken($requestToken ?? '')) {
			// 	throw new Exception('Invalid CSRF token', 419);
			// }
			$filters = [
            'empresaSearch' => isset($_SESSION["id_empresa"]) ? $_SESSION["id_empresa"] : null,
				'numberSearch' => isset($data['numberSearch']) ? trim($data['numberSearch']) : null,
            'tipoSearch' => isset($data['tipoSearch']) ? trim($data['tipoSearch']) : null,
            'dateFromSearch' => isset($data['dateFromSearch']) && !empty($data['dateFromSearch']) ? DateTime::createFromFormat(DATE_FORMAT, $data['dateFromSearch'])->format('Y-m-d') : null,
            'dateToSearch' => isset($data['dateToSearch']) && !empty($data['dateToSearch']) ? DateTime::createFromFormat(DATE_FORMAT, $data['dateToSearch'])->format('Y-m-d') : null,
            'terceroSearch' => isset($data['terceroSearch']) ? trim($data['terceroSearch']) : null,
				'minValueSearch' => isset($data['minValueSearch']) && is_numeric($data['minValueSearch']) ? (float)$data['minValueSearch'] : null,
				'maxValueSearch' => isset($data['maxValueSearch']) && is_numeric($data['maxValueSearch']) ? (float)$data['maxValueSearch'] : null,

				'poWarehouseSearch' => isset($data['poWarehouseSearch']) && is_numeric($data['poWarehouseSearch']) ? (int)$data['poWarehouseSearch'] : null,
			];

         $query = "SELECT a.*, c.TerNombr, d.CueNombr, ifnull(e.BanNombr, '') as BanNombr, f.name 
            FROM DvMovCaj a 
            LEFT JOIN companies b ON a.id_empresa = b.id_empresa
            LEFT JOIN CoTercer  c ON b.EmpCodig = c.EmpCodig AND a.TerDocId = c.TerDocId 
            LEFT JOIN CoPlaCue  d ON b.EmpCodig = d.EmpCodig AND a.CueCodig = d.CueCodig 
            LEFT JOIN BaCuenta  e ON b.EmpCodig = e.EmpCodig AND a.BanCodig = e.BanCodig
            LEFT JOIN users     f ON a.id_empresa = f.id_empresa AND a.id_user = f.id_user
            WHERE 1=1";

         if (!empty($filters['empresaSearch'])) {
				$query .= " AND a.id_empresa = ?";
				$params[] = $filters['empresaSearch'];
			}
			if (!empty($filters['numberSearch'])) {
				$query .= " AND a.id_movimiento = ?";
				$params[] = $filters['numberSearch'];
			}
         if (!empty($filters['tipoSearch'])) {
            if ($filters['tipoSearch'] == '*') {
               $query .= " AND a.tipo_movimiento <> ?";
            } else {               
               $query .= " AND a.tipo_movimiento = ?";
            }
				$params[] = $filters['tipoSearch'];
         }
         if (!empty($filters['dateFromSearch'])) {
				$query .= " AND a.fecha >= ?";
				$params[] = $filters['dateFromSearch'];
			}
			if (!empty($filters['dateToSearch'])) {
				$query .= " AND a.fecha <= ?";
				$params[] = $filters['dateToSearch'];
			}
         if (!empty($filters['terceroSearch'])) {
				$query .= " AND a.TerDocId LIKE ?";
				$params[] = '%' . $filters['terceroSearch'] . '%';
			}
			if (!empty($filters['fecVencimSearch'])) {
				$query .= " AND a.vencimiento <= ?";
				$params[] = $filters['fecVencimSearch'];
			}
			if (!empty($filters['minValueSearch'])) {
				$query .= " AND (a.valor_entrada >= ?";
				$params[] = $filters['minValueSearch'];
				// $params[] = $filters['minValueSearch'];
			}
			if (!empty($filters['maxValueSearch'])) {
				$query .= " AND a.valor_entrada <= ?)";
				$params[] = $filters['maxValueSearch'];
				// $params[] = $filters['maxValueSearch'];
         } else {
            if (!empty($filters['minValueSearch'])) {
               $query .= " )";
            }
         }

			if (!empty($filters['minValueSearch'])) {
				$query .= " OR (a.valor_salida >= ?";
				$params[] = $filters['minValueSearch'];
				// $params[] = $filters['minValueSearch'];
			}
			if (!empty($filters['maxValueSearch'])) {
				$query .= " AND a.valor_salida <= ?)";
				$params[] = $filters['maxValueSearch'];
				// $params[] = $filters['maxValueSearch'];
			} else {
            if (!empty($filters['minValueSearch'])) {
               $query .= " )";
            }
         }



         // if (!empty($filters['minValueSalSearch'])) {
			// 	$query .= " AND a.valor_salida >= ?";
			// 	$params[] = $filters['minValueSalSearch'];
			// }
			// if (!empty($filters['maxValueSalSearch'])) {
			// 	$query .= " AND a.valor_salida <= ?";
			// 	$params[] = $filters['maxValueSalSearch'];
			// }

         $query .= " ORDER BY a.fecha DESC, a.id_movimiento DESC LIMIT 100";
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


   //*********************************************************************************************
   static public function getLastVale() { 
      $connection = Database::getConnection();
      $sql = "SELECT * FROM DvMovCaj 
         WHERE consecutivo = (SELECT MAX(consecutivo) FROM DvMovCaj 
         WHERE id_empresa = :idEmpresa)
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //*********************************************************************************************
   static public function getOne($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.*, c.TerNombr, d.CueNombr, ifnull(e.BanNombr, '') as BanNombr, f.name 
         FROM DvMovCaj a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa
         LEFT JOIN CoTercer  c ON b.EmpCodig = c.EmpCodig AND a.TerDocId = c.TerDocId 
         LEFT JOIN CoPlaCue  d ON b.EmpCodig = d.EmpCodig AND a.CueCodig = d.CueCodig 
         LEFT JOIN BaCuenta  e ON b.EmpCodig = e.EmpCodig AND a.BanCodig = e.BanCodig
         LEFT JOIN users     f ON a.id_empresa = f.id_empresa AND a.id_user = f.id_user
         WHERE a.id_empresa = :idEmpresa AND a.id_movimiento = :id_movimiento
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_movimiento", $data["id_movimiento"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //*********************************************************************************************
   static public function getByNum($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.*, c.TerNombr, d.CueNombr, ifnull(e.BanNombr, '') as BanNombr, f.name 
         FROM DvMovCaj a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa
         LEFT JOIN CoTercer  c ON b.EmpCodig = c.EmpCodig AND a.TerDocId = c.TerDocId 
         LEFT JOIN CoPlaCue  d ON b.EmpCodig = d.EmpCodig AND a.CueCodig = d.CueCodig 
         LEFT JOIN BaCuenta  e ON b.EmpCodig = e.EmpCodig AND a.BanCodig = e.BanCodig
         LEFT JOIN users     f ON a.id_empresa = f.id_empresa AND a.id_user = f.id_user
         WHERE a.id_empresa = :idEmpresa AND a.tipo_movimiento = :tipo_movimiento AND a.consecutivo = :consecutivo
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":tipo_movimiento", $data["tipoDoc"], PDO::PARAM_INT);
      $stmt->bindParam(":consecutivo", $data["numDocum"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }



}