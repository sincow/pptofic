<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/dival/mdlctaclien.php';
}

class CtaclienController {

   //**********************************************************************************************
   static public function index(){
      $cuentas = CtaclienModel::getAll(null);
      return $cuentas;
   }


   //**********************************************************************************************
   static public function getOne(){ 
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }

      $required = ['id' ];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $cuenta = CtaclienModel::getOne($data["id"]);
      return $cuenta;
   }


   //**********************************************************************************************
   static public function getWhere(){ 
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $cuentas = CtaclienModel::getWhere($data);
      return $cuentas;
   }


   //***************************************************************************************************
   static public function create() {
      $required = ['id_dvcliente', 'BanCodNa', 'sucursal', 'numero_cuenta' ];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["sucursal"] = trim($data["sucursal"]);
      $data["sucursal"] = strip_tags($data["sucursal"]);
      $data["sucursal"] = strtoupper(htmlspecialchars($data["sucursal"], ENT_QUOTES, 'UTF-8'));
      $data["numero_cuenta"] = trim($data["numero_cuenta"]);
      $data["numero_cuenta"] = strip_tags($data["numero_cuenta"]);
      $data["numero_cuenta"] = strtoupper(htmlspecialchars($data["numero_cuenta"], ENT_QUOTES, 'UTF-8'));

      $tabla = "DvBanCli";
      $dataUpdt = array(
         "id_empresa"    => $_SESSION["id_empresa"],
         "id_dvcliente"    => $data["id_dvcliente"], 
         "id_banco"      => $data["BanCodNa"], 
         "sucursal"      => $data["sucursal"],
         "numero_cuenta" => $data["numero_cuenta"],
         "id_user"       => $_SESSION["user_id"],
      );
      $response = GeneralModel::save($tabla, $dataUpdt, null);
      return $response;
   }


   //**********************************************************************************************
   static public function update() {
      $required = ['id_bancli', 'id_dvcliente', 'BanCodNa', 'sucursal', 'numero_cuenta' ];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["sucursal"] = trim($data["sucursal"]);
      $data["sucursal"] = strip_tags($data["sucursal"]);
      $data["sucursal"] = strtoupper(htmlspecialchars($data["sucursal"], ENT_QUOTES, 'UTF-8'));
      $data["numero_cuenta"] = trim($data["numero_cuenta"]);
      $data["numero_cuenta"] = strip_tags($data["numero_cuenta"]);
      $data["numero_cuenta"] = strtoupper(htmlspecialchars($data["numero_cuenta"], ENT_QUOTES, 'UTF-8'));
      $tabla = "DvBanCli";
      $dataUpdt = array(
         "id_banco"      => $data["BanCodNa"], 
         "sucursal"      => $data["sucursal"],
         "numero_cuenta" => $data["numero_cuenta"],
         "id_user"       => $_SESSION["user_id"],
      );
      $where = array(
         "id_empresa" => $_SESSION["id_empresa"],
         "id_bancli"   => $data["id_bancli"], 
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
      $data = $_POST;
      $id = $data["id"];
      $tabla = "DvBanCli";
      $data =array(
         "BanEstad" => $_POST["status"]
      );
      $where = array(
         "id_empresa" => $_SESSION["id_empresa"],
         "id_bancli"   => $id
      );
      $response = GeneralModel::update($tabla, $data, $where, null);
      return $response;



   }

}