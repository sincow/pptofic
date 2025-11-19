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

class CuentasModel {


   /**********************************************************************/
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT BanCodig, BanNombr, BanCuent, CueCodig, BanCodNa, 
         BanNomNa, BanFeApe, CheCodig, BanConCh, BanCodBa, BanEstad, UsuCodig, UsuFecCr, UsuFecAc 
         FROM BaCuenta
         WHERE EmpCodig = :id_empresa
         ORDER BY BanNombr"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["empdef"], PDO::PARAM_STR);
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
      $where = "a.EmpCodig = :id_empresa AND ";
      foreach ($listWhere as $key => $value) {
         $where .= $value["id"] . " = :" . $value["id"] . " AND ";
      }
      if ($where != "") {
         $where = substr($where, 0, -4);
         $where = " AND " . $where;
      }
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.BanCodig, a.BanNombr, a.BanCuent, a.CueCodig, a.BanCodNa, 
         a.BanNomNa, a.BanFeApe, a.CheCodig, a.BanConCh, a.BanCodBa, a.BanEstad, a.UsuCodig, a.UsuFecCr, a.UsuFecAc 
         FROM BaCuenta a 
         WHERE 1 = 1 " . $where . "
         ORDER BY a.BanNombr"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["empdef"], PDO::PARAM_STR);
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
      $stmt = $connection->prepare("SELECT a.BanCodig, a.BanNombr, a.BanCuent, a.CueCodig, a.BanCodNa, 
         a.BanNomNa, a.BanFeApe, a.CheCodig, a.BanConCh, a.BanCodBa, a.BanEstad, a.UsuCodig, a.UsuFecCr, a.UsuFecAc 
         FROM BaCuenta a 
         WHERE a.EmpCodig = :id_empresa AND BanCodig = :id"
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