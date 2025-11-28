<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/mdlcheques.php';
}
if (isset($_POST["action"]) && ($_POST["action"] == "create" || $_POST["action"] == "devolucion" || $_POST["action"] == "aplaza" || $_POST["action"] == "pagocapital" || $_POST["action"] == "pagointeres" || $_POST["action"] == "anular" || $_POST["action"] == "anularApl" || $_POST["action"] == "anularPagoCap" || $_POST["action"] == "anularPagoInt")) {
   require_once '../'. APP_PATH . '/models/dival/mdlclientes.php';
   require_once '../'. APP_PATH . '/controllers/contabilidad/ctrmovimientos.php';
   require_once '../'. APP_PATH . '/models/contabilidad/mdlmovimientos.php';
   require_once '../'. APP_PATH . '/controllers/contabilidad/ctrcuentas.php';
   require_once '../'. APP_PATH . '/models/contabilidad/mdlcuentas.php';
}

class ChequesController {

   //*******************************************************************************************
   static public function filter(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
		$data = filter_input_array(INPUT_POST);
      $especies = ChequesModel::filter($data);
      return $especies;
   }


   //******************************************************************************************
   static public function getOne() {
      $required = ['id_cheque'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["id_cheque"] = filter_var($data["id_cheque"], FILTER_SANITIZE_NUMBER_INT);
      $response = ChequesModel::getOne($data);
      return $response;
   }


   //******************************************************************************************
   static public function getByNum() {
      $required = ['id_cheque'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $response = ChequesModel::getOne($data, 1);
      return $response;
   }


   //******************************************************************************************
   static public function getDetails() {
      $required = ['idDocument'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["id_cheque"] = filter_var($data["idDocument"], FILTER_SANITIZE_NUMBER_INT);
      $responseOne = ChequesModel::getOne($data);
      $dataCon = [];
      $responseCon = [];
      if ($responseOne != null ) {
         $idCheque = $responseOne["id_cheque"];
         $dataCon = array("id_cheque" => $idCheque);
         $responseCon = ChequesModel::getConsigDocum($dataCon);
         $responseDev = ChequesModel::getDevolucion($dataCon);
         $responseApl = ChequesModel::getAplaza($dataCon);
      }
      $response = array("cheque" => $responseOne, "consignacion" => $responseCon, "devolucion" => $responseDev, "aplaza" => $responseApl); ;
      return $response;
   }


   //******************************************************************************************
   static public function getDevol() {
      $required = ['id_cheque'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $dataCon = [];
      $responseChe = ChequesModel::getOne($data, 1);
      if ($responseChe != null ) {
         $idCehque = $responseChe["id_cheque"];
         $dataCon = array("id_cheque" => $idCehque);
      }
      $responseCon = ChequesModel::getConsigDocum($dataCon);
      $response = array("cheque" => $responseChe, "consignacion" => $responseCon);
      return $response;
   }


   //******************************************************************************************
   static public function getAplaza() {
      $required = ['id_cheque'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $dataApl = [];
      $responseChe = ChequesModel::getOne($data, 1);
      if ($responseChe != null ) {
         $idCehque = $responseChe["id_cheque"];
         $dataApl = array("id_cheque" => $idCehque);
      }
      $responseApl = ChequesModel::getAplaza($dataApl);
      $response = array("cheque" => $responseChe, "aplaza" => $responseApl);
      return $response;
   }


   //******************************************************************************************
   static public function getAplById() {
      $required = ['id_aplaza'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $dataApl = [];
      $data["id_aplaza"] = filter_var($data["id_aplaza"], FILTER_SANITIZE_NUMBER_INT);
      $responseApl = ChequesModel::getAplById($data);
      if ($responseApl != null ) {
         $idCehque = $responseApl["id_cheque"];
         $dataApl = array("id_cheque" => $idCehque);
      }
      $responseChe = ChequesModel::getOne($dataApl, 0);
      $response = array("cheque" => $responseChe, "aplaza" => $responseApl);
      return $response;
   }


   //******************************************************************************************
   static public function getPagoCapById() {
      $required = ['id_pago'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $dataApl = [];
      $data["id_pago"] = filter_var($data["id_pago"], FILTER_SANITIZE_NUMBER_INT);
      $responseApl = ChequesModel::getPagoCapById($data);
      if ($responseApl != null ) {
         $idCehque = $responseApl["id_cheque"];
         $dataApl = array("id_cheque" => $idCehque);
      }
      $responseChe = ChequesModel::getOne($dataApl, 0);
      $response = array("cheque" => $responseChe, "pagoCapital" => $responseApl);
      return $response;
   }


   //******************************************************************************************
   static public function getPagoIntById() {
      $required = ['id_pago'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $dataApl = [];
      $data["id_pago"] = filter_var($data["id_pago"], FILTER_SANITIZE_NUMBER_INT);
      $responseApl = ChequesModel::getPagoIntById($data);
      if ($responseApl != null ) {
         $idCehque = $responseApl["id_cheque"];
         $dataApl = array("id_cheque" => $idCehque);
      }
      $responseChe = ChequesModel::getOne($dataApl, 0);
      $response = array("cheque" => $responseChe, "pagoInteres" => $responseApl);
      return $response;
   }


   //******************************************************************************************
   static public function create() {
      if (isset($_POST["clase"]) && $_POST["clase"] != '1') {
         $_POST["mensajeria"] = "2";
      }
      $required = ['id_dvcliente', 'numero', 'numero_cuenta', 'vencimiento', 'dias_cobrados', 'valor_cheque', 
         'valComision', 'impuesto_banco', 'valIVA', 'porcentaje_comision', 'mensajeria'
      ];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }

      if(isset($_FILES["imagen"]["tmp_name"]) && $_FILES['imagen']['error'] !== UPLOAD_ERR_OK){
         $response = array("success" => false, "message" => 'El archivvo con la imagen del cheque no es vvádio');
         return $response;
      }
      $_POST["id"] = $_POST["id_dvcliente"];
      $saldoCliente = ClientesModel::getSaldo($_POST["id_dvcliente"]);
      $cliente = ClientesModel::getOne($_POST["id_dvcliente"]);
      // if ($saldoCliente == null) {
      //    $response = array("success" => false, "message" => 'No se pudo consultar cupo del cliente');
      //    return $response;
      // }
      $valCartera = 0;
      $cupo = $cliente["valor_cupo"] + $cliente["valor_cupotemporal"];
      foreach ($saldoCliente as $key => $value) {
         $valCartera = $valCartera + $value["valor_cheque"] - $value["capital_pagado"] ;
      }
      $valCheque =  str_replace(",", "", $_POST["valor_cheque"]);
      if ($cupo - $valCartera < $valCheque) {
         $response = array("success" => false, "message" => 'Valor del documento es mayor que cupo disponible');
         return $response;
      }

      $data = $_POST;

      $data["imagen"] = "";
      $data["imagen_name"] = "";
      $photo = "";
      if ($data["clase"] == '1') {
         $data["imagen"] = $_FILES["imagen"]["tmp_name"];
         $data["imagen_name"] = $_FILES["imagen"]["name"];

         $directorio = "../assets/uploads/cheques/".$_SESSION["id_empresa"]."/";
         if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
         }
         $aleatorio = mt_rand(100,999);
         list($ancho, $alto) = getimagesize($_FILES["imagen"]["tmp_name"]);
         $nuevoAncho = 600;
         $nuevoAlto = 300;
         $allowedTypes = array("image/jpeg", "application/pdf");
         if(!in_array($_FILES["imagen"]["type"], $allowedTypes)){
            $response = array("success" => false, "message" => 'Formato no permitido. Solo JPG y PDF');
            return $response;
         }
         if($_FILES["imagen"]["type"] == "image/jpeg" && $_FILES["imagen"]["size"] > 2097152){
            $response = array("success" => false, "message" => "La imagen es muy grande. Máximo " . (2097152 / 1024 / 1024) . "MB");
            return $response;
         }
         if($_FILES["imagen"]["type"] == "application/pdf" && $_FILES["imagen"]["size"] > 5242880){
            $response = array("success" => false, "message" => "El PDF es muy grande. Máximo " . (5242880 / 1024 / 1024) . "MB");
            return $response;
         }
         if($_FILES["imagen"]["size"] == 0){
            return "El archivo está vacío";
            $response = array("success" => false, "message" => '"El archivo con la imágen dle cheque está vacío');
            return $response;
         }
         $fileType = $_FILES["imagen"]["type"];
         $extension = ($fileType == "image/jpeg") ? ".jpg" : ".pdf";
         $photo = $directorio."/".$_POST["TerDocId"]."-".$data["numero"].$extension;
         if($fileType == "image/jpeg"){
            $origen = imagecreatefromjpeg($_FILES["imagen"]["tmp_name"]);
            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
            imagejpeg($destino, $photo);
         } else {
            if(!move_uploaded_file($_FILES["imagen"]["tmp_name"], $photo)){
               $response = array("success" => false, "message" => "Error al subir el PDF");
               return $response;
            }
         }
      }
      $consecutivo = $data["numero"];
      $lastRecord = ChequesModel::getLast($data["clase"]);
      if ($data["clase"] != '1') {
         $data["numero"] = "0000001";
         if ($lastRecord != null ) {
            $data["numero"] = str_pad(intval(substr($lastRecord["numero"], -7)) + 1, 7, "0", STR_PAD_LEFT);
         }
         if ($data["clase"] == '3') {
            $data["numero"] = 'P'.$data["numero"];
            $asiDesc = "Emisión de Pagaré: ".$data["numero"];
         } else {
            $data["numero"] = 'L'.$data["numero"];
            $asiDesc = "Emisión de Letra: ".$data["numero"];
         }
         $acountingList = json_decode($data["acountingList"], true);
         foreach ($acountingList as $key => $value) {
            $acountingList[$key]["AsiDescr"] = $asiDesc;
         }
         $data["acountingList"] = json_encode($acountingList);
         $consecutivo = $data["numero"];
      } else {
         if ($lastRecord != null ) {
            $consecutivo = str_pad(intval($lastRecord["consecutivo"]) + 1, 8, "0", STR_PAD_LEFT);
         } else {
            $consecutivo = "00000001";
         }
      }
      $data["fecha"] = date('Y-m-d');
      $data["valor_cheque"] = str_replace(',', '', $data["valor_cheque"]);
      $data["valor_cheque"] = filter_var($data["valor_cheque"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $data["valComision"] = str_replace(',', '', $data["valComision"]);
      $data["valComision"] = filter_var($data["valComision"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $data["impuesto_banco"] = filter_var($data["impuesto_banco"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $data["valIVA"] = str_replace(',', '', $data["valIVA"]);
      $data["valIVA"] = filter_var($data["valIVA"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $data["porcentaje_comision"] = filter_var($data["porcentaje_comision"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $data["id_dvcliente"] = filter_var($data["id_dvcliente"], FILTER_SANITIZE_NUMBER_INT);
      $data["numero_cuenta"] = filter_var($data["numero_cuenta"], FILTER_SANITIZE_NUMBER_INT);
      $data["dias_cobrados"] = filter_var($data["dias_cobrados"], FILTER_SANITIZE_NUMBER_INT);

      $tabla = "DvCheque";
      $dataUpdt = array(
         'id_empresa'          => $_SESSION["id_empresa"],
         'clase'               => $data["clase"],
         'consecutivo'         => $consecutivo,
         'id_dvcliente'        => $data["id_dvcliente"],
         'TerDocId'            => $data["TerDocId"],
         'TerDocId2'           => $data["TerDocId2"],
         'TerDocId3'           => $data["TerDocId3"],
         'TerDocId4'           => $data["TerDocId4"],
         'numero'              => $data["numero"],
         'id_bancli'           => $data["numero_cuenta"],
         'fecha'               => $data["fechaActual"],
         'vencimiento'         => $data["vencimiento"],
         'dias_cobrados'       => $data["dias_cobrados"],
         'valor_cheque'        => $data["valor_cheque"],
         'comision'            => $data["valComision"],
         'impuesto_banco'      => $data["impuesto_banco"],
         'valor_iva'           => $data["valIVA"],
         'porcentaje_comision' => $data["porcentaje_comision"],
         'imagen'              => $photo,
         'mensajeria'          => $data["mensajeria"],
         'observacion'         => $data["observacion"],
         'id_user'             => $_SESSION["user_id"]
      );
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         $response = GeneralModel::save($tabla, $dataUpdt, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }
         $AsiDocum = $response["lastId"];

         /*
         if ($data["clase"] == '3') {
            $consecutivo = "P".str_pad($AsiDocum, 6, "0", STR_PAD_LEFT);
         } else if ($data["clase"] == '5') {
            $consecutivo = "L".str_pad($AsiDocum, 6, "0", STR_PAD_LEFT);
         } else {
            $consecutivo = "C".str_pad($AsiDocum, 6, "0", STR_PAD_LEFT);
         }
         if ($data["clase"] != 1) {
            $data["numero"] = $consecutivo;
         }
         $dataUpdt = array(
            'consecutivo' => $consecutivo,
            'numero'      => $data["numero"]
         );
         $whereUpdt = array(
            'id_cheque' => $AsiDocum
         );
         $response = GeneralModel::update($tabla, $dataUpdt, $whereUpdt, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }
         */

         if ($data["compte"] != "") {
            $datos = array(
               "ComCodig" => $data["compte"],
               "AsiDocum" => $AsiDocum,
               "AsiDocSo" => $data["numero"],
               "TerDocId" => $data["TerDocId"],
               "CenCodig" => "",
               "CenCodAu" => "",
               "AsiFecha" => $data["fechaActual"],
               "origen"   => 2,
               "partidas" => $data["acountingList"]
            );
            $responCont = MovimientosController::save($datos, null);
            if ($responCont["success"] === false) {
               throw new PDOException($responCont["message"], $responCont["code"]);
            }
         }
         $connection->commit();
         $response = array("success" => true, "message" => "Registro guardado exitosamente", "lastId" => $AsiDocum);
      } catch (PDOException $ex) {
         $connection->rollBack();
         $response = array("success" => false, "message" => $ex->getMessage(), "code" => $ex->getCode());
      }

		$reportUrl = null;
		if ($response["success"] == true) {
			$token = bin2hex(random_bytes(16));
			$_SESSION['report_temp_' . $token] = [
				'id_cheque' => $AsiDocum,
				'GenHojCal' => 0,
				'token' => $token,
				'timestamp' => time()
			];
         if ($data["clase"] == '1') {
            $reportUrl = "app/reports/dival/liquidacion.php?token=" . urlencode($token);
         }
         if ($data["clase"] == '3') {
            $reportUrl = "app/reports/dival/pagare.php?token=" . urlencode($token);
         }
         if ($data["clase"] == '5') {
            $reportUrl = "app/reports/dival/letra.php?token=" . urlencode($token);
         }
		}
		return array("success" => $response["success"], "message" =>  $response["message"], "reportUrl" => $reportUrl);
   }


   //******************************************************************************************
   static public function devolucion() {
      $required = ['id_cheque', 'motivo', 'id_consigna'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["consecutivo"] = 1;
      $lastRecord = ChequesModel::getLastDevol();
      if ($lastRecord != null ) {
         $data["consecutivo"] = $lastRecord["consecutivo"] + 1;
      }
      $DevConta = '1';

      $tabla = "DvDevolu";
      $dataUpdt = array(
         'id_empresa'  => $_SESSION["id_empresa"],
         'consecutivo' => $data["consecutivo"],
         'id_consigna' => $data["id_consigna"],
         'id_cheque'   => $data["id_cheque"],
         'fecha'       => $data["fechaActual"],
         'motivo'      => $data["motivo"],
         'status'      => "1",
         'DevConta'    => $DevConta,
         'id_user'     => $_SESSION["user_id"]
      );
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         $response = GeneralModel::save($tabla, $dataUpdt, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }
         $AsiDocum = $response["lastId"];

         $tabla = "DvCheque";
         $dataUpdt = array(
            'status'  => "D"
         );
         $where = array(
            'id_empresa' => $_SESSION["id_empresa"],
            'id_cheque'  => $data["id_cheque"]
         );
         $response = GeneralModel::update($tabla, $dataUpdt, $where, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }

         $tabla = "DvConsig";
         $dataUpdt = array(
            'status'  => "D"
         );
         $where = array(
            'id_empresa' => $_SESSION["id_empresa"],
            'id_consigna'  => $data["id_consigna"]
         );
         $response = GeneralModel::update($tabla, $dataUpdt, $where, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }
         if ($data["CompteBco"] != "") {
            $tabla = "BaMovimi";
            $datos = array(
               "EmpCodig" => $_SESSION["empdef"],
               "ConCodig" => $data["CompteBco"],
               "MovDocum" => $AsiDocum,
               "TipCodig" => "",
               "MovFecha" => $data["fechaActual"],
		         "BanCodig" => $data["BanCodig"],
               "CheCodig" => "",
               "TerDocId" => $data["TerDocId"],
               "MovDetal" => "DEVOLUCION DE CHEQUE",
               "MovValor" => $data["valor_cheque"],
               "MovChequ" => $data["numero"],
               "MovEstad" => "1",
               "UsuCodig" => $_SESSION["user_id"]
            );
            $responCont = GeneralModel::save($tabla, $datos, $connection);
            if ($responCont["success"] === false) {
               throw new PDOException($responCont["message"], $responCont["code"]);
            }
         }

         if ($data["compte"] != "") {
            $datos = array(
               "ComCodig" => $data["compte"],
               "AsiDocum" => $AsiDocum,
               "AsiDocSo" => $data["numero"],
               "TerDocId" => $data["TerDocId"],
               "CenCodig" => "",
               "CenCodAu" => "",
               "AsiFecha" => $data["fechaActual"],
               "origen"   => 2,
               "partidas" => $data["acountingList"]
            );
            $responCont = MovimientosController::save($datos, $connection);
            if ($responCont["success"] === false) {
               throw new PDOException($responCont["message"], $responCont["code"]);
            }
         }
         $connection->commit();
         $response = array("success" => true, "message" => "Registro guardado exitosamente", "lastId" => $AsiDocum);
      } catch (PDOException $ex) {
         $connection->rollBack();
         $response = array("success" => false, "message" => $ex->getMessage(), "code" => $ex->getCode());
      }

		$reportUrl = null;
      /*
		if ($response["success"] == true) {
			$token = bin2hex(random_bytes(16));
			$_SESSION['report_temp_' . $token] = [
				'id_devolu' => $AsiDocum,
				'GenHojCal' => 0,
				'token' => $token,
				'timestamp' => time()
			];
         $reportUrl = "app/reports/dival/pagare.php?token=" . urlencode($token);
		}
      */
		return array("success" => $response["success"], "message" =>  $response["message"], "reportUrl" => $reportUrl);
      // $response = ChequesModel::devolucion($data);
      // return $response;
   }


   //******************************************************************************************
   static public function aplaza() {
      $required = ['id_cheque', 'vencimiento', 'valor_aplaza', 'valor_interes'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["valor_aplaza"] = str_replace(',', '', $data["valor_aplaza"]);
      $data["valor_aplaza"] = filter_var($data["valor_aplaza"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $data["valor_interes"] = str_replace(',', '', $data["valor_interes"]);
      $data["valor_interes"] = filter_var($data["valor_interes"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $AplConta = '1';
      $fecha = date('Y-m-d');
      $tabla = "DvAplaza";
      $dataUpdt = array(
         'id_empresa'    => $_SESSION["id_empresa"],
         'id_cheque'     => $data["id_cheque"],
         'fecha'         => $data["vencimiento"],
         'dias_cobrar'   => $data["dias_cobrar"],
         'valor_aplaza'  => $data["valor_aplaza"],
         'intereses'     => $data["porcentaje_comision"],
         'valor_interes' => $data["valor_interes"],
         'motivo'        => $data["motivo"],
         'AplConta'      => $AplConta,
         'status'        => "1",
         'id_user'       => $_SESSION["user_id"]
      );
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         $response = GeneralModel::save($tabla, $dataUpdt, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }
         $AsiDocum = $response["lastId"];
         $dataUpdt = array(
            'id_empresa' => $_SESSION["id_empresa"],
            'id_cheque'  => $data["id_cheque"],
            'valor'      => $data["valor_interes"]
         );
         $response = ChequesModel::updtCheque($dataUpdt, 1, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }
         if ($data["compte"] != "") {
            $datos = array(
               "ComCodig" => $data["compte"],
               "AsiDocum" => $AsiDocum,
               "AsiDocSo" => $data["numero"],
               "TerDocId" => $data["TerDocId"],
               "CenCodig" => "",
               "CenCodAu" => "",
               "AsiFecha" => $fecha,
               "origen"   => 2,
               "partidas" => $data["acountingList"]
            );
            $responCont = MovimientosController::save($datos, null);
            if ($responCont["success"] === false) {
               throw new PDOException($responCont["message"], $responCont["code"]);
            }
         }
         $connection->commit();
         $response = array("success" => true, "message" => "Registro guardado exitosamente", "lastId" => $AsiDocum);
      } catch (PDOException $ex) {
         $connection->rollBack();
         $response = array("success" => false, "message" => $ex->getMessage(), "code" => $ex->getCode());
      }
		$reportUrl = null;
		return array("success" => $response["success"], "message" =>  $response["message"], "reportUrl" => $reportUrl);
   }


   //******************************************************************************************
   static public function pagocapital() {
      $required = ['id_cheque', 'fecha_pago', 'numero', 'capital_pagar', 'TerDocId'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      if ($_POST['capital_pagar'] <= 0 || $_POST['capital_pagar'] == null) {
         return array("success" => false, "message" =>  "Valor del capital a pagar debe ser mayor a 0");
      }
      $data = $_POST;
      $data["capital_pagar"] = str_replace(',', '', $data["capital_pagar"]);
      $data["capital_pagar"] = filter_var($data["capital_pagar"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $data["consecutivo"] = 1;
      $lastRecord = ChequesModel::getLastPagCap();
      if ($lastRecord != null ) {
         $data["consecutivo"] = $lastRecord["consecutivo"] + 1;
      }
      $DevConta = '1';
      $tabla = "DvPagCap";
      $dataUpdt = array(
         'id_empresa'  => $_SESSION["id_empresa"],
         'consecutivo' => $data["consecutivo"],
         'id_cheque'   => $data["id_cheque"],
         'fecha'       => $data["fecha_pago"],
         'valor'       => $data["capital_pagar"],
         'status'      => "1",
         'id_user'     => $_SESSION["user_id"]
      );
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         $response = GeneralModel::save($tabla, $dataUpdt, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }
         $AsiDocum = $response["lastId"];
         $dataUpdt = array(
            'id_empresa' => $_SESSION["id_empresa"],
            'id_cheque'  => $data["id_cheque"],
            'valor'      => $data["capital_pagar"]
         );
         $response = ChequesModel::updtCheque($dataUpdt, 2, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }
         if ($data["compte"] != "") {
            $datos = array(
               "ComCodig" => $data["compte"],
               "AsiDocum" => $AsiDocum,
               "AsiDocSo" => $data["numero"],
               "TerDocId" => $data["TerDocId"],
               "CenCodig" => "",
               "CenCodAu" => "",
               "AsiFecha" => $data["fecha_pago"],
               "origen"   => 2,
               "partidas" => $data["acountingList"]
            );
            $responCont = MovimientosController::save($datos, null);
            if ($responCont["success"] === false) {
               throw new PDOException($responCont["message"], $responCont["code"]);
            }
         }
         $connection->commit();
         $response = array("success" => true, "message" => "Registro guardado exitosamente", "lastId" => $AsiDocum);
      } catch (PDOException $ex) {
         $connection->rollBack();
         $response = array("success" => false, "message" => $ex->getMessage(), "code" => $ex->getCode());
      }
		$reportUrl = null;
      /*
		if ($response["success"] == true) {
			$token = bin2hex(random_bytes(16));
			$_SESSION['report_temp_' . $token] = [
				'id_devolu' => $AsiDocum,
				'GenHojCal' => 0,
				'token' => $token,
				'timestamp' => time()
			];
         $reportUrl = "app/reports/dival/pagare.php?token=" . urlencode($token);
		}
      */
		return array("success" => $response["success"], "message" =>  $response["message"], "reportUrl" => $reportUrl);
   }


   //******************************************************************************************
   static public function pagointeres() {
      $required = ['id_cheque', 'fecha_pago', 'numero', 'interes_pagar', 'TerDocId'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      if ($_POST['interes_pagar'] <= 0 || $_POST['interes_pagar'] == null) {
         return array("success" => false, "message" =>  "Valor del interes a pagar debe ser mayor a 0");
      }
      $data = $_POST;
      $data["interes_pagar"] = str_replace(',', '', $data["interes_pagar"]);
      $data["interes_pagar"] = filter_var($data["interes_pagar"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $data["consecutivo"] = 1;
      $lastRecord = ChequesModel::getLastPagInt();
      if ($lastRecord != null ) {
         $data["consecutivo"] = $lastRecord["consecutivo"] + 1;
      }
      $PinConta = '1';
      $tabla = "DvPagInt";
      $dataUpdt = array(
         'id_empresa'  => $_SESSION["id_empresa"],
         'consecutivo' => $data["consecutivo"],
         'id_cheque'   => $data["id_cheque"],
         'fecha'       => $data["fecha_pago"],
         'valor'       => $data["interes_pagar"],
         'status'      => "1",
         'id_user'     => $_SESSION["user_id"]
      );
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         $response = GeneralModel::save($tabla, $dataUpdt, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }
         $AsiDocum = $response["lastId"];
         $dataUpdt = array(
            'id_empresa' => $_SESSION["id_empresa"],
            'id_cheque'  => $data["id_cheque"],
            'valor'      => $data["interes_pagar"]
         );
         $response = ChequesModel::updtCheque($dataUpdt, 3, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }
         if ($data["compte"] != "") {
            $datos = array(
               "ComCodig" => $data["compte"],
               "AsiDocum" => $AsiDocum,
               "AsiDocSo" => $data["numero"],
               "TerDocId" => $data["TerDocId"],
               "CenCodig" => "",
               "CenCodAu" => "",
               "AsiFecha" => $data["fecha_pago"],
               "origen"   => 2,
               "partidas" => $data["acountingList"]
            );
            $responCont = MovimientosController::save($datos, null);
            if ($responCont["success"] === false) {
               throw new PDOException($responCont["message"], $responCont["code"]);
            }
         }
         $connection->commit();
         $response = array("success" => true, "message" => "Registro guardado exitosamente", "lastId" => $AsiDocum);
      } catch (PDOException $ex) {
         $connection->rollBack();
         $response = array("success" => false, "message" => $ex->getMessage(), "code" => $ex->getCode());
      }
		$reportUrl = null;
      /*
		if ($response["success"] == true) {
			$token = bin2hex(random_bytes(16));
			$_SESSION['report_temp_' . $token] = [
				'id_devolu' => $AsiDocum,
				'GenHojCal' => 0,
				'token' => $token,
				'timestamp' => time()
			];
         $reportUrl = "app/reports/dival/pagare.php?token=" . urlencode($token);
		}
      */
		return array("success" => $response["success"], "message" =>  $response["message"], "reportUrl" => $reportUrl);
   }


   //******************************************************************************************
   static public function repplafectivo2() {
      $required = ['repFecPlanilla'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $response = ChequesModel::repplafectivo($data);
      return $response;
   }


   //******************************************************************************************
   static public function repplaarqueo2() {
      $required = ['repFecPlanilla', 'repValContado'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["repValContado"] = str_replace(',', '', $data["repValContado"]);
      $data["repValContado"] = filter_var($data["repValContado"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $response = ChequesModel::repplaarqueo($data);
      return $response;
   }


   //******************************************************************************************
   static public function getDashborad() {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $response = ChequesModel::getDashborad();
      return $response;
   }


   //******************************************************************************************
   static public function anular() {
      $required = ['id_cheque', 'clase'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         $tablaDocum = "DvCheque";
         $dataDocum = array(
            'valor_cheque' => 0,
            'comision'     => 0,
            'status'       => "A"
         );
         $where = array(
            'id_empresa' => $_SESSION["id_empresa"],
            'id_cheque'  => $data["id_cheque"]
         );
         $responseDocum = GeneralModel::update($tablaDocum, $dataDocum, $where, $connection);
         if ($responseDocum["success"] === false) {
            throw new PDOException($responseDocum["message"], $responseDocum["code"]);
         }

         $dataCont = array(
            "ComCodig" => $data["compte"],
            "AsiDocum" => $data["id_cheque"],
            "accion" => "A",
         );
         $responCont = MovimientosController::cancel($dataCont, $connection);
         if ($responCont["success"] === false) {
            throw new PDOException($responCont["message"], $responCont["code"]);
         }
         $connection->commit();
         $response = array("success" => true, "message" => "Registro anulado exitosamente");
      } catch (PDOException $ex) {
         $connection->rollBack();
         $response = array("success" => false, "message" => $ex->getMessage(), "code" => $ex->getCode());
      }
      return $response;
   }


   //******************************************************************************************
   static public function anularApl() {
      $required = ['id_aplaza', 'id_cheque', 'valor_interes'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["id_aplaza"] = filter_var($data["id_aplaza"], FILTER_SANITIZE_NUMBER_INT);
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         $tablaDocum = "DvAplaza";
         $dataDocum = array(
            'valor_aplaza'  => 0,
            'intereses'     => 0,
            'valor_interes' => 0,
            'status' => "A"
         );
         $where = array(
            'id_empresa' => $_SESSION["id_empresa"],
            'id_aplaza'  => $data["id_aplaza"]
         );
         $responseDocum = GeneralModel::update($tablaDocum, $dataDocum, $where, $connection);
         if ($responseDocum["success"] === false) {
            throw new PDOException($responseDocum["message"], $responseDocum["code"]);
         }

         $dataChe = array(
            'intereses_cobrados' => $data["valor_interes"],
            'id_empresa' => $_SESSION["id_empresa"],
            'id_cheque'  => $data["id_cheque"]
         );
         $responseChe = ChequesModel::actInteres($dataChe, $connection);
         if ($responseChe["success"] === false) {
            throw new PDOException($responseChe["message"], $responseChe["code"]);
         }

         if ($data["compte"] != "") {
            $dataCont = array(
               "ComCodig" => $data["compte"],
               "AsiDocum" => $data["id_aplaza"],
               "accion" => "A",
            );
            $responCont = MovimientosController::cancel($dataCont, $connection);
            if ($responCont["success"] === false) {
               throw new PDOException($responCont["message"], $responCont["code"]);
            }
         }

         $connection->commit();
         $response = array("success" => true, "message" => "Registro anulado exitosamente");
      } catch (PDOException $ex) {
         $connection->rollBack();
         $response = array("success" => false, "message" => $ex->getMessage(), "code" => $ex->getCode());
      }
      return $response;
   }


   //******************************************************************************************
   static public function anularPagoCap() {
      $required = ['id_pago', 'id_cheque', 'valor'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         $tablaDocum = "DvPagCap";
         $dataDocum = array(
            'valor'  => 0,
            'status' => "A"
         );
         $where = array(
            'id_empresa' => $_SESSION["id_empresa"],
            'id_pago'    => $data["id_pago"]
         );
         $responseDocum = GeneralModel::update($tablaDocum, $dataDocum, $where, $connection);
         if ($responseDocum["success"] === false) {
            throw new PDOException($responseDocum["message"], $responseDocum["code"]);
         }

         $dataChe = array(
            'capital_pagado' => $data["valor"],
            'id_empresa' => $_SESSION["id_empresa"],
            'id_cheque'  => $data["id_cheque"]
         );
         $responseChe = ChequesModel::actPagoCap($dataChe, $connection);
         if ($responseChe["success"] === false) {
            throw new PDOException($responseChe["message"], $responseChe["code"]);
         }

         if ($data["compte"] != "") {
            $dataCont = array(
               "ComCodig" => $data["compte"],
               "AsiDocum" => $data["id_pago"],
               "accion" => "A",
            );
            $responCont = MovimientosController::cancel($dataCont, $connection);
            if ($responCont["success"] === false) {
               throw new PDOException($responCont["message"], $responCont["code"]);
            }
         }
         $connection->commit();
         $response = array("success" => true, "message" => "Registro anulado exitosamente");
      } catch (PDOException $ex) {
         $connection->rollBack();
         $response = array("success" => false, "message" => $ex->getMessage(), "code" => $ex->getCode());
      }
      return $response;
   }


   //******************************************************************************************
   static public function anularPagoInt() {
      $required = ['id_pago', 'id_cheque', 'valor'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         $tablaDocum = "DvPagInt";
         $dataDocum = array(
            'valor'  => 0,
            'status' => "A"
         );
         $where = array(
            'id_empresa' => $_SESSION["id_empresa"],
            'id_pago'    => $data["id_pago"]
         );
         $responseDocum = GeneralModel::update($tablaDocum, $dataDocum, $where, $connection);
         if ($responseDocum["success"] === false) {
            throw new PDOException($responseDocum["message"], $responseDocum["code"]);
         }

         $dataChe = array(
            'intereses_pagados' => $data["valor"],
            'id_empresa' => $_SESSION["id_empresa"],
            'id_cheque'  => $data["id_cheque"]
         );
         $responseChe = ChequesModel::actPagoInt($dataChe, $connection);
         if ($responseChe["success"] === false) {
            throw new PDOException($responseChe["message"], $responseChe["code"]);
         }

         if ($data["compte"] != "") {
            $dataCont = array(
               "ComCodig" => $data["compte"],
               "AsiDocum" => $data["id_pago"],
               "accion" => "A",
            );
            $responCont = MovimientosController::cancel($dataCont, $connection);
            if ($responCont["success"] === false) {
               throw new PDOException($responCont["message"], $responCont["code"]);
            }
         }
         $connection->commit();
         $response = array("success" => true, "message" => "Registro anulado exitosamente");
      } catch (PDOException $ex) {
         $connection->rollBack();
         $response = array("success" => false, "message" => $ex->getMessage(), "code" => $ex->getCode());
      }
      return $response;
   }

}