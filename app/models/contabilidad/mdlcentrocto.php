<?php
if (!defined('CONFIG_PATH')) {
   // require_once "../config/config.php";
   define("CONFIG_PATH", "../config");
}
require_once CONFIG_PATH . "/Database.php";
if (!isset($_SESSION)) {
	session_start();
}

class CentroCtoModel {


   /**********************************************************************/
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT * 
         FROM CoCenCos
         WHERE EmpCodig = :id_empresa
         ORDER BY CenDescr"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_STR);
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   /**********************************************************************/
   static public function getWhere($data) {

      $listWhere = json_decode($data["listWhere"], true);
      $where = "a.EmpCodig = :id_empresa AND ";
      foreach ($listWhere as $key => $value) {
         $where .= $value["id"] . " = :" . $value["id"] . " AND ";
      }
      if ($where != "") {
         $where = substr($where, 0, -4);
         $where = " AND " . $where;
      }
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.* 
         FROM CoCenCos a 
         WHERE 1 = 1 " . $where . "
         ORDER BY a.CenCodig"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_STR);
      foreach ($listWhere as $key => $value) {
         $stmt->bindParam(":" . $value["id"], $value["value"]);
      }
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   /**********************************************************************/
   static public function getOne($id) {
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT *
         FROM CoCenCos 
         WHERE EmpCodig = :id_empresa AND CenCodig = :CenCodig"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_STR);
      $stmt->bindParam(":CenCodig", $id, PDO::PARAM_STR);
      $stmt->execute();
      $resp = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }

}