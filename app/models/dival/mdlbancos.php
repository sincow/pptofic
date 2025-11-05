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

class BancosModel {


   /**********************************************************************/
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT id_banco, codigo, nombre, iniciales, status, id_user, 
         creado_el, actualizado_el 
         FROM DvBancos
         WHERE id_empresa = :id_empresa
         ORDER BY nombre"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   /**********************************************************************/
   static public function getWhere($data) {
      $listWhere = json_decode($data["listWhere"], true);
      $status = "1";
      $where = "a.id_empresa = :id_empresa AND ";
      foreach ($listWhere as $key => $value) {
         $where .= $value["id"] . " = :" . $value["id"] . " AND ";
      }
      if ($where != "") {
         $where = substr($where, 0, -4);
         $where = " AND " . $where;
      }
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.id_banco, a.codigo, a.nombre, a.iniciales, a.status, a.id_user, 
         a.creado_el, a.actualizado_el  
         FROM DvBancos a 
         WHERE 1 = 1 " . $where . "
         ORDER BY a.nombre"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
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
      $stmt = $connection->prepare("SELECT a.id_banco, a.codigo, a.nombre, a.iniciales, a.status, a.id_user, 
         a.creado_el, a.actualizado_el 
         FROM DvBancos a 
         WHERE a.id_empresa = :id_empresa AND id_banco = :id"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id", $id, PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }

}