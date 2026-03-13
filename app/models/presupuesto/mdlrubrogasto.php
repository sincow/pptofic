<?php
if (!defined('CONFIG_PATH')) {
   // require_once "../config/config.php";
   define("CONFIG_PATH", "../config");
}
require_once CONFIG_PATH . "/Database.php";
if (!isset($_SESSION)) {
	session_start();
}

class RubroGastoModel {

   /**********************************************************************/
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT a.RubroGastoId, a.Nombre, a.TipoFinanciacionId, a.CtaPucId, a.UsuarioId, 
         a.FechaCreacion, a.FechaModificacion, a.Estado, ifnull(b.Nombre, 'N/A') as TipoFinanciacionNombre, a.Movimiento, a.RubroDependiente 
         FROM poRubroGasto a
               left join poTipoFinanciacion b on a.EmpresaId = b.EmpresaId AND a.TipoFinanciacionId = b.TipoFinanciacionId
         WHERE a.EmpresaId = :id_empresa
         ORDER BY a.Nombre"
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
      $stmt = $connection->prepare("SELECT a.RubroGastoId, a.Nombre, a.TipoFinanciacionId, a.CtaPucId, a.UsuarioId, 
         a.FechaCreacion, a.FechaModificacion, a.Estado, a RubroDependiente  
         FROM poRubroGasto a 
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
      $stmt = $connection->prepare("SELECT a.RubroGastoId, a.Nombre, a.TipoFinanciacionId, a.CtaPucId, a.UsuarioId, 
         a.FechaCreacion, a.FechaModificacion, a.Estado, a.RubroDependiente
         FROM poRubroGasto a 
         WHERE a.EmpresaId = :id_empresa AND a.RubroGastoId = :id"
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