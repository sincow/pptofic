<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/presupuesto/mdlrubrogasto.php';
}

class RubroGastoController {

   //*****************************************************************************************************
   static public function index(){
      $rubrogasto = RubroGastoModel::getAll(null);
      return $rubrogasto;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $rubrogasto = RubroGastoModel::getWhere($data);
      return $rubrogasto;
   }


   //*****************************************************************************************************
   static public function getOne($id) {
      $rubrogasto = RubroGastoModel::getOne($id);
      return $rubrogasto;
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
      $data["tipofinanciacion"] = trim($data["tipofinanciacion"]);
      $data["tipofinanciacion"] = strip_tags($data["tipofinanciacion"]);
      $data["tipofinanciacion"] = strtoupper(htmlspecialchars($data["tipofinanciacion"], ENT_QUOTES, 'UTF-8'));
      $data["ctapucid"] = trim($data["ctapucid"]);
      $data["ctapucid"] = strip_tags($data["ctapucid"]);
      $data["ctapucid"] = strtoupper(htmlspecialchars($data["ctapucid"], ENT_QUOTES, 'UTF-8'));
      
      $movimiento = isset($_POST["movimiento"]) ? 1 : 0;

      $tabla = "poRubroGasto";
      $dataUpdt = array(
         "EmpresaId" => $_SESSION["id_empresa"],
         "RubroGastoId"    => $data["codigo"], 
         "Nombre"    => $data["nombre"], 
         "TipoFinanciacionId" => $data["tipofinanciacion"],
         "CtaPucId" => $data["ctapucid"],
         "Movimiento" => $movimiento,
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
      if (empty(trim($data["codigo"])) || !preg_match('/^[0-9]+$/', trim($data["codigo"]))) {
         $response = array("success" => false, "message" => 'Registro inválido: ' . $data["codigo"]);
         return $response;
      }
      $data["id"] = filter_var($data["id"], FILTER_SANITIZE_NUMBER_INT);
      $data["codigo"] = filter_var(trim($data["codigo"]), FILTER_SANITIZE_NUMBER_INT);
      $data["nombre"] = trim($data["nombre"]);
      $data["nombre"] = strip_tags($data["nombre"]);
      $data["nombre"] = strtoupper(htmlspecialchars($data["nombre"], ENT_QUOTES, 'UTF-8'));
      $data["tipofinanciacion"] = trim($data["tipofinanciacion"]);
      $data["tipofinanciacion"] = strip_tags($data["tipofinanciacion"]);
      $data["tipofinanciacion"] = strtoupper(htmlspecialchars($data["tipofinanciacion"], ENT_QUOTES, 'UTF-8'));
      $data["ctapucid"] = trim($data["ctapucid"]);
      $data["ctapucid"] = strip_tags($data["ctapucid"]);
      $data["ctapucid"] = strtoupper(htmlspecialchars($data["ctapucid"], ENT_QUOTES, 'UTF-8'));
      $movimiento = isset($_POST["movimiento"]) ? 1 : 0;
      $tabla = "poRubroGasto";
      $dataUpdt = array(
         "Nombre"    => $data["nombre"], 
         "TipoFinanciacionId" => $data["tipofinanciacion"],
         "CtaPucId" => $data["ctapucid"],
         "Movimiento" => $movimiento,
         "UsuarioId"   => $_SESSION["user_id"]
      );
      $where = array(
         "RubroGastoId"    => $data["codigo"], 
         "EmpresaId" => $_SESSION["id_empresa"]
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
      if (empty($_POST["id"]) || !preg_match('/^[0-9]+$/',trim($_POST["id"]))) {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      $idRubroGasto = filter_var(trim($_POST["id"]), FILTER_SANITIZE_NUMBER_INT);
      $tabla = "poRubroGasto";
      $data =array(
         "Estado" => $_POST["status"]
      );
      $where = array(
         "EmpresaId" => $_SESSION["id_empresa"],
         "RubroGastoId"   => $idRubroGasto
      );
      $response = GeneralModel::update($tabla, $data, $where, null);
      return $response;
   }
}