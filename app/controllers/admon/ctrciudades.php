<?php
class CiudadesController {

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
         if ($listWhere[$key]["id"] == "CiuEstad") 
         $idTabla = $listWhere[$key]["value"];
      }
      if ($idTabla == "") {
         $response = array("success" => false, "message" => 'Debe especificar tabla a consultar');
         return $response;
      }
      $tabla = "GrCiudad"; 
      $order = "CiuNombr"; 
      $where = "CiuEstad = '".$idTabla."'";
      $tablas = GeneralModel::getAll($tabla, $order, $where);
      return $tablas;
   }

}