<?php
class ActiviController {

   static public function getWhere(){
      $required = ['listWhere'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $listWhere = json_decode($data["listWhere"], true);
      $status = "";
      foreach ($listWhere as $key => $value) {
         if ($listWhere[$key]["id"] == "status") 
         $status = $listWhere[$key]["value"];
      }
      if ($status == "") {
         $response = array("success" => false, "message" => 'Debe especificar tabla a consultar');
         return $response;
      }
      $tabla = "DvActividad"; 
      $order = "nombre"; 
      $where = "status = '".$status."'";
      $tablas = GeneralModel::getAll($tabla, $order, $where);
      return $tablas;
   }

}