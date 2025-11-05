<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/mdlbancos.php';
}

class BancosController {

   //*****************************************************************************************************
   static public function index(){
      $bancos = BancosModel::getAll(null);
      return $bancos;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $banco = BancosModel::getWhere($data);
      return $banco;
   }


   //*****************************************************************************************************
   static public function getOne($id) {
      $banco = BancosModel::getOne($id);
      return $banco;
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
      $data["iniciales"] = trim($data["iniciales"]);
      $data["iniciales"] = strip_tags($data["iniciales"]);
      $data["iniciales"] = strtoupper(htmlspecialchars($data["iniciales"], ENT_QUOTES, 'UTF-8'));
      $tabla = "DvBancos";
      $dataUpdt = array(
         "id_empresa" => $_SESSION["id_empresa"],
         "codigo"    => $data["codigo"], 
         "nombre"    => $data["nombre"], 
         "iniciales" => $data["iniciales"],
         "id_user"   => $_SESSION["user_id"]
      );
      $response = GeneralModel::save($tabla, $dataUpdt, null);
      return $response;
   }


   //*****************************************************************************************************
   static public function update() {
      $required = ['id', 'codigo', 'nombre'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      if (empty($data["id"]) || !filter_var($data["id"], FILTER_VALIDATE_INT)) {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      $data["id"] = filter_var($data["id"], FILTER_SANITIZE_NUMBER_INT);
      $data["nombre"] = trim($data["nombre"]);
      $data["nombre"] = strip_tags($data["nombre"]);
      $data["nombre"] = strtoupper(htmlspecialchars($data["nombre"], ENT_QUOTES, 'UTF-8'));
      $data["iniciales"] = trim($data["iniciales"]);
      $data["iniciales"] = strip_tags($data["iniciales"]);
      $data["iniciales"] = strtoupper(htmlspecialchars($data["iniciales"], ENT_QUOTES, 'UTF-8'));
      $tabla = "DvBancos";
      $dataUpdt = array(
         "codigo"    => $data["codigo"], 
         "nombre"    => $data["nombre"], 
         "iniciales" => $data["iniciales"],
         "id_user"   => $_SESSION["user_id"]
      );
      $where = array(
         "id_banco"   => $data["id"], 
         "id_empresa" => $_SESSION["id_empresa"]
      );
      $response = GeneralModel::update($tabla, $dataUpdt, $where, null);
      return $response;
   }


   //*****************************************************************************************************
   static public function delete() {
      $required = ['id', 'status'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      if (!filter_var($_POST["id"], FILTER_VALIDATE_INT)) {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      $idBanco = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
      $tabla = "DvBancos";
      $data =array(
         "status" => $_POST["status"]
      );
      $where = array(
         "id_empresa" => $_SESSION["id_empresa"],
         "id_banco"   => $idBanco
      );
      $response = GeneralModel::update($tabla, $data, $where, null);
      return $response;
   }
}