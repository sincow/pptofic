<?php
if (!defined('CONFIG_PATH')) {
   define("CONFIG_PATH", "../config");
}
require_once CONFIG_PATH . "/Database.php";
if (!isset($_SESSION)) {
	session_start();
}

class OrdenadorGastoModel {

   /**********************************************************************/
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT o.TerceroId, t.TerNombr, o.Cargo, t.TerDirec, t.TerTele1, t.TerTele2, o.Vigente, o.Estado,  
                                             o.UsuarioId, o.FechaCreacion, o.FechaModificacion
         FROM PoOrdenadorGasto o
         INNER JOIN coTercer t ON o.EmpresaId = t.EmpCodig AND o.TerceroId = t.TerDocId
         WHERE o.EmpresaId = :id_empresa
         ORDER BY t.TerNombr"
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
      $stmt = $connection->prepare("SELECT a.TerceroId, t.TerNombr, a.Cargo, t.TerDirec, t.TerTele1, t.TerTele2, a.Vigente, a.Estado,  
                                             a.UsuarioId, a.FechaCreacion, a.FechaModificacion
         FROM PoOrdenadorGasto a
         INNER JOIN coTercer t ON a.EmpresaId = t.EmpCodig AND a.TerceroId = t.TerDocId
         WHERE 1 = 1 " . $where . "
         ORDER BY t.TerNombr"
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
      $stmt = $connection->prepare("SELECT a.TerceroId, t.TerNombr, a.Cargo, t.TerDirec, t.TerTele1, t.TerTele2, a.Vigente, a.Estado,  
         a.UsuarioId, a.FechaCreacion, a.FechaModificacion
         FROM PoOrdenadorGasto a 
         INNER JOIN coTercer t ON a.EmpresaId = t.EmpCodig AND a.TerceroId = t.TerDocId
         WHERE a.EmpresaId = :id_empresa AND a.TerceroId = :id"
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