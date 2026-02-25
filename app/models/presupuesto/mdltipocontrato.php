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

class TipoContratoModel {


   /**********************************************************************/
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT TipoContratoId, Nombre, Iniciales, Estado, UsuarioId, 
         FechaCreacion, FechaModificacion
         FROM potipocontrato
         WHERE EmpresaId = :id_empresa
         ORDER BY Nombre"
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
      $where = "a.EmpresaId = :id_empresa AND ";
      foreach ($listWhere as $key => $value) {
         $where .= $value["id"] . " = :" . $value["id"] . " AND ";
      }
      if ($where != "") {
         $where = substr($where, 0, -4);
         $where = " AND " . $where;
      }
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.TipoContratoId, a.Nombre, a.Iniciales, a.Estado, a.UsuarioId, 
         a.FechaCreacion, a.FechaModificacion  
         FROM poTipoContrato a 
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
      $stmt = $connection->prepare("SELECT a.TipoContratoId, a.Nombre, a.Iniciales, a.Estado, a.UsuarioId, 
         a.FechaCreacion, a.FechaModificacion
         FROM poTipoContrato a 
         WHERE a.EmpresaId = :id_empresa AND a.TipoContratoId = :id"
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