<?php
if (!defined('CONFIG_PATH')) {
   // require_once "../config/config.php";
   define("CONFIG_PATH", "../config");
}
if (!isset($_SESSION)) {
	session_start();
}

if (isset($_SESSION['reportPath'])) {
   require_once $_SESSION['reportPath'].CONFIG_PATH . "/Database.php";
} else {
   require_once CONFIG_PATH . "/Database.php";
}
// $lang = Language::getInstance("../app/languages/");
// $lang->loadTranslations();

class CajasModel {


   //*********************************************************************************************
   static public function getLastVale() { 
      $connection = Database::getConnection();
      $sql = "SELECT * FROM DvMovCaj 
         WHERE consecutivo = (SELECT MAX(consecutivo) FROM DvMovCaj 
         WHERE id_empresa = :idEmpresa)
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //*********************************************************************************************
   static public function getOne($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.*, c.TerNombr, d.CueNombr, e.name 
         FROM DvMovCaj a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa
         LEFT JOIN CoTercer  c ON b.EmpCodig = c.EmpCodig AND a.TerDocId = c.TerDocId 
         LEFT JOIN CoPlaCue  d ON b.EmpCodig = d.EmpCodig AND a.CueCodig = d.CueCodig 
         LEFT JOIN users     e ON a.id_empresa = e.id_empresa AND a.id_user = e.id_user
         WHERE a.id_empresa = :idEmpresa AND a.id_movimiento = :id_movimiento
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_movimiento", $data["id_movimiento"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //*********************************************************************************************
   static public function getByNum($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.*, c.TerNombr, d.CueNombr, ifnull(e.BanNombr, '') as BanNombr, f.name 
         FROM DvMovCaj a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa
         LEFT JOIN CoTercer  c ON b.EmpCodig = c.EmpCodig AND a.TerDocId = c.TerDocId 
         LEFT JOIN CoPlaCue  d ON b.EmpCodig = d.EmpCodig AND a.CueCodig = d.CueCodig 
         LEFT JOIN BaCuenta  e ON b.EmpCodig = e.EmpCodig AND a.BanCodig = e.BanCodig
         LEFT JOIN users     f ON a.id_empresa = f.id_empresa AND a.id_user = f.id_user
         WHERE a.id_empresa = :idEmpresa AND a.tipo_movimiento = :tipo_movimiento AND a.consecutivo = :consecutivo
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":tipo_movimiento", $data["tipoDoc"], PDO::PARAM_INT);
      $stmt->bindParam(":consecutivo", $data["numDocum"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }



}