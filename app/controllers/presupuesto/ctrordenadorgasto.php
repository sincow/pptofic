<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/presupuesto/mdlordenadorgasto.php';
}

class OrdenadorGastoController {

   //*****************************************************************************************************
   static public function index(){
      $ordenador = OrdenadorGastoModel::getAll(null);
      return $ordenador;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $ordenador = OrdenadorGastoModel::getWhere($data);
      return $ordenador;
   }


   //*****************************************************************************************************
   static public function getOne($id) {
      $ordenador = OrdenadorGastoModel::getOne($id);
      return $ordenador;
   }


   //*****************************************************************************************************
   static public function create() {
      $required = ['id', 'cargo', 'nombre'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["id"] = trim($data["id"]);
      $data["id"] = strip_tags($data["id"]);
      $data["id"] = strtoupper(htmlspecialchars($data["id"], ENT_QUOTES, 'UTF-8'));
      
      $data["cargo"] = trim($data["cargo"]);
      $data["cargo"] = strip_tags($data["cargo"]);
      $data["cargo"] = strtoupper(htmlspecialchars($data["cargo"], ENT_QUOTES, 'UTF-8'));

      $vigente = isset($_POST["vigente"]) ? 1 : 0;

      if ($vigente == 1) {
         
         $tabla = "poOrdenadorGasto";
         $dataUpdt = array(
            "Vigente" => 0,
            "UsuarioId"   => $_SESSION["user_id"]
         );
         $where = array(
            "EmpresaId" => $_SESSION["empdef"]
         );
         $response = GeneralModel::update($tabla, $dataUpdt, $where, null);
         if ($response["success"] == false) {
            return $response;
         }
      }
      
      $tabla = "poOrdenadorGasto";
      $dataUpdt = array(
         "EmpresaId" => $_SESSION["empdef"],
         "TerceroId"    => $data["id"], 
         "Cargo"    => $data["cargo"], 
         "Vigente" => $vigente,
         "UsuarioId"   => $_SESSION["user_id"]
      );

      $response = GeneralModel::save($tabla, $dataUpdt, null);
      return $response;
   }


   //*****************************************************************************************************
   static public function update() {
      $required = ['id', 'cargo', 'nombre'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      if (empty($data["id"]) ) {
         $response = array("success" => false, "message" => 'Registro inválido: ' . $data["id"]);
         return $response;
      }
      $data["id"] = filter_var($data["id"], FILTER_SANITIZE_NUMBER_INT);
      $data["cargo"] = trim($data["cargo"]);
      $data["cargo"] = strip_tags($data["cargo"]);
      $data["cargo"] = strtoupper(htmlspecialchars($data["cargo"], ENT_QUOTES, 'UTF-8'));
            
      $vigente = isset($_POST["vigente"]) ? 1 : 0;

      if ($vigente == 1) {
         
         $tabla = "poOrdenadorGasto";
         $dataUpdt = array(
            "Vigente" => 0,
            "UsuarioId"   => $_SESSION["user_id"]
         );
         $where = array(
            "EmpresaId" => $_SESSION["empdef"]
         );
         $response = GeneralModel::update($tabla, $dataUpdt, $where, null);
         if ($response["success"] == false) {
            return $response;
         }
      }


      $tabla = "poOrdenadorGasto";
      $dataUpdt = array(
         "Cargo"    => $data["cargo"], 
         "Vigente" => $vigente,
         "UsuarioId"   => $_SESSION["user_id"]
      );
      $where = array(
         "TerceroId"    => $data["id"], 
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
      if (empty($_POST["id"]))  {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      $idOrdenador = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
      $tabla = "poOrdenadorGasto";
      $data =array(
         "Estado" => $_POST["status"]
      );
      $where = array(
         "EmpresaId" => $_SESSION["empdef"],
         "TerceroId"   => $idOrdenador
      );
      
      $response = GeneralModel::update($tabla, $data, $where, null);
      return $response;
   }
}