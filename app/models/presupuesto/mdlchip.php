<?php
if (!defined('CONFIG_PATH')) {
   define("CONFIG_PATH", "../config");
}
require_once CONFIG_PATH . "/Database.php";
if (!isset($_SESSION)) {
	session_start();
}

class ChipModel {

   /**********************************************************************/
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT ChipId, Nombre, UsuarioId 
         FROM pochip
         WHERE EmpresaId = :id_empresa
         ORDER BY nombre"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["empdef"], PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   /**********************************************************************/
   static public function getWhere($data) {
      $listWhere = json_decode($data["listWhere"], true);
      $where = "a.EmpresaId = :id_empresa AND ";
      foreach ($listWhere as $key => $value) {
         $where .= $value["id"] . " = :" . $value["id"] . " AND ";
      }
      if ($where != "") {
         $where = substr($where, 0, -4);
         $where = " AND " . $where;
      }
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.ChipId, a.Nombre, a.UsuarioId, 
         FROM pochip a 
         WHERE 1 = 1 " . $where . "
         ORDER BY a.nombre"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["empdef"], PDO::PARAM_INT);
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
      $stmt = $connection->prepare("SELECT a.ChipId, a.Nombre, a.UsuarioId, 
         FROM pochip a 
         WHERE a.EmpresaId = :id_empresa AND ChipId = :id"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["empdef"], PDO::PARAM_INT);
      $stmt->bindParam(":id", $id, PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }

}