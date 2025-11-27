<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/mdlcheques.php';
}
if (isset($_POST["action"]) && ($_POST["action"] == "create" || $_POST["action"] == "consigna" || $_POST["action"] == "anular")) {
   require_once '../'. APP_PATH . '/models/dival/mdlclientes.php';
   require_once '../'. APP_PATH . '/controllers/contabilidad/ctrmovimientos.php';
   require_once '../'. APP_PATH . '/models/contabilidad/mdlmovimientos.php';
   require_once '../'. APP_PATH . '/controllers/contabilidad/ctrcuentas.php';
   require_once '../'. APP_PATH . '/models/contabilidad/mdlcuentas.php';
}

class ConsignaController {

   //******************************************************************************************
   static public function getPorConsig() {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $response = ConsignaModel::getPorConsig();
      return $response;
   }


   //******************************************************************************************
   static public function consigna() {
      $required = ['documConsigList', 'canConsig'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      if ($_POST["canConsig"] == 0) {
         return array("success" => false, "message" => "No se seleccionaron documentos para consignar");
      }
      $data = $_POST;
      $data["consecutivo"] = 1;
      $lastRecord = ConsignaModel::getLastConsigna();
      if ($lastRecord != null ) {
         $data["consecutivo"] = $lastRecord["consecutivo"] + 1;
      }
      $ConConta = '1';
      $valConsig = 0;
      $documConsigList = json_decode($data["documConsigList"], true);
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         foreach ($documConsigList as $document) {
            $tablaConsig = "DvConsig";
            $dataConsig = array(
               'id_empresa'  => $_SESSION["id_empresa"],
               'consecutivo' => $data["consecutivo"],
               'id_cheque'   => $document["id_cheque"],
               'BanCodig'    => $data["BancoCodig"],
               'fecha'       => $data["fechaActual"],
               'status'      => '1',
               'ConConta'    => $ConConta,
               'id_user'     => $_SESSION["user_id"]
               // 'creado_el'   => date("Y-m-d H:i:s"),
               // 'actualizado_el' => date("Y-m-d H:i:s")
            );
            $responseConsig = GeneralModel::save($tablaConsig, $dataConsig, $connection);
            if ($responseConsig["success"] === false) {
               throw new PDOException($responseConsig["message"], $responseConsig["code"]);
            }
            $idConsigna = $responseConsig["lastId"];

            $valConsig = $valConsig + $document["valor_cheque"];
            $tablaDocum = "DvCheque";
            $dataDocum = array(
               'capital_pagado' => $document["valor_cheque"],
               'status'  => "C"
            );
            $where = array(
               'id_empresa' => $_SESSION["id_empresa"],
               'id_cheque'  => $document["id_cheque"]
            );
            $responseDocum = GeneralModel::update($tablaDocum, $dataDocum, $where, $connection);
            if ($responseDocum["success"] === false) {
               throw new PDOException($responseDocum["message"], $responseDocum["code"]);
            }
         }
         if ($data["CompteBco"] != "") {
            $tablaBco = "BaMovimi";
            $dataBco = array(
               "EmpCodig" => $_SESSION["empdef"],
               "ConCodig" => $data["CompteBco"],
               "MovDocum" => $idConsigna,
               "TipCodig" => "",
               "MovFecha" => $data["fechaActual"],
		         "BanCodig" => $data["BancoCodig"],
               "CheCodig" => "",
               "TerDocId" => $_SESSION['companyid'],
               "MovDetal" => "CONSIGNACION",
               "MovValor" => $valConsig,
               "MovChequ" => "",
               "MovEstad" => "1",
               "UsuCodig" => $_SESSION["user_id"]
            );
            $responBco = GeneralModel::save($tablaBco, $dataBco, $connection);
            if ($responBco["success"] === false) {
               throw new PDOException($responBco["message"], $responBco["code"]);
            }
         }
         if ($data["compte"] != "") {
            $dataCont = array(
               "ComCodig" => $data["compte"],
               "AsiDocum" => $idConsigna,
               "AsiDocSo" => "",
               "TerDocId" => $_SESSION['companyid'],
               "CenCodig" => "",
               "CenCodAu" => "",
               "AsiFecha" => $data["fechaActual"],
               "origen"   => 2,
               "partidas" => $data["acountingList"]
            );
            $responCont = MovimientosController::save($dataCont, $connection);
            if ($responCont["success"] === false) {
               throw new PDOException($responCont["message"], $responCont["code"]);
            }
         }
         $connection->commit();
         $response = array("success" => true, "message" => "Registro guardado exitosamente", "lastId" => $idConsigna);
      } catch (PDOException $ex) {
         $connection->rollBack();
         $response = array("success" => false, "message" => $ex->getMessage(), "code" => $ex->getCode());
      }
		$reportUrl = null;
		if ($response["success"] == true) {
			$token = bin2hex(random_bytes(16));
			$_SESSION['report_temp_' . $token] = [
				'idConsigna' => $idConsigna,
				'GenHojCal'   => 0,
				'token'       => $token,
				'timestamp'   => time()
			];
         $reportUrl = "app/reports/dival/consigna.php?token=" . urlencode($token);
		}
		return array("success" => $response["success"], "message" =>  $response["message"], "reportUrl" => $reportUrl);
      // return $response;
   }


   //******************************************************************************************
   static public function getConsigById() {
      $required = ['idConsigna'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $response = ConsignaModel::getConsignacion($data);
      return $response;
   }


   //******************************************************************************************
   static public function anular() {
      $required = ['id_consigna'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["id_consigna"] = filter_var($data["id_consigna"], FILTER_SANITIZE_NUMBER_INT);
   }


}