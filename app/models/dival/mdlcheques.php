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
         m.valor_cupo, m.valor_cupotemporal, l.nombre AS banco_nombre, k.sucursal AS banco_sucursal, 
         k.numero_cuenta AS banco_num_cuenta, d.TerNombr as TerNombr2, h.TabNive6 as TerTiDoc2, d.TerDirec as TerDirec2, 
         d.TerTele1 as TerTele12, e.TerNombr as TerNombr3, i.TabNive6 as TerTiDoc3, e.TerDirec as TerDirec3, 
         e.TerTele1 as TerTele13, f.TerNombr as TerNombr4, j.TabNive6 as TerTiDoc4, f.TerDirec as TerDirec4, 
         f.TerTele1 as TerTele14, u.name, 
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
         LEFT JOIN users     u ON a.id_empresa = u.id_empresa AND a.id_user = u.id_user
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
         WHERE a.id_empresa = :id_empresa AND a.id_dvcliente = :TerDocId AND a.fecha between :repFecIniHisCli AND :repFecFinHisCli";
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
   static public function reppreliqui($data) {
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
         WHERE a.id_empresa = :id_empresa AND a.id_dvcliente = :id_dvcliente AND a.valor_cheque > 0 AND 
         a.valor_cheque >  a.capital_pagado AND a.status <> 'A'";
      $stmt = $connection->prepare($query);
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_dvcliente", $data["id_dvcliente"], PDO::PARAM_INT);
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


   //******************************************************************************************
   static public function getAplById($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.id_empresa, a.id_aplaza, a.id_cheque, a.fecha, a.dias_cobrar, a.valor_aplaza, 
         a.intereses, a.valor_interes, ifnull(a.motivo, '') as motivo, a.AplConta, a.status, a.id_user, 
         a.creado_el, a.actualizado_el 
         FROM DvAplaza a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa 
         WHERE a.id_empresa = :idEmpresa AND a.id_aplaza = :id_aplaza 
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_aplaza", $data["id_aplaza"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //******************************************************************************************
   static public function getPagoCapById($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.id_empresa, a.id_pago, a.consecutivo, a.id_cheque, a.fecha, a.valor, 
         a.PcaConta, a.status, a.id_user, a.creado_el, a.actualizado_el 
         FROM DvPagCap a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa 
         WHERE a.id_empresa = :idEmpresa AND a.id_pago = :id_pago 
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_pago", $data["id_pago"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //******************************************************************************************
   static public function getPagoIntById($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.id_empresa, a.id_pago, a.consecutivo, a.id_cheque, a.fecha, a.valor, 
         a.PinConta, a.status, a.id_user, a.creado_el, a.actualizado_el 
         FROM DvPagInt a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa 
         WHERE a.id_empresa = :idEmpresa AND a.id_pago = :id_pago 
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_pago", $data["id_pago"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //**************************************************************************************
   static public function getConsigDocum($data) {
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


   //**************************************************************************************
   static public function repplafectivo($data) {
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT '1' AS CtoCodig, c.numero, 'Pago Capital' AS CtoNombr, 
         a.consecutivo AS CtoConse, a.fecha AS CtoFecha, a.valor AS CtoValor, d.TerNombr 
         FROM DvPagCap a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa 
         LEFT JOIN DvCheque  c ON a.id_empresa = c.id_empresa AND a.id_cheque = c.id_cheque 
         LEFT JOIN CoTercer  d ON b.EmpCodig = d.EmpCodig AND c.TerDocId = d.TerDocId 
         WHERE a.id_empresa = :id_empresa AND a.fecha = :repFecPlanilla AND a.status <> 'A'"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":repFecPlanilla", $data["repFecPlanilla"], PDO::PARAM_STR);
      $stmt->execute();
      $responsePC = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $stmt = $connection->prepare("SELECT '2' AS CtoCodig, c.numero, 'Pago Intereses' AS CtoNombr, 
         a.consecutivo AS CtoConse, a.fecha AS CtoFecha, a.valor AS CtoValor, d.TerNombr 
         FROM DvPagInt a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa 
         LEFT JOIN DvCheque  c ON a.id_empresa = c.id_empresa AND a.id_cheque = c.id_cheque 
         LEFT JOIN CoTercer  d ON b.EmpCodig = d.EmpCodig AND c.TerDocId = d.TerDocId 
         WHERE a.id_empresa = :id_empresa AND a.fecha = :repFecPlanilla AND a.status <> 'A'"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":repFecPlanilla", $data["repFecPlanilla"], PDO::PARAM_STR);
      $stmt->execute();
      $responsePI = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $stmt = $connection->prepare("SELECT '3' AS CtoCodig, a.consecutivo AS numero, 
         CONCAT(a.CueCodig, ' ', a.descripcion) AS CtoNombr, a.consecutivo AS CtoConse, a.fecha AS CtoFecha, 
         a.valor_entrada AS CtoValor, d.TerNombr 
         FROM DvMovCaj a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa 
         LEFT JOIN CoTercer  d ON b.EmpCodig = d.EmpCodig AND a.TerDocId = d.TerDocId 
         WHERE a.id_empresa = :id_empresa AND a.fecha = :repFecPlanilla AND a.status <> 'A'"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":repFecPlanilla", $data["repFecPlanilla"], PDO::PARAM_STR);
      $stmt->execute();
      $responseMC = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $stmt = $connection->prepare("SELECT '0' AS CtoCodig, '' AS numero, 
         'EFECTIVO ANTERIOR' AS CtoNombr, '' AS CtoConse, a.fecha AS CtoFecha, 
         a.valor_contado AS CtoValor, '' AS TerNombr 
         FROM DvArcCaj a 
         WHERE a.id_empresa = :id_empresa AND a.fecha < :repFecPlanilla AND a.status <> 'A'
         ORDER BY a.fecha DESC LIMIT 1"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":repFecPlanilla", $data["repFecPlanilla"], PDO::PARAM_STR);
      $stmt->execute();
      $responseSE = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if ($responseSE == false) {
         $responseSE = array(
            "CtoCodig" => "0",
            "numero" => "",
            "CtoNombr" => "EFECTIVO ANTERIOR",
            "CtoConse" => "",
            "CtoFecha" => $data["repFecPlanilla"],
            "CtoValor" => 0,
            "TerNombr" => ""
         );
         $responseSE = array($responseSE);
      }
      $valSaldo = $responseSE[0]["CtoValor"];
      $cero = 0;
      $stmtAct = $connection->prepare("INSERT INTO DvArcCaj (id_empresa, fecha, valor_contado, valor_saldo, 
         status, id_user) VALUES (:id_empresa, :fecha, 0, :valor_saldo, '1', :id_user) 
         ON DUPLICATE KEY UPDATE valor_saldo = :valor_saldo2"
      );
      $stmtAct->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmtAct->bindParam(":fecha", $data["repFecPlanilla"], PDO::PARAM_STR);
      // $stmtAct->bindParam(":valor_contado", $cero, PDO::PARAM_INT);
      $stmtAct->bindParam(":valor_saldo", $valSaldo, PDO::PARAM_INT);
      $stmtAct->bindParam(":id_user", $_SESSION["user_id"], PDO::PARAM_INT);
      $stmtAct->bindParam(":valor_saldo2", $valSaldo, PDO::PARAM_STR);
      $stmtAct->execute();

      $response = array_merge($responseSE, $responsePC, $responsePI, $responseMC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //**************************************************************************************
   static public function repplaarqueo($data) {
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT '3' AS CtoCodig, a.consecutivo AS numero, 
         CONCAT(a.CueCodig, ' ', a.descripcion) AS CtoNombr, a.consecutivo AS CtoConse, a.fecha AS CtoFecha, 
         a.valor_salida AS CtoValor, d.TerNombr 
         FROM DvMovCaj a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa 
         LEFT JOIN CoTercer  d ON b.EmpCodig = d.EmpCodig AND a.TerDocId = d.TerDocId 
         WHERE a.id_empresa = :id_empresa AND a.fecha = :repFecPlanilla AND a.tipo_movimiento = '3' 
         AND a.status <> 'A'"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":repFecPlanilla", $data["repFecPlanilla"], PDO::PARAM_STR);
      $stmt->execute();
      $responseVC = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $valCon00 = $data["repValContado"];
      $valCon11 = 0;
      if ($responseVC) {
         foreach ($responseVC as $key => $value) {
            $valCon00 += $value["CtoValor"];
         }
      }
      
      $stmt = $connection->prepare("SELECT '0' AS CtoCodig, '' AS numero, 
         'EFECTIVO ANTERIOR' AS CtoNombr, '' AS CtoConse, a.fecha AS CtoFecha, 
         a.valor_contado AS CtoValor, a.valor_saldo AS ValorSaldo, '' AS TerNombr 
         FROM DvArcCaj a 
         WHERE a.id_empresa = :id_empresa AND a.fecha = :repFecPlanilla AND a.status <> 'A'
         ORDER BY a.fecha DESC LIMIT 1"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":repFecPlanilla", $data["repFecPlanilla"], PDO::PARAM_STR);
      $stmt->execute();
      $responsePE = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if ($responsePE == false) {
         return array("success" => false, "message" => "Debe generar primero la planilla de efectivo");
      }
      if ($responsePE) {
         $valCon11 += $responsePE[0]["ValorSaldo"];
      }

      $stmt = $connection->prepare("SELECT sum(valor_cheque) AS CheValor, 
         sum(round(valor_cheque * porcentaje_comision / 100 * dias_cobrados,0)) AS CheComis, 
	      sum(round(valor_cheque * impuesto_banco / 100,0)) AS CheImpBa 
         FROM DvCheque 
         WHERE id_empresa = :id_empresa AND fecha = :repFecPlanilla AND status <> 'A'"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":repFecPlanilla", $data["repFecPlanilla"], PDO::PARAM_STR);
      $stmt->execute();
      $responseDO = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if ($responseDO) {
         $valCon00 += $responseDO[0]["CheValor"];
         $valCon11 += $responseDO[0]["CheComis"];
         $valCon11 += $responseDO[0]["CheImpBa"];
      }
      if ($valCon00 == $valCon11) {
         $valActua = $data["repValContado"];
      } else {
         $valActua = 0;
      }
      if ($valCon00 != 0 || $valCon11 != 0) {
         $stmtAct = $connection->prepare("UPDATE DvArcCaj SET valor_contado = :valor_contado 
            WHERE id_empresa = :id_empresa AND fecha = :fecha"
         );
         $stmtAct->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
         $stmtAct->bindParam(":fecha", $data["repFecPlanilla"], PDO::PARAM_STR);
         $stmtAct->bindParam(":valor_contado", $valActua, PDO::PARAM_INT);
         $stmtAct->execute();
      }

      // $stmt = $connection->prepare("SELECT sum(valor_entrada) as EnEValor 
      //    FROM DvMovCaj 
	   //    WHERE id_empresa = :id_empresa AND fecha = :repFecPlanilla AND tipo_movimiento <> '3' AND status <> 'A'"
      // );
      // $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      // $stmt->bindParam(":repFecPlanilla", $data["repFecPlanilla"], PDO::PARAM_STR);
      // $stmt->execute();
      // $responseMC = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // $stmt = $connection->prepare("SELECT sum(valor) as PCaValor 
      //    FROM DvPagCap 
	   //    WHERE id_empresa = :id_empresa AND fecha = :repFecPlanilla AND status <> 'A'"
      // );
      // $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      // $stmt->bindParam(":repFecPlanilla", $data["repFecPlanilla"], PDO::PARAM_STR);
      // $stmt->execute();
      // $responsePC = $stmt->fetchAll(PDO::FETCH_ASSOC);
      // $stmt = $connection->prepare("SELECT sum(valor) as PInValor 
      //    FROM DvPagInt 
	   //    WHERE id_empresa = :id_empresa AND fecha = :repFecPlanilla AND status <> 'A'"
      // );
      // $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      // $stmt->bindParam(":repFecPlanilla", $data["repFecPlanilla"], PDO::PARAM_STR);
      // $stmt->execute();
      // $responsePI = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $response = array(
         "VC" => $responseVC,
         "PE" => $responsePE,
         "DO" => $responseDO
      );
      // "PC" => $responsePC,
      // "PI" => $responsePI
      // "MC" => $responseMC
      $connection = null;
      $stmt = null;
      return $response;
   }


   //******************************************************************************************
   static public function getDashborad() {
      $connection = Database::getConnection();
      $query = "SELECT 
         COALESCE(SUM(CASE WHEN month(fecha) = month(CURDATE()) AND year(fecha) = YEAR(CURDATE()) AND status <> 'A' THEN valor_cheque END), 0) AS valor_month,
         COALESCE(COUNT(CASE WHEN month(fecha) = month(CURDATE()) AND year(fecha) = YEAR(CURDATE()) AND status <> 'A' THEN id_cheque END), 0) AS count_month,
         COALESCE(SUM(CASE WHEN fecha = CURDATE() AND status <> 'A' THEN valor_cheque END), 0) AS valor_today,
         COALESCE(COUNT(CASE WHEN fecha = CURDATE() AND status <> 'A' THEN id_cheque END), 0) AS count_today,
         COALESCE(SUM(CASE WHEN status IN ('1', 'D') THEN valor_cheque END), 0) AS valor_pendiente,
         COALESCE(COUNT(CASE WHEN status IN ('1', 'D') THEN id_cheque END), 0) AS count_pendiente,
         COALESCE(SUM(CASE WHEN vencimiento <= CURDATE() AND status IN ('1', 'D') THEN valor_cheque END), 0) AS valor_vencim,
         COALESCE(COUNT(CASE WHEN vencimiento <= CURDATE() AND status IN ('1', 'D') THEN id_cheque END), 0) AS count_vencim
         FROM DvCheque 
         WHERE id_empresa = :id_empresa";
      $stmt = $connection->prepare($query);
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //******************************************************************************************
   static public function actInteres($data, $connection = null) {
		$conn = false;
		try {
			if ($connection == null) {
				$conn = true;
				$connection = Database::getConnection();
			}
         $sql = "UPDATE DvCheque SET intereses_cobrados = intereses_cobrados - :interes 
            WHERE id_empresa = :idEmpresa AND id_cheque = :id_cheque 
         ";
         $stmt = $connection->prepare($sql);
         $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
         $stmt->bindParam(":id_cheque", $data["id_cheque"], PDO::PARAM_INT);
         $stmt->bindParam(":interes", $data["intereses_cobrados"], PDO::PARAM_INT);
         $stmt->execute();
         $response = array("success" => true, "message" => 'Registro actualizado exitosamente');
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


   //******************************************************************************************
   static public function actPagoCap($data, $connection = null, $status = null) {
      $conn = false;
      try {
         if ($connection == null) {
            $conn = true;
            $connection = Database::getConnection();
         }
         if ($status != null) {
            $sql = "UPDATE DvCheque SET capital_pagado = capital_pagado - :capital, status = :status
               WHERE id_empresa = :idEmpresa AND id_cheque = :id_cheque 
            ";
         } else {
            $sql = "UPDATE DvCheque SET capital_pagado = capital_pagado - :capital 
               WHERE id_empresa = :idEmpresa AND id_cheque = :id_cheque 
            ";
         }
         $stmt = $connection->prepare($sql);
         $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
         $stmt->bindParam(":id_cheque", $data["id_cheque"], PDO::PARAM_INT);
         $stmt->bindParam(":capital", $data["capital_pagado"], PDO::PARAM_INT);
         if ($status != null) {
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
         }
         $stmt->execute();
         $response = array("success" => true, "message" => 'Registro actualizado exitosamente');
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


   //******************************************************************************************
   static public function actPagoInt($data, $connection = null) {
      $conn = false;
      try {
         if ($connection == null) {
            $conn = true;
            $connection = Database::getConnection();
         }
         $sql = "UPDATE DvCheque SET intereses_pagados = intereses_pagados - :interes 
            WHERE id_empresa = :idEmpresa AND id_cheque = :id_cheque 
         ";
         $stmt = $connection->prepare($sql);
         $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
         $stmt->bindParam(":id_cheque", $data["id_cheque"], PDO::PARAM_INT);
         $stmt->bindParam(":interes", $data["intereses_pagados"], PDO::PARAM_INT);
         $stmt->execute();
         $response = array("success" => true, "message" => 'Registro actualizado exitosamente');
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