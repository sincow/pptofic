<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/mdlclientes.php';
}

class ClientesController {

   //******************************************************************************************
   static public function index(){
      $Clientes = ClientesModel::getAll(null);
      return $Clientes;
   }


   //******************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $Cliente = ClientesModel::getWhere($data);
      return $Cliente;
   }


   //**********************************************************************************************
   static public function searchClient(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $especies = ClientesModel::searchClient($data);
      return $especies;
   }


   //******************************************************************************************
   static public function getOne($id) {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $Cliente = ClientesModel::getOne($id);
      return $Cliente;
   }


   //******************************************************************************************
   static public function getSaldo() {
      // if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      //    $response = array("success" => false, "message" => 'Método inválido');
      //    return $response;
      // }
      $required = ['id'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["id"] = filter_var($data["id"], FILTER_SANITIZE_NUMBER_INT);
      $response = ClientesModel::getSaldo($data["id"]);
      return $response;
   }


   //******************************************************************************************
   static public function create() {
      $required = ['TerTiDoc', 'TerDocId', 'CiuCodig', 'TerDirec', 'TerTele1', 'TerEmail' ];
      if (isset($_POST["TerTiDoc"])) {
         if ($_POST["TerTiDoc"] == "31") {
            $required = ['TerTiDoc', 'TerDocId', 'TerRaSoc', 'CiuCodig', 'TerDirec', 'TerTele1', 'TerEmail' ];
         } else {
            $required = ['TerTiDoc', 'TerDocId', 'TerNomb1', 'TerApel1', 'CiuCodig', 'TerDirec', 'TerTele1', 'TerEmail' ];
         }
      }
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      if ($data["TerTiDoc"] == "31") {
         $TerNombr = $data["TerRaSoc"];
         $data["TerNomb1"] = "";
         $data["TerNomb2"] = "";
         $data["TerApel1"] = "";
         $data["TerApel2"] = "";
      } else {
         $TerNombr = $data["TerNomb1"] . ' ' . $data["TerNomb2"] . ' ' . $data["TerApel1"] . ' ' . $data["TerApel2"];
         $data["TerRaSoc"] = "";
      }
      if (!isset($data["pep"])) {
         $data["pep"] = 0;
      }
      // $data["tipo_telefono"] = 1;
      $data["TerEmail"] = trim($data["TerEmail"]);
      $data["TerEmail"] = strip_tags($data["TerEmail"]);
      $data["TerEmail"] = filter_var($data["TerEmail"], FILTER_SANITIZE_EMAIL);
      if (!filter_var($data["TerEmail"], FILTER_VALIDATE_EMAIL)) {
         $response = array("success" => false, "message" => htmlspecialchars('correo Electrónico invalido'), ENT_QUOTES, 'UTF-8');
         return $response;
      }
      $data["valor_cupo"] = str_replace(",", "", $data['valor_cupo']);
      $data["valor_cupotemporal"] = str_replace(",", "", $data['valor_cupotemporal']);
      $data["TerNomb1"] = trim($data["TerNomb1"]);
      $data["TerNomb1"] = strip_tags($data["TerNomb1"]);
      $data["TerNomb1"] = strtoupper(htmlspecialchars($data["TerNomb1"], ENT_QUOTES, 'UTF-8'));
      $data["TerNomb2"] = trim($data["TerNomb2"]);
      $data["TerNomb2"] = strip_tags($data["TerNomb2"]);
      $data["TerNomb2"] = strtoupper(htmlspecialchars($data["TerNomb2"], ENT_QUOTES, 'UTF-8'));
      $data["TerApel1"] = trim($data["TerApel1"]);
      $data["TerApel1"] = strip_tags($data["TerApel1"]);
      $data["TerApel1"] = strtoupper(htmlspecialchars($data["TerApel1"], ENT_QUOTES, 'UTF-8'));
      $data["TerApel2"] = trim($data["TerApel2"]);
      $data["TerApel2"] = strip_tags($data["TerApel2"]);
      $data["TerApel2"] = strtoupper(htmlspecialchars($data["TerApel2"], ENT_QUOTES, 'UTF-8'));
      $data["TerRaSoc"] = trim($data["TerRaSoc"]);
      $data["TerRaSoc"] = strip_tags($data["TerRaSoc"]);
      $data["TerRaSoc"] = strtoupper(htmlspecialchars($data["TerRaSoc"], ENT_QUOTES, 'UTF-8'));
      $TerNombr = trim($TerNombr);
      $TerNombr = strip_tags($TerNombr);
      $TerNombr = strtoupper(htmlspecialchars($TerNombr, ENT_QUOTES, 'UTF-8'));
      if (!isset($data["TerFreAu"]) || $data["TerFreAu"] == "") {
         $data["TerFreAu"] = null;
      }
      $tabla = "DvClient";
      $dataUpdt = array(
         "id_empresa"           => $_SESSION["id_empresa"],
         "TerDocId"             => $data["TerDocId"], 
         "direccion_residencia" => $data["direccion_residencia"], 
         "fecha_nacimiento"     => $data["fecha_nacimiento"],
         "ciudad_nacimiento"    => $data["CiuCodig"],
         "referencia_comercial" => $data["referencia_comercial"],
         "telefono_refcomercial"=> $data["telefono_refcomercial"], 
         "referencia_personal"  => $data["referencia_personal"], 
         "telefono_refpersonal" => $data["telefono_refpersonal"],
         "valor_cupo"           => $data["valor_cupo"], 
         "valor_cupotemporal"   => $data["valor_cupotemporal"],
         "id_actividad"         => $data["TerAcEco"], 
         "tipo_telefono"        => $data["tipo_telefono"], 
         "persona_responde"     => $data["persona_responde"],
         "origen_recursos"      => $data["origen_recursos"], 
         "nivel_riezgo"         => $data["nivel_riezgo"],
         "pep"                  => $data["pep"], 
         "id_user"              => $_SESSION["user_id"]
      );
      $response = GeneralModel::save($tabla, $dataUpdt, null);
      if ($response["success"] == false) {
         return $response;
      }
      $tabla = "CoTercer";
      $dataSave = array(
         "EmpCodig" => $_SESSION["empdef"],
         "TerDocId" => $data["TerDocId"], 
         "TerNombr" => $TerNombr,
         "TerTiDoc" => $data["TerTiDoc"],
         "TerRetie" => $data["TerRetie"] ?? 0, 
         "TerGrCon" => $data["TerGrCon"] ?? 0,
         "TerAuRet" => $data["TerAuRet"] ?? 0,
         "TerResAu" => $data["TerResAu"],
         "TerFreAu" => $data["TerFreAu"], 
         "CiuCodig" => $data["CiuCodig"], 
         "TerDirec" => $data["TerDirec"],
         "TerTele1" => $data["TerTele1"], 
         "TerTele2" => $data["TerTele2"],
         "TerEmail" => $data["TerEmail"], 
         "TerApel1" => $data["TerApel1"], 
         "TerApel2" => $data["TerApel2"],
         "TerNomb1" => $data["TerNomb1"], 
         "TerNomb2" => $data["TerNomb2"],
         "TerRaSoc" => $data["TerRaSoc"], 
         "TerRegim" => $data["TerRegim"],
         "TerAcEco" => $data["TerAcEco"], 
         "UsuCodig" => $_SESSION["user_id"]
      );
      $dataUpdt = array(
         // "EmpCodig" => $_SESSION["empdef"],
         // "TerDocId" => $data["TerDocId"], 
         "TerNombr" => $TerNombr,
         "TerTiDoc" => $data["TerTiDoc"],
         "TerRetie" => $data["TerRetie"] ?? 0, 
         "TerGrCon" => $data["TerGrCon"] ?? 0,
         "TerAuRet" => $data["TerAuRet"] ?? 0,
         "TerResAu" => $data["TerResAu"],
         "TerFreAu" => $data["TerFreAu"], 
         "CiuCodig" => $data["CiuCodig"], 
         "TerDirec" => $data["TerDirec"],
         "TerTele1" => $data["TerTele1"], 
         "TerTele2" => $data["TerTele2"],
         "TerEmail" => $data["TerEmail"], 
         "TerApel1" => $data["TerApel1"], 
         "TerApel2" => $data["TerApel2"],
         "TerNomb1" => $data["TerNomb1"], 
         "TerNomb2" => $data["TerNomb2"],
         "TerRaSoc" => $data["TerRaSoc"], 
         "TerRegim" => $data["TerRegim"],
         "TerAcEco" => $data["TerAcEco"], 
         "UsuCodig" => $_SESSION["user_id"]
      );
      $response = GeneralModel::saveupdate($tabla, $dataSave, $dataUpdt);
      $_POST = null;
      return $response;
   }


   //******************************************************************************************
   static public function update() {
      $required = ['id', 'TerTiDoc', 'TerDocId', 'CiuCodig', 'TerDirec', 'TerTele1', 'TerEmail' ];
      if (isset($_POST["TerTiDoc"])) {
         if ($_POST["TerTiDoc"] == "31") {
            $required = ['TerTiDoc', 'TerDocId', 'TerRaSoc', 'CiuCodig', 'TerDirec', 'TerTele1', 'TerEmail' ];
         } else {
            $required = ['TerTiDoc', 'TerDocId', 'TerNomb1', 'TerApel1', 'CiuCodig', 'TerDirec', 'TerTele1', 'TerEmail' ];
         }
      }
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      if ($data["TerTiDoc"] == "31") {
         $TerNombr = $data["TerRaSoc"];
         $data["TerNomb1"] = "";
         $data["TerNomb2"] = "";
         $data["TerApel1"] = "";
         $data["TerApel2"] = "";
      } else {
         $TerNombr = $data["TerNomb1"] . ' ' . $data["TerNomb2"] . ' ' . $data["TerApel1"] . ' ' . $data["TerApel2"];
         $data["TerRaSoc"] = "";
      }
      $data['valor_cupo'] = str_replace(",", "", $data['valor_cupo']);
      $data['valor_cupotemporal'] = str_replace(",", "", $data['valor_cupotemporal']);
      if (!isset($data["pep"])) {
         $data["pep"] = 0;
      }
      // $data["tipo_telefono"] = 1;
      $data["TerEmail"] = trim($data["TerEmail"]);
      $data["TerEmail"] = strip_tags($data["TerEmail"]);
      $data["TerEmail"] = filter_var($data["TerEmail"], FILTER_SANITIZE_EMAIL);
      if (!filter_var($data["TerEmail"], FILTER_VALIDATE_EMAIL)) {
         $response = array("success" => false, "message" => htmlspecialchars('correo Electrónico invalido'), ENT_QUOTES, 'UTF-8');
         return $response;
      }
      $data["TerNomb1"] = trim($data["TerNomb1"]);
      $data["TerNomb1"] = strip_tags($data["TerNomb1"]);
      $data["TerNomb1"] = strtoupper(htmlspecialchars($data["TerNomb1"], ENT_QUOTES, 'UTF-8'));
      $data["TerNomb2"] = trim($data["TerNomb2"]);
      $data["TerNomb2"] = strip_tags($data["TerNomb2"]);
      $data["TerNomb2"] = strtoupper(htmlspecialchars($data["TerNomb2"], ENT_QUOTES, 'UTF-8'));
      $data["TerApel1"] = trim($data["TerApel1"]);
      $data["TerApel1"] = strip_tags($data["TerApel1"]);
      $data["TerApel1"] = strtoupper(htmlspecialchars($data["TerApel1"], ENT_QUOTES, 'UTF-8'));
      $data["TerApel2"] = trim($data["TerApel2"]);
      $data["TerApel2"] = strip_tags($data["TerApel2"]);
      $data["TerApel2"] = strtoupper(htmlspecialchars($data["TerApel2"], ENT_QUOTES, 'UTF-8'));
      $data["TerRaSoc"] = trim($data["TerRaSoc"]);
      $data["TerRaSoc"] = strip_tags($data["TerRaSoc"]);
      $data["TerRaSoc"] = strtoupper(htmlspecialchars($data["TerRaSoc"], ENT_QUOTES, 'UTF-8'));
      $TerNombr = trim($TerNombr);
      $TerNombr = strip_tags($TerNombr);
      $TerNombr = strtoupper(htmlspecialchars($TerNombr, ENT_QUOTES, 'UTF-8'));
      if (!isset($data["TerFreAu"]) || $data["TerFreAu"] == "") {
         $data["TerFreAu"] = null;
      }
      $tabla = "DvClient";
      $dataUpdt = array(
         "direccion_residencia" => $data["direccion_residencia"], 
         "fecha_nacimiento"     => $data["fecha_nacimiento"],
         "ciudad_nacimiento"    => $data["CiuCodig"],
         "referencia_comercial" => $data["referencia_comercial"],
         "telefono_refcomercial"=> $data["telefono_refcomercial"], 
         "referencia_personal"  => $data["referencia_personal"], 
         "telefono_refpersonal" => $data["telefono_refpersonal"],
         "valor_cupo"           => $data["valor_cupo"], 
         "valor_cupotemporal"   => $data["valor_cupotemporal"],
         "id_actividad"         => $data["TerAcEco"], 
         "tipo_telefono"        => $data["tipo_telefono"], 
         "persona_responde"     => $data["persona_responde"],
         "origen_recursos"      => $data["origen_recursos"], 
         "nivel_riezgo"         => $data["nivel_riezgo"],
         "pep"                  => $data["pep"], 
         "id_user"              => $_SESSION["user_id"]
      );
      $where = array(
         "id_empresa"   => $_SESSION["id_empresa"],
         "id_dvcliente" => $data["id"], 
      );
      $response = GeneralModel::update($tabla, $dataUpdt, $where, null);
      if ($response["success"] == false) {
         return $response;
      }
      $tabla = "CoTercer";
      $dataSave = array(
         "EmpCodig" => $_SESSION["empdef"],
         "TerDocId" => $data["TerDocId"], 
         "TerNombr" => $TerNombr,
         "TerTiDoc" => $data["TerTiDoc"],
         "TerRetie" => $data["TerRetie"] ?? 0, 
         "TerGrCon" => $data["TerGrCon"] ?? 0,
         "TerAuRet" => $data["TerAuRet"] ?? 0,
         "TerResAu" => $data["TerResAu"],
         "TerFreAu" => $data["TerFreAu"], 
         "CiuCodig" => $data["CiuCodig"], 
         "TerDirec" => $data["TerDirec"],
         "TerTele1" => $data["TerTele1"], 
         "TerTele2" => $data["TerTele2"],
         "TerEmail" => $data["TerEmail"], 
         "TerApel1" => $data["TerApel1"], 
         "TerApel2" => $data["TerApel2"],
         "TerNomb1" => $data["TerNomb1"], 
         "TerNomb2" => $data["TerNomb2"],
         "TerRaSoc" => $data["TerRaSoc"], 
         "TerRegim" => $data["TerRegim"],
         "TerAcEco" => $data["TerAcEco"], 
         "UsuCodig" => $_SESSION["user_id"]
      );
      $dataUpdt = array(
         // "EmpCodig" => $_SESSION["empdef"],
         // "TerDocId" => $data["TerDocId"], 
         "TerNombr" => $TerNombr,
         "TerTiDoc" => $data["TerTiDoc"],
         "TerRetie" => $data["TerRetie"] ?? 0, 
         "TerGrCon" => $data["TerGrCon"] ?? 0,
         "TerAuRet" => $data["TerAuRet"] ?? 0,
         "TerResAu" => $data["TerResAu"],
         "TerFreAu" => $data["TerFreAu"], 
         "CiuCodig" => $data["CiuCodig"], 
         "TerDirec" => $data["TerDirec"],
         "TerTele1" => $data["TerTele1"], 
         "TerTele2" => $data["TerTele2"],
         "TerEmail" => $data["TerEmail"], 
         "TerApel1" => $data["TerApel1"], 
         "TerApel2" => $data["TerApel2"],
         "TerNomb1" => $data["TerNomb1"], 
         "TerNomb2" => $data["TerNomb2"],
         "TerRaSoc" => $data["TerRaSoc"], 
         "TerRegim" => $data["TerRegim"],
         "TerAcEco" => $data["TerAcEco"], 
         "UsuCodig" => $_SESSION["user_id"]
      );
      $response = GeneralModel::saveupdate($tabla, $dataSave, $dataUpdt);
      $_POST = null;
      return $response;
   }


   //******************************************************************************************
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
      $idCliente = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
      $tabla = "DvClient";
      $data =array(
         "status" => $_POST["status"]
      );
      $where = array(
         "id_empresa"   => $_SESSION["id_empresa"],
         "id_dvcliente" => $idCliente
      );
      $response = GeneralModel::update($tabla, $data, $where, null);
      return $response;
   }
}