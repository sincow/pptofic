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

class ReportsModel {

   //**************************************************************************************
   static public function repDocument($data){
      $connection = Database::getConnection();
      $sql = "SELECT * FROM DvCheque WHERE id_empresa = :id_empresa AND clase = :clase AND consecutivo = :consecutivo";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":clase", $data["clase"]);
      $stmt->bindParam(":consecutivo", $data["repNroDomum"]);
      $stmt->execute();
      $resp = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }

}