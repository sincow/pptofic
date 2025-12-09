<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/contabilidad/mdlcomprobantes.php';
}

class ComprobantesController {

   //*****************************************************************************************************
   static public function index(){
      $especies = ComprobantesModel::getAll(null);
      return $especies;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $especies = ComprobantesModel::getWhere($data);
      return $especies;
   }


   //*****************************************************************************************************
   static public function getOne($id) {
      $client = ComprobantesModel::getOne("clients", "id", $id, null);
      return $client;
   }


}