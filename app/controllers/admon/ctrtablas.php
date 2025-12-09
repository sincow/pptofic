<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/admon/mdlgeneral.php';
}

class TablasController {

   static public function getWhere(){
      $required = ['listWhere'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $listWhere = json_decode($data["listWhere"], true);
      $idTabla = "";
      foreach ($listWhere as $key => $value) {
         if ($listWhere[$key]["id"] == "TabCodig") 
         $idTabla = $listWhere[$key]["value"];
      }
      if ($idTabla == "") {
         $response = array("success" => false, "message" => 'Debe especificar tabla a consultar');
         return $response;
      }
      $tabla = "GrTablas"; 
      $order = "TabNive1"; 
      $where = "TabCodig = '".$idTabla."'";
      $tablas = GeneralModel::getAll($tabla, $order, $where);
      return $tablas;
   }

}