<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/presupuesto/mdldependencia.php';
}

class DependenciaController {

   //*****************************************************************************************************
   static public function index(){
      $dependencia = DependenciaModel::getAll(null);
      return $dependencia;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $dependencia = DependenciaModel::getWhere($data);
      return $dependencia;
   }


   //*****************************************************************************************************
   static public function getOne($id) {
      $dependencia = DependenciaModel::getOne($id);
      return $dependencia;
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
     $data["centrocto"] = strip_tags($data["centrocto"]);
      $tabla = "poDependencia";
      $dataUpdt = array(
         "EmpresaId" => $_SESSION["empdef"],
         "DependenciaId"    => $data["codigo"], 
         "Nombre"    => $data["nombre"], 
         "Iniciales" => $data["iniciales"],
         "CentroCtoId" => $data["centrocto"],
         "UsuarioId"   => $_SESSION["user_id"]
      );

//       echo "<pre>";
// var_dump($dataUpdt);
// echo "</pre>";
// exit;
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
      if (empty($data["codigo"]) || !preg_match('/^[0-9]+$/', $data["codigo"])) {
         $response = array("success" => false, "message" => 'Registro inválido: ' . $data["codigo"]);
         return $response;
      }
      $data["id"] = filter_var($data["id"], FILTER_SANITIZE_NUMBER_INT);
      $data["codigo"] = filter_var($data["codigo"], FILTER_SANITIZE_NUMBER_INT);
      $data["nombre"] = trim($data["nombre"]);
      $data["nombre"] = strip_tags($data["nombre"]);
      $data["nombre"] = strtoupper(htmlspecialchars($data["nombre"], ENT_QUOTES, 'UTF-8'));
      $data["iniciales"] = trim($data["iniciales"]);
      $data["iniciales"] = strip_tags($data["iniciales"]);
      $data["iniciales"] = strtoupper(htmlspecialchars($data["iniciales"], ENT_QUOTES, 'UTF-8'));
      $data["centrocto"] = strip_tags($data["centrocto"]);
      $tabla = "poDependencia";
      $dataUpdt = array(
         "Nombre"    => $data["nombre"], 
         "Iniciales" => $data["iniciales"],
         "UsuarioId"   => $_SESSION["user_id"],
         "CentroCtoId" => $data["centrocto"]
      );
      $where = array(
         "DependenciaId"    => $data["codigo"], 
         "EmpresaId" => $_SESSION["empdef"]
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
      if (empty($_POST["id"]) || !preg_match('/^[0-9]+$/', $_POST["id"])) {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      $idDependencia = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
      $tabla = "poDependencia";
      $data =array(
         "Estado" => $_POST["status"]
      );
      $where = array(
         "EmpresaId" => $_SESSION["empdef"],
         "DependenciaId"   => $idDependencia
      );
      $response = GeneralModel::update($tabla, $data, $where, null);
      return $response;
   }
}