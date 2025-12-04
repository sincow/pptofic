<?php
if (!defined('CONFIG_PATH')) {
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

class TareasModel {

   //**************************************************************************************
   static public function getAll() {
      $connection = Database::getConnection();
      $sql = "SELECT a.*, b.name 
         FROM GrNotifi a
         LEFT JOIN users b ON a.id_user = b.id_user
         WHERE a.id_empresa = :idEmpresa
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }



   //**************************************************************************************
   static public function getOne($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.*, b.name, c.name as user_name 
         FROM GrNotifi a
         LEFT JOIN users b ON a.id_user = b.id_user
         LEFT JOIN users c ON a.user_id_user = c.id_user
         WHERE a.id_empresa = :idEmpresa AND a.id_notifi = :id_notifi
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_notifi", $data["id_notifi"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //**************************************************************************************
   static public function getLastTarea() {
      $connection = Database::getConnection();
      $tipo = 1;
      $sql = "SELECT * FROM GrNotifi 
         WHERE numero = (SELECT MAX(numero) FROM DvConsig 
         WHERE id_empresa = :idEmpresa AND tipo = :tipo)
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":tipo", $tipo, PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }

}