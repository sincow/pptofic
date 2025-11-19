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
         f.TerNombr as TerNombr4, j.TabNive6 as TerTiDoc4, f.TerDirec as TerDirec4, f.TerTele1 as TerTele14 
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
   static public function getAplaza($dataApl) {
      $connection = Database::getConnection();
      $sql = "SELECT a.* 
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
   static public function devolucion($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.* 
         FROM DvCheque a 
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