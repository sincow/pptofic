<?php
if (!defined('CONFIG_PATH')) {
   // require_once "../config/config.php";
   define("CONFIG_PATH", "../config");
}
require_once CONFIG_PATH . "/Database.php";
if (!isset($_SESSION)) {
	session_start();
}
// $lang = Language::getInstance("../app/languages/");
// $lang->loadTranslations();

class ParametrosModel {


   /**********************************************************************/
   static public function getAll($data) {
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT * 
         FROM GrParame
         WHERE EmpCodig = :id_empresa AND ModCodig = :ModCodig
         ORDER BY ParObjeto"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["empdef"], PDO::PARAM_STR);
      $stmt->bindParam(":ModCodig", $data["modcodig"], PDO::PARAM_STR);
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   /**********************************************************************/
   static public function getWhere($data) {
      $listWhere = json_decode($data["listWhere"], true);
      $where = "EmpCodig = :id_empresa AND ";
      foreach ($listWhere as $key => $value) {
         $where .= $value["id"] . " = :" . $value["id"] . " AND ";
      }
      if ($where != "") {
         $where = substr($where, 0, -4);
         $where = " AND " . $where;
      }
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT * 
         FROM GrParame  
         WHERE 1 = 1 " . $where . "
         ORDER BY ParCodig"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_STR);
      foreach ($listWhere as $key => $value) {
         $stmt->bindParam(":" . $value["id"], $value["value"]);
      }

      //$stmt->debugDumpParams();   // Agregar esta línea para depuración
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   //******************************************************************************
   static public function getOne($id) {
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.*
         FROM GrParame a 
         WHERE a.EmpCodig = :id_empresa AND a.ParCodig = :id"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id", $id, PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   //******************************************************************************
   static public function save($data) {
      $paramsList = json_decode($data["paramsList"], true);
      $ModCodig = $data["modcodig"];

      try {
         $conn = Database::getConnection();
         $conn->beginTransaction();
         $stmt = $conn->prepare("INSERT INTO GrParame (EmpCodig, ModCodig, ParCodig, ParValor, 
            ParNombr, ParObjeto)
			   values (:EmpCodig, :ModCodig, :ParCodig, :ParValor, :ParNombr, :ParObjeto)
				ON DUPLICATE KEY UPDATE ParValor = :ParValor2, ParObjeto = :ParObjeto2 "
         );
         $stmt->bindParam(":EmpCodig", $_SESSION["empdef"], PDO::PARAM_STR);
         $stmt->bindParam(":ModCodig", $ModCodig, PDO::PARAM_STR);
         foreach ($paramsList as $key => $value) {
				$stmt->bindValue(":ParCodig", $value["ParCodig"], PDO::PARAM_STR);
				$stmt->bindValue(":ParValor", $value["ParValor"], PDO::PARAM_STR);
				$stmt->bindValue(":ParValor2",$value["ParValor"], PDO::PARAM_STR);
				$stmt->bindValue(":ParNombr", $value["ParNombr"], PDO::PARAM_STR);
				$stmt->bindValue(":ParObjeto", $value["ParObjeto"], PDO::PARAM_STR);
				$stmt->bindValue(":ParObjeto2", $value["ParObjeto"], PDO::PARAM_STR);
            $stmt->execute();
         }
         $conn->commit();
         $response = array("success" => true, "message" => "Parametros actualizados correctamente");
      } catch (PDOException $ex) {
			$conn->rollBack();
			$errorInfo = GeneralController::handleMySQLerror($conn->errorInfo()[1], $conn->errorInfo()[2]);
			if (ENVIRONMENT == 'development') {
				$messageError = $errorInfo["error_code"]." ".$errorInfo["technical_message"];
			} else {
				$messageError = $errorInfo["error_code"]." ".$errorInfo["error_message"];
			}
			$response = array("success" => false, "message" => $messageError, "code" => $errorInfo["error_code"]);
      }
      $conn = null;
      return $response;
   }

}