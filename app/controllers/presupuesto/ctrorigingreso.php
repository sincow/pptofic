<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/presupuesto/mdlorigingreso.php';
}

class OrigIngresoController {

   //*****************************************************************************************************
   static public function index(){
      $origingreso = OrigIngresoModel::getAll(null);
      return $origingreso;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $origingreso = OrigIngresoModel::getWhere($data);
      return $origingreso;
   }


   //*****************************************************************************************************
   static public function getOne($id) {
      $origingreso = OrigIngresoModel::getOne($id);
      return $origingreso;
   }


   //*****************************************************************************************************
   static public function create() {
      $required = ['codigo', 'nombre'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["nombre"] = trim($data["nombre"]);
      $data["nombre"] = strip_tags($data["nombre"]);
      $data["nombre"] = strtoupper(htmlspecialchars($data["nombre"], ENT_QUOTES, 'UTF-8'));
      
      $tabla = "poOrigIngreso";
      $dataUpdt = array(
         "EmpresaId" => $_SESSION["empdef"],
         "OrigIngresoId"    => $data["codigo"], 
         "Nombre"    => $data["nombre"], 
         "UsuarioId"   => $_SESSION["user_id"]
      );
      $response = GeneralModel::save($tabla, $dataUpdt, null);
      return $response;
   }


   //*****************************************************************************************************
   static public function update() {
      $required = ['codigo', 'nombre'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      if (empty($data["codigo"])) {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      
      $data["nombre"] = trim($data["nombre"]);
      $data["nombre"] = strip_tags($data["nombre"]);
      $data["nombre"] = strtoupper(htmlspecialchars($data["nombre"], ENT_QUOTES, 'UTF-8'));
      
      $tabla = "poorigingreso";
      $dataUpdt = array(
         "Nombre"       => $data["nombre"], 
         "UsuarioId"    => $_SESSION["user_id"]
      );
      $where = array(
         "OrigIngresoId"   => $data["codigo"], 
         "EmpresaId" => $_SESSION["empdef"]
      );
      $response = GeneralModel::update($tabla, $dataUpdt, $where, null);
      return $response;
   }


   //*****************************************************************************************************
   static public function delete() {
     
      $required = ['codigo', 'status'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      //$data = $_POST;
      if (empty($_POST["codigo"])) {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      $idIngreso = filter_var($_POST["codigo"], FILTER_SANITIZE_NUMBER_INT);
      $tabla = "poorigingreso";
      $data =array(
         "Estado" => $_POST["status"]
      );
      $where = array(
         "EmpresaId" => $_SESSION["empdef"],
         "OrigIngresoId"   => $idIngreso
      );
      $response = GeneralModel::update($tabla, $data, $where, null);
      return $response;
   }
}