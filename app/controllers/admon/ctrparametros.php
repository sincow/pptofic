<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/mdlparametros.php';
}

class ParametrosController {

   //*****************************************************************************************************
   static public function index(){
      $params = ParametrosModel::getAll(null);
      return $params;
   }


   //*****************************************************************************************************
   static public function getAll(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $params = ParametrosModel::getAll($data);
      return $params;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $params = ParametrosModel::getWhere($data);
      return $params;
   }


   //*****************************************************************************************************
   static public function getOne($id) {
      $param = ParametrosModel::getOne("clients", "id", $id, null);
      return $param;
   }


   static public function save() {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $especies = ParametrosModel::save($data);
      return $especies;


   }


}