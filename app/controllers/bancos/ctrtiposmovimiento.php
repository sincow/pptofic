<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/bancos/mdltiposmovimiento.php';
}

class TiposmovimientoController {

   //*****************************************************************************************************
   static public function index(){
      $especies = TiposmovimientoModel::getAll(null);
      return $especies;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $especies = TiposmovimientoModel::getWhere($data);
      return $especies;
   }


   //*****************************************************************************************************
   static public function getOne($id) {
      $client = TiposmovimientoModel::getOne("clients", "id", $id, null);
      return $client;
   }


}