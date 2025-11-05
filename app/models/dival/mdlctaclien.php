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

class CtaclienModel {


   //*************************************************************************************************
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT a.id_bancli, a.id_dvcliente, a.id_banco, a.sucursal, a.numero_cuenta, 
         a.status, a.id_user, a.creado_el, a.actualizado_el, b.nombre as BanNomNa, b.codigo, c.TerDocId, e.TerNombr  
         FROM DvBanCli a 
         LEFT JOIN DvBancos  b ON a.id_empresa = b.id_empresa AND a.id_banco = b.id_banco 
         LEFT JOIN DvClient  c ON a.id_empresa = c.id_empresa AND a.id_dvcliente = c.id_dvcliente 
         LEFT JOIN companies d ON a.id_empresa = d.id_empresa
         LEFT JOIN CoTercer  e ON d.EmpCodig = e.EmpCodig AND c.TerDocId = e.TerDocId 
         WHERE a.id_empresa = :id_empresa 
         ORDER BY a.id_dvcliente, a.id_banco, a.sucursal, a.numero_cuenta"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   //*************************************************************************************************
   static public function getOne($id) {
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.id_bancli, a.id_dvcliente, a.id_banco, a.sucursal, a.numero_cuenta, 
         a.status, a.id_user, a.creado_el, a.actualizado_el, b.nombre as BanNomNa, b.codigo, c.TerDocId, e.TerNombr  
         FROM DvBanCli a 
         LEFT JOIN DvBancos  b ON a.id_empresa = b.id_empresa AND a.id_banco = b.id_banco 
         LEFT JOIN DvClient  c ON a.id_empresa = c.id_empresa AND a.id_dvcliente = c.id_dvcliente 
         LEFT JOIN companies d ON a.id_empresa = d.id_empresa
         LEFT JOIN CoTercer  e ON d.EmpCodig = e.EmpCodig AND c.TerDocId = e.TerDocId 
         WHERE a.id_empresa = :id_empresa AND a.id_bancli = :id_bancli
         ORDER BY a.id_dvcliente, a.id_banco, a.sucursal, a.numero_cuenta"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_bancli", $id, PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   //**********************************************************************************************
   static function getWhere($data) {
      $listWhere = json_decode($data["listWhere"], true);
      $where = "a.id_empresa = :id_empresa" . " AND ";
      foreach ($listWhere as $key => $value) {
         $where .= "a.".$value["id"] . " = :" . $value["id"] . " AND ";
      }
      if ($where != "") {
         $where = substr($where, 0, -4);
         $where = " AND " . $where;
      }
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.id_bancli, a.id_dvcliente, a.id_banco, a.sucursal, a.numero_cuenta, 
         a.status, a.id_user, a.creado_el, a.actualizado_el, b.nombre as BanNomNa, b.codigo, c.TerDocId, e.TerNombr, 
         e.TerEmail 
         FROM DvBanCli a 
         LEFT JOIN DvBancos  b ON a.id_empresa = b.id_empresa AND a.id_banco = b.id_banco 
         LEFT JOIN DvClient  c ON a.id_empresa = c.id_empresa AND a.id_dvcliente = c.id_dvcliente 
         LEFT JOIN companies d ON a.id_empresa = d.id_empresa
         LEFT JOIN CoTercer  e ON d.EmpCodig = e.EmpCodig AND c.TerDocId = e.TerDocId 
         WHERE 1 = 1 " . $where . "
         ORDER BY a.id_dvcliente, a.id_banco, a.sucursal, a.numero_cuenta"
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

      /*
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.id_bancli, a.id_dvcliente, a.id_banco, a.sucursal, a.numero_cuenta, 
         a.status, a.id_user, a.creado_el, a.actualizado_el, b.nombre as BanNomNa, b.codigo, c.TerDocId, e.TerNombr, 
         e.TerEmail 
         FROM DvBanCli a 
         LEFT JOIN DvBancos  b ON a.id_empresa = b.id_empresa AND a.id_banco = b.id_banco 
         LEFT JOIN DvClient  c ON a.id_empresa = c.id_empresa AND a.id_dvcliente = c.id_dvcliente 
         LEFT JOIN companies d ON a.id_empresa = d.id_empresa
         LEFT JOIN CoTercer  e ON d.EmpCodig = e.EmpCodig AND c.TerDocId = e.TerDocId 
         WHERE a.id_empresa = :id_empresa AND a.id_dvcliente = :id_dvcliente 
         ORDER BY a.id_dvcliente, a.id_banco, a.sucursal, a.numero_cuenta"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_dvcliente", $data["id_dvcliente"], PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
      */
   }


   //*************************************************************************************************
   static public function searchCtaClienANT($data) {
      $searchTerm = trim($data["searchTerm"]);
      $connection = Database::getConnection();
      $sql = "SELECT a.id_bancli, a.id_dvcliente, a.id_banco, a.sucursal, a.numero_cuenta, 
         a.status, a.id_user, a.creado_el, a.actualizado_el, b.nombre as BanNomNa, b.codigo, c.TerDocId, e.TerNombr, 
         e.TerEmail 
         FROM DvBanCli a 
         LEFT JOIN DvBancos  b ON a.id_empresa = b.id_empresa AND a.id_banco = b.id_banco 
         LEFT JOIN DvClient  c ON a.id_empresa = c.id_empresa AND a.id_dvcliente = c.id_dvcliente 
         LEFT JOIN companies d ON a.id_empresa = d.id_empresa
         LEFT JOIN CoTercer  e ON d.EmpCodig = e.EmpCodig AND c.TerDocId = e.TerDocId 
         WHERE (e.TerNombr LIKE :search1 OR e.TerDocId LIKE :search2 OR e.TerEmail LIKE :search3) AND 
         a.id_empresa = :id_empresa AND a.status = '1' 
         ORDER BY a.id_dvcliente, a.id_banco, a.sucursal, a.numero_cuenta";
      $stmt = $connection->prepare($sql);
      for ($i = 1; $i <= 3; $i++) {
         $stmt->bindValue(":search$i", "%$searchTerm%", PDO::PARAM_STR);
      }
      $stmt->bindValue(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


}