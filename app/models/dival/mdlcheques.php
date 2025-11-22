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

class ChequesModel {

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
			// 	throw new Exception('Invalid CSRF token', 419);fecCambioSearchFrom
			// }
			$filters = [
            'empresaSearch' => isset($_SESSION["id_empresa"]) ? $_SESSION["id_empresa"] : null,
				'numberSearch' => isset($data['numberSearch']) ? trim($data['numberSearch']) : null,
            'statusSearch' => isset($data['statusSearch']) ? trim($data['statusSearch']) : null,
            'fecCambioSearchFrom' => isset($data['fecCambioSearchFrom']) && !empty($data['fecCambioSearchFrom']) ? DateTime::createFromFormat(DATE_FORMAT, $data['fecCambioSearchFrom'])->format('Y-m-d') : null,
            'fecCambioSearchTo' => isset($data['fecCambioSearchTo']) && !empty($data['fecCambioSearchTo']) ? DateTime::createFromFormat(DATE_FORMAT, $data['fecCambioSearchTo'])->format('Y-m-d') : null,
            'clienteSearch' => isset($data['clienteSearch']) ? trim($data['clienteSearch']) : null,
            'fecVencimSearch' => isset($data['fecVencimSearch']) && !empty($data['fecVencimSearch']) ? DateTime::createFromFormat(DATE_FORMAT, $data['fecVencimSearch'])->format('Y-m-d') : null,

            'poCompanySearch' => isset($data['poCompanySearch']) && is_numeric($data['poCompanySearch']) ? (int)$data['poCompanySearch'] : null,
				'poVendorSearch' => isset($data['poVendorSearch']) && is_numeric($data['poVendorSearch']) ? (int)$data['poVendorSearch'] : null,
				'poWarehouseSearch' => isset($data['poWarehouseSearch']) && is_numeric($data['poWarehouseSearch']) ? (int)$data['poWarehouseSearch'] : null,
				'poFromDateSearch' => isset($data['poFromDateSearch']) && !empty($data['poFromDateSearch']) ? DateTime::createFromFormat(DATE_FORMAT, $data['poFromDateSearch'])->format('Y-m-d') : null,
				'poToDateSearch' => isset($data['poToDateSearch']) && !empty($data['poToDateSearch']) ? DateTime::createFromFormat(DATE_FORMAT, $data['poToDateSearch'])->format('Y-m-d') : null,
				'poFromExpectedSearch' => isset($data['poFromExpectedSearch']) && !empty($data['poFromExpectedSearch']) ? DateTime::createFromFormat(DATE_FORMAT, $data['poFromExpectedSearch'])->format('Y-m-d') : null,
				'poToExpectedSearch' => isset($data['poToExpectedSearch']) && !empty($data['poToExpectedSearch']) ? DateTime::createFromFormat(DATE_FORMAT, $data['poToExpectedSearch'])->format('Y-m-d') : null,
				'poStatusSearch' => isset($data['poStatusSearch']) ? trim($data['poStatusSearch']) : null,
				'minCostSearch' => isset($data['minCostSearch']) && is_numeric($data['minCostSearch']) ? (float)$data['minCostSearch'] : null,
				'maxCostSearch' => isset($data['maxCostSearch']) && is_numeric($data['maxCostSearch']) ? (float)$data['maxCostSearch'] : null
			];

