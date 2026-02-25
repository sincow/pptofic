<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/presupuesto/mdlchip.php';
}

class ChipController {

   //*****************************************************************************************************
   static public function index(){
      $chips = ChipModel::getAll(null);
      return $chips;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $chip = ChipModel::getWhere($data);
      return $chip;
   }


   //*****************************************************************************************************
   static public function getOne($id) {
      $chip = ChipModel::getOne($id);
      return $chip   ;
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
      $tabla = "pochip";
      $dataUpdt = array(
         "EmpresaId" => $_SESSION["id_empresa"],
         "ChipId"    => $data["codigo"], 
         "Nombre"    => $data["nombre"], 
         "UsuarioId" => $_SESSION["user_id"]
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
         $response = array("success" => false, "message" => 'Registro inválido..');
         return $response;
      }
      $data["nombre"] = trim($data["nombre"]);
      $data["nombre"] = strip_tags($data["nombre"]);
      $data["nombre"] = strtoupper(htmlspecialchars($data["nombre"], ENT_QUOTES, 'UTF-8'));
      
      $tabla = "pochip";
      $dataUpdt = array(
         "Nombre"    => $data["nombre"], 
         "UsuarioId" => $_SESSION["user_id"]
      );
      $where = array(
         "ChipId"   => $data["codigo"], 
         "EmpresaId" => $_SESSION["id_empresa"]
      );
      $response = GeneralModel::update($tabla, $dataUpdt, $where, null);
      return $response;
   }


   //*****************************************************************************************************
   static public function delete() {
      $required = ['codigo'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      if (!filter_var($_POST["codigo"], FILTER_VALIDATE_INT)) {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      $idChip = filter_var($_POST["codigo"], FILTER_SANITIZE_NUMBER_INT);
      $tabla = "pochip";
      $data =array(
         "nombre" => $_POST["nombre"]
      );
      $where = array(
         "EmpresaId" => $_SESSION["id_empresa"],
         "ChipId"   => $idChip
      );
      $response = GeneralModel::update($tabla, $data, $where, null);
      return $response;
   }
}