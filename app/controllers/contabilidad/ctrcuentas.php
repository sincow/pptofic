<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/contabilidad/mdlcuentas.php';
}

class CuentasController {

   //*****************************************************************************************************
   static public function index(){
      $especies = CuentasModel::getAll(null);
      return $especies;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $especies = CuentasModel::getWhere($data);
      return $especies;
   }



   //*****************************************************************************************************
   static public function getOne($id) {
      $cuenta = CuentasModel::getOne($id);
      return $cuenta;
   }


}