			$query = "SELECT a.*, c.TerNombr, g.TabNive6 as TerTiDoc, c.TerDirec, c.TerTele1, c.TerEmail, m.nivel_riezgo, 
            m.valor_cupo, m.valor_cupotemporal, 
            l.nombre AS banco_nombre, k.sucursal AS banco_sucursal, k.numero_cuenta AS banco_num_cuenta, 
            d.TerNombr as TerNombr2, h.TabNive6 as TerTiDoc2, d.TerDirec as TerDirec2, d.TerTele1 as TerTele12, 
            e.TerNombr as TerNombr3, i.TabNive6 as TerTiDoc3, e.TerDirec as TerDirec3, e.TerTele1 as TerTele13, 
            f.TerNombr as TerNombr4, j.TabNive6 as TerTiDoc4, f.TerDirec as TerDirec4, f.TerTele1 as TerTele14, 
            COALESCE((SELECT MAX(n.fecha) FROM DvAplaza n WHERE a.id_empresa = n.id_empresa AND a.id_cheque = n.id_cheque), a.vencimiento) as UltVenci 
            FROM DvCheque a 
            LEFT JOIN companies b ON a.id_empresa = b.id_empresa
            LEFT JOIN CoTercer  c FORCE INDEX (PRIMARY) ON c.EmpCodig = b.EmpCodig AND c.TerDocId = a.TerDocId 
            LEFT JOIN CoTercer  d ON d.EmpCodig = b.EmpCodig AND d.TerDocId = a.TerDocId2 
            LEFT JOIN CoTercer  e ON e.EmpCodig = b.EmpCodig AND e.TerDocId = a.TerDocId3 
            LEFT JOIN CoTercer  f ON f.EmpCodig = b.EmpCodig AND f.TerDocId = a.TerDocId4 
            LEFT JOIN GrTablas  g ON g.EmpCodig = '1' AND g.TabCodig = '01' AND c.TerTiDoc = g.TabNive1 
            LEFT JOIN GrTablas  h ON h.EmpCodig = '1' AND h.TabCodig = '01' AND d.TerTiDoc = h.TabNive1 
            LEFT JOIN GrTablas  i ON i.EmpCodig = '1' AND i.TabCodig = '01' AND e.TerTiDoc = i.TabNive1 
            LEFT JOIN GrTablas  j ON j.EmpCodig = '1' AND j.TabCodig = '01' AND f.TerTiDoc = j.TabNive1 
            LEFT JOIN DvBanCli  k ON a.id_empresa = k.id_empresa AND a.id_bancli = k.id_bancli 
            LEFT JOIN DvBancos  l ON k.id_empresa = l.id_empresa AND k.id_banco = l.id_banco 
            LEFT JOIN DvClient  m ON a.id_empresa = m.id_empresa AND a.id_dvcliente = m.id_dvcliente
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
         } else {
            $query .= " AND a.valor_cheque > a.capital_pagado";
         }
         if (!empty($filters['fecCambioSearchFrom'])) {
				$query .= " AND a.fecha >= ?";
				$params[] = $filters['fecCambioSearchFrom'];
			}
			if (!empty($filters['fecCambioSearchTo'])) {
				$query .= " AND a.fecha <= ?";
				$params[] = $filters['fecCambioSearchTo'];
			}
         if (!empty($filters['clienteSearch'])) {
				$query .= " AND c.TerDocId LIKE ?";
				$params[] = '%' . $filters['clienteSearch'] . '%';
			}
			if (!empty($filters['fecVencimSearch'])) {
				$query .= " AND a.vencimiento <= ?";
				$params[] = $filters['fecVencimSearch'];
			}
			if (!empty($filters['minCostSearch'])) {
				$query .= " AND a.valor_cheque - a.capital_pagado >= ?";
				$params[] = $filters['minCostSearch'];
			}
			if (!empty($filters['maxCostSearch'])) {
				$query .= " AND a.valor_cheque - a.capital_pagado <= ?";
				$params[] = $filters['maxCostSearch'];
			}

			$query .= " ORDER BY a.vencimiento ASC";
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
   static public function getLast($clase) {
      $connection = Database::getConnection();
      $sql = "SELECT * FROM DvCheque 
         WHERE id_cheque = (SELECT MAX(id_cheque) FROM DvCheque 
         WHERE id_empresa = :idEmpresa AND clase = :clase)
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":clase", $clase);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //**************************************************************************************
   static public function getOne($data, $by = 0) {
      $connection = Database::getConnection();
      $sql = "SELECT a.*, c.TerNombr, g.TabNive6 as TerTiDoc, c.TerDirec, c.TerTele1, c.TerEmail, m.nivel_riezgo, 
         m.valor_cupo, m.valor_cupotemporal, 
         l.nombre AS banco_nombre, k.sucursal AS banco_sucursal, k.numero_cuenta AS banco_num_cuenta, 
         d.TerNombr as TerNombr2, h.TabNive6 as TerTiDoc2, d.TerDirec as TerDirec2, d.TerTele1 as TerTele12, 
         e.TerNombr as TerNombr3, i.TabNive6 as TerTiDoc3, e.TerDirec as TerDirec3, e.TerTele1 as TerTele13, 
         f.TerNombr as TerNombr4, j.TabNive6 as TerTiDoc4, f.TerDirec as TerDirec4, f.TerTele1 as TerTele14, 
         COALESCE((SELECT MAX(n.fecha) FROM DvAplaza n WHERE a.id_empresa = n.id_empresa AND a.id_cheque = n.id_cheque), a.vencimiento) as UltVenci 
         FROM DvCheque a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa
         LEFT JOIN CoTercer  c ON b.EmpCodig = c.EmpCodig AND a.TerDocId = c.TerDocId 
         LEFT JOIN CoTercer  d ON b.EmpCodig = d.EmpCodig AND a.TerDocId2 = d.TerDocId 
         LEFT JOIN CoTercer  e ON b.EmpCodig = e.EmpCodig AND a.TerDocId3 = e.TerDocId 
         LEFT JOIN CoTercer  f ON b.EmpCodig = f.EmpCodig AND a.TerDocId4 = f.TerDocId 
         LEFT JOIN GrTablas  g ON c.TerTiDoc = g.TabNive1 AND g.TabCodig = '01'
         LEFT JOIN GrTablas  h ON d.TerTiDoc = h.TabNive1 AND h.TabCodig = '01'
         LEFT JOIN GrTablas  i ON e.TerTiDoc = i.TabNive1 AND i.TabCodig = '01'
         LEFT JOIN GrTablas  j ON f.TerTiDoc = j.TabNive1 AND j.TabCodig = '01'
         LEFT JOIN DvBanCli  k ON a.id_empresa = k.id_empresa AND a.id_bancli = k.id_bancli 
         LEFT JOIN DvBancos  l ON k.id_empresa = l.id_empresa AND k.id_banco = l.id_banco 
         LEFT JOIN DvClient  m ON a.id_empresa = m.id_empresa AND a.id_dvcliente = m.id_dvcliente
         WHERE a.id_empresa = :idEmpresa 
      ";
      if ($by == 0) {
         $sql .= " AND a.id_cheque = :id_cheque";
      } else if ($by == 1) {
         $sql .= " AND a.numero = :id_cheque";
      }
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_cheque", $data["id_cheque"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //**************************************************************************************
	static public function placomisi($data) {
      $connection = Database::getConnection();
      $query = "SELECT a.*, c.TerNombr, g.TabNive6 as TerTiDoc, c.TerDirec, c.TerTele1, c.TerEmail, m.nivel_riezgo, 
         m.valor_cupo, m.valor_cupotemporal, l.codigo AS banco_codigo,
         l.nombre AS banco_nombre, k.sucursal AS banco_sucursal, k.numero_cuenta AS banco_num_cuenta 
         FROM DvCheque a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa
         LEFT JOIN CoTercer  c ON c.EmpCodig = b.EmpCodig AND c.TerDocId = a.TerDocId 
         LEFT JOIN GrTablas  g ON g.EmpCodig = '1' AND g.TabCodig = '01' AND c.TerTiDoc = g.TabNive1 
         LEFT JOIN DvBanCli  k ON a.id_empresa = k.id_empresa AND a.id_bancli = k.id_bancli 
         LEFT JOIN DvBancos  l ON k.id_empresa = l.id_empresa AND k.id_banco = l.id_banco 
         LEFT JOIN DvClient  m ON a.id_empresa = m.id_empresa AND a.id_dvcliente = m.id_dvcliente
         WHERE a.id_empresa = :id_empresa AND a.fecha = :repFecPlanilla";

      $stmt = $connection->prepare($query);
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":repFecPlanilla", $data["fecCambioSearchFrom"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $response;
   }


   //**************************************************************************************
   static public function rephiscliente($data) {
      $connection = Database::getConnection();
      $query = "SELECT a.*, c.TerNombr, g.TabNive6 as TerTiDoc, c.TerDirec, c.TerTele1, c.TerEmail, m.nivel_riezgo, 
         m.valor_cupo, m.valor_cupotemporal, l.codigo AS banco_codigo,
         l.nombre AS banco_nombre, k.sucursal AS banco_sucursal, k.numero_cuenta AS banco_num_cuenta, 
         COALESCE((SELECT MAX(n.fecha) FROM DvAplaza n WHERE a.id_empresa = n.id_empresa AND a.id_cheque = n.id_cheque), a.vencimiento) as UltVenci 
         FROM DvCheque a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa
         LEFT JOIN CoTercer  c ON b.EmpCodig = c.EmpCodig AND a.TerDocId = c.TerDocId 
         LEFT JOIN GrTablas  g ON c.TerTiDoc = g.TabNive1 AND g.TabCodig = '01'
         LEFT JOIN DvBanCli  k ON a.id_empresa = k.id_empresa AND a.id_bancli = k.id_bancli 
         LEFT JOIN DvBancos  l ON k.id_empresa = l.id_empresa AND k.id_banco = l.id_banco 
         LEFT JOIN DvClient  m ON a.id_empresa = m.id_empresa AND a.id_dvcliente = m.id_dvcliente
         WHERE a.id_empresa = :id_empresa AND a.TerDocId = :TerDocId AND a.fecha between :repFecIniHisCli AND :repFecFinHisCli";
      $stmt = $connection->prepare($query);
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":TerDocId", $data["TerDocId"], PDO::PARAM_INT);
      $stmt->bindParam(":repFecIniHisCli", $data["repFecIniHisCli"]);
      $stmt->bindParam(":repFecFinHisCli", $data["repFecFinHisCli"]);
      $stmt->execute();
      $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $response;
   }

   //**************************************************************************************
   static public function getAplaza($dataApl) {
      $connection = Database::getConnection();
      $sql = "SELECT a.id_empresa, a.id_aplaza, a.id_cheque, a.fecha, a.dias_cobrar, a.valor_aplaza, 
         a.intereses, a.valor_interes, ifnull(a.motivo, '') as motivo, a.AplConta, a.status, a.id_user, 
         a.creado_el, a.actualizado_el 
         FROM DvAplaza a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa 
         WHERE a.id_empresa = :idEmpresa AND a.id_cheque = :id_cheque AND a.status = '1' 
         ORDER BY a.fecha DESC
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_cheque", $dataApl["id_cheque"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //**************************************************************************************
   static public function getConsignacion($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.*, c.*, d.CueCodig 
         FROM DvConsig a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa 
         LEFT JOIN DvCheque  c ON b.id_empresa = c.id_empresa AND a.id_cheque = c.id_cheque 
         LEFT JOIN BaCuenta  d ON b.EmpCodig = d.EmpCodig AND a.BanCodig = d.BanCodig 
         WHERE a.id_empresa = :idEmpresa AND a.id_cheque = :id_cheque AND a.status = '1'
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_cheque", $data["id_cheque"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //**************************************************************************************
   static public function getLastDevol() {
      $connection = Database::getConnection();
      $sql = "SELECT * FROM DvDevolu 
         WHERE id_devolu = (SELECT MAX(id_devolu) FROM DvDevolu 
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


   //**************************************************************************************
   static public function getLastPagCap() {
      $connection = Database::getConnection();
      $sql = "SELECT * FROM DvPagCap 
         WHERE consecutivo = (SELECT MAX(consecutivo) FROM DvPagCap 
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



   //**************************************************************************************
   static public function getLastPagInt() {
      $connection = Database::getConnection();
      $sql = "SELECT * FROM DvPagInt 
         WHERE consecutivo = (SELECT MAX(consecutivo) FROM DvPagInt 
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


   //**************************************************************************************
   static public function getDevolucion($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.id_empresa, a.id_devolu, a.consecutivo, a.id_consigna, a.id_cheque, a.fecha, 
         ifnull(a.motivo, '') as motivo, a.status, a.DevConta, a.id_user, a.creado_el, a.actualizado_el
         FROM DvDevolu a 
         WHERE a.id_empresa = :idEmpresa AND a.id_cheque = :id_cheque AND a.status = '1'
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_cheque", $data["id_cheque"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //**************************************************************************************
   static public function updtCheque($data, $tipo, $connection = null) {
      $conn = false;
		try {
         if ($connection == null) {
            $connection = Database::getConnection();
            $conn = true;
         }
         switch ($tipo) {
            case 1:
               $sql = "UPDATE DvCheque SET intereses_cobrados = intereses_cobrados + :valor 
                  WHERE id_empresa = :idEmpresa AND id_cheque = :id_cheque 
               ";
               break;
            case 2:
               $sql = "UPDATE DvCheque SET capital_pagado = capital_pagado + :valor 
                  WHERE id_empresa = :idEmpresa AND id_cheque = :id_cheque 
               ";
               break;
            case 3:
               $sql = "UPDATE DvCheque SET intereses_pagados = intereses_pagados + :valor 
                  WHERE id_empresa = :idEmpresa AND id_cheque = :id_cheque 
               ";
               break;
            default:
               # code...
               break;
         }
         $stmt = $connection->prepare($sql);
         $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
         $stmt->bindParam(":id_cheque", $data["id_cheque"], PDO::PARAM_INT);
         $stmt->bindParam(":valor",     $data["valor"], PDO::PARAM_INT);
         $stmt->execute();
         $response = array("success" => true, "message" => 'Registro guardado exitosamente');
		} catch (PDOException $e) {
			$errorInfo = GeneralController::handleMySQLerror($stmt->errorInfo()[1], $stmt->errorInfo()[2]);
			if (ENVIRONMENT == 'development1') {
				$messageError = $errorInfo["error_code"]." ".$errorInfo["technical_message"];
			} else {
				$messageError = $errorInfo["error_code"]." ".$errorInfo["error_message"];
			}
			$response = array("success" => false, "message" => $messageError, "code" =>  $errorInfo["error_code"]);
		}
		if ($conn == true) {
			$connection = null;
		}
		return $response;
   }


}