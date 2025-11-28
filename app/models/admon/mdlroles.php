<?php
if (!defined('CONFIG_PATH')) {
   define("CONFIG_PATH", "../config");
}
require_once CONFIG_PATH . "/Database.php";
if (!isset($_SESSION)) {
	session_start();
}

class RolesModel {

   //*******************************************************************************************
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT a.id_role, a.description, a.status 
         FROM roles a 
         WHERE a.id_empresa = :id_empresa 
         ORDER BY a.description"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   //*******************************************************************************************
   static public function getOne($id) {
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.id_role, a.description, a.status 
         FROM roles a 
         WHERE a.id_empresa = :id_empresa AND a.id_role = :id_role"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_role", $id, PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }

}