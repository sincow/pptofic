<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/contabilidad/mdlcentrocto.php';
}

class CentroCtoController {

   //*****************************************************************************************************
   static public function index(){
      $centrocto = CentroCtoModel::getAll(null);
      return $centrocto;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $centrocto = CentroCtoModel::getWhere($data);
      return $centrocto;
   }



   //*****************************************************************************************************
   static public function getOne($id) {
      $centrocto = CentroCtoModel::getOne($id);
      return $centrocto;
   }


}