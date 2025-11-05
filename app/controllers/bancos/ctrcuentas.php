<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/mdlcuentas.php';
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
      $client = CuentasModel::getOne("clients", "id", $id, null);
      return $client;
   }


   //*****************************************************************************************************
   static public function create() {
      $required = ['BanCodig', 'BanCodNa', 'BanCuent', 'BanNombr', 'BanFeApe', 'CueCodig'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["BanNombr"] = trim($data["BanNombr"]);
      $data["BanNombr"] = strip_tags($data["BanNombr"]);
      $data["BanNombr"] = strtoupper(htmlspecialchars($data["BanNombr"], ENT_QUOTES, 'UTF-8'));
      $data["BanCuent"] = trim($data["BanCuent"]);
      $data["BanCuent"] = strip_tags($data["BanCuent"]);
      $data["BanCuent"] = htmlspecialchars($data["BanCuent"], ENT_QUOTES, 'UTF-8');
      $tabla = "BaCuenta";
      $dataUpdt = array(
         "EmpCodig" => $_SESSION["empdef"],
         "BanCodig" => $data["BanCodig"],
         "BanNombr" => $data["BanNombr"],
         "BanCuent" => $data["BanCuent"] ?? '',
         "CueCodig" => $data["CueCodig"] ?? '',
         "BanCodNa" => $data["BanCodNa"],
         "BanNomNa" => "",
         "BanFeApe" => $data["BanFeApe"] ?? '1900/01/01',
         "CheCodig" => "",
         "BanConCh" => "",
         "BanCodBa" => "",
         "BanEstad" => "1",
         "UsuCodig" => $_SESSION["user_id"],
         "UsuFecCr" => date("Y-m-d H:i:s")
         // "UsuFecAc" => null
      );
      $response = GeneralModel::save($tabla, $dataUpdt, null);
      return $response;
   }


   //*****************************************************************************************************
   static public function update() {
      $required = ['id', 'BanCodNa', 'BanCuent', 'BanNombr', 'BanFeApe', 'CueCodig' ];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["BanNombr"] = trim($data["BanNombr"]);
      $data["BanNombr"] = strip_tags($data["BanNombr"]);
      $data["BanNombr"] = strtoupper(htmlspecialchars($data["BanNombr"], ENT_QUOTES, 'UTF-8'));
      $data["BanCuent"] = trim($data["BanCuent"]);
      $data["BanCuent"] = strip_tags($data["BanCuent"]);
      $data["BanCuent"] = htmlspecialchars($data["BanCuent"], ENT_QUOTES, 'UTF-8');
      $tabla = "BaCuenta";
      $dataUpdt = array(
         "BanNombr" => $data["BanNombr"],
         "BanCuent" => $data["BanCuent"] ?? '',
         "CueCodig" => $data["CueCodig"] ?? '',
         "BanCodNa" => $data["BanCodNa"],
         "BanNomNa" => "",
         "BanFeApe" => $data["BanFeApe"] ?? '1900/01/01',
         // "CheCodig" => "",
         // "BanConCh" => "",
         // "BanCodBa" => "",
         // "BanEstad" => "1",
         "UsuCodig" => $_SESSION["user_id"],
         "UsuFecAc" => date("Y-m-d H:i:s")
      );
      $where = array(
         "EmpCodig" => $_SESSION["empdef"],
         "BanCodig" => $data["id"]
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
      // if (!filter_var($_POST["id"], FILTER_VALIDATE_INT)) {
      //    $response = array("success" => false, "message" => 'Registro inválido');
      //    return $response;
      // }
      // $idBanco = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
      $data = $_POST;
      $id = $data["id"];
      $tabla = "BaCuenta";
      $data =array(
         "BanEstad" => $_POST["status"]
      );
      $where = array(
         "EmpCodig" => $_SESSION["empdef"],
         "BanCodig" => $id
      );
      $response = GeneralModel::update($tabla, $data, $where, null);
      return $response;
   }
}