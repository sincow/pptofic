<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/dival/mdlcajas.php';
}
require_once '../'. APP_PATH . '/controllers/contabilidad/ctrmovimientos.php';
require_once '../'. APP_PATH . '/models/contabilidad/mdlmovimientos.php';
require_once '../'. APP_PATH . '/controllers/contabilidad/ctrcuentas.php';
require_once '../'. APP_PATH . '/models/contabilidad/mdlcuentas.php';

class CajasController {

   //*******************************************************************************************
   static public function filter(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
		$data = filter_input_array(INPUT_POST);
      $especies = CajasModel::filter($data);
      return $especies;
   }


   //*********************************************************************************************
   static public function addDocumCaja(){
      $required = ['tipoDoc', 'terceroVale', 'valDetalle', 'valeFecha', 'entrValor', 'valeValor', 'cuentaVale'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      if ($data["tipoDoc"] == '1') {
         if (!isset($data["BancoCodig"])) {
            return array("success" => false, "message" =>  "Por favor, completa todos los campos");
         }
      } else {
         $data["BancoCodig"] = "";
      }
      if ($data["tipoDoc"] == '1' || $data["tipoDoc"] == '2') {
         $data["entrValor"] = str_replace(',', '', $data["entrValor"]);
         $data["entrValor"] = filter_var($data["entrValor"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
         if ($data['entrValor'] <= 0 || $data['entrValor'] == null) {
            return array("success" => false, "message" =>  "Valor de la Entrada de Efectivo debe ser mayor a 0");
         }
      }
      if ($data["tipoDoc"] == '3') {
         $data["valeValor"] = str_replace(',', '', $data["valeValor"]);
         $data["valeValor"] = filter_var($data["valeValor"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
         if ($data['valeValor'] <= 0 || $data['valeValor'] == null) {
            return array("success" => false, "message" =>  "Valor del Vale de caja debe ser mayor a 0");
         }
      }
      $data["consecutivo"] = 1;
      $lastRecord = CajasModel::getLastVale();
      if ($lastRecord != null ) {
         $data["consecutivo"] = $lastRecord["consecutivo"] + 1;
      }

      $tabla = "DvMovCaj";
      $dataUpdt = array(
         'id_empresa'      => $_SESSION["id_empresa"],
         'tipo_movimiento' => $data["tipoDoc"],
         'consecutivo'     => $data["consecutivo"],
         'BanCodig'        => $data["BancoCodig"],
         'CueCodig'        => $data["cuentaVale"],
         'TerDocId'        => $data["terceroVale"],
         'descripcion'     => $data["valDetalle"],
         'fecha'           => $data["valeFecha"],
         'valor_entrada'   => $data["entrValor"],
         'valor_salida'    => $data["valeValor"],
         'CajConta'        => '1',
         'status'          => "1",
         'id_user'         => $_SESSION["user_id"]
      );
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         $response = GeneralModel::save($tabla, $dataUpdt, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }
         $AsiDocum = $response["lastId"];
         if ($data["compte"] != "") {
            $datos = array(
               "ComCodig" => $data["compte"],
               "AsiDocum" => $AsiDocum,
               "AsiDocSo" => $AsiDocum,
               "TerDocId" => $data["terceroVale"],
               "CenCodig" => isset($data["centroVale"]) ? $data["centroVale"] : '0001',
               "CenCodAu" => "",
               "AsiFecha" => $data["valeFecha"],
               "origen"   => 2,
               "partidas" => $data["acountingList"]
            );
            $responCont = MovimientosController::save($datos, null);
            if ($responCont["success"] === false) {
               throw new PDOException($responCont["message"], $responCont["code"]);
            }
         }

         if ($data["CompteBco"] != "" & $data["tipoDoc"] == '1') {
            $tabla = "BaMovimi";
            $datos = array(
               "EmpCodig" => $_SESSION["empdef"],
               "ConCodig" => $data["CompteBco"],
               "MovDocum" => $AsiDocum,
               "TipCodig" => "",
               "MovFecha" => $data["valeFecha"],
		         "BanCodig" => $data["BancoCodig"],
               "CheCodig" => "",
               "TerDocId" => $_SESSION['companyid'],
               "MovDetal" => $data["valDetalle"],
               "MovValor" => $data["entrValor"],
               "MovChequ" => "",
               "MovEstad" => "1",
               "UsuCodig" => $_SESSION["user_id"]
            );
            $responCont = GeneralModel::save($tabla, $datos, $connection);
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
				'id_movimiento' => $AsiDocum,
				'tipoDoc' => $data["tipoDoc"],
				'GenHojCal' => 0,
				'token' => $token,
				'timestamp' => time()
			];
         switch ($data["tipoDoc"]) {
            case '2':
               $reportUrl = "app/reports/dival/entradaefectivo.php?token=" . urlencode($token);
               break;
            case '3':
               $reportUrl = "app/reports/dival/valecaja.php?token=" . urlencode($token);
               break;
            default:
               break;
         }
		}
		return array("success" => $response["success"], "message" =>  $response["message"], "reportUrl" => $reportUrl);
   }


   //*********************************************************************************************
   static public function getByNum(){
      $required = ['numDocum', 'tipoDoc'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $response = CajasModel::getByNum($data);
      return $response;
   }


   //*********************************************************************************************
   static public function anular() {
      $required = ['tipoDoc', 'id_movimiento'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      if ($data["tipoDoc"] == '1') {
         $tablaQuery = "BaTipMov";
         $order = "ComCodig";
         $whereQuery = "EmpCodig = '".$_SESSION["empdef"]."' AND ConCodig = '".$data["CompteBco"]."'";
         $CompteBco = GeneralModel::getAll($tablaQuery, $order, $whereQuery);
         if ($CompteBco == null) {
            return array("success" => false, "message" =>  "No se ha definido el Concepto de Egresos en parámetros");
         }
         $data["compte"] = $CompteBco[0]["ComCodig"];
      }

      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         $tablaDocum = "DvMovCaj";
         $dataDocum = array(
            'valor_entrada' => 0,
            'valor_salida'  => 0,
            'status'       => "A"
         );
         $where = array(
            'id_empresa' => $_SESSION["id_empresa"],
            'id_movimiento'  => $data["id_movimiento"]
         );
         $responseDocum = GeneralModel::update($tablaDocum, $dataDocum, $where, $connection);
         if ($responseDocum["success"] === false) {
            throw new PDOException($responseDocum["message"], $responseDocum["code"]);
         }

         if ($data["CompteBco"] != "" & $data["tipoDoc"] == '1') {
            $tablaBco = "BaMovimi";
            $dataBco = array(
               'MovValor' => 0,
               'MovEstad' => "0"
            );
            $whereBco = array(
               'EmpCodig' => $_SESSION["empdef"],
               'ConCodig' => $data["CompteBco"],
               'MovDocum' => intval($data["id_movimiento"])
            );
            $responseBco = GeneralModel::update($tablaBco, $dataBco, $whereBco, $connection);
            if ($responseBco["success"] === false) {
               throw new PDOException($responseBco["message"], $responseBco["code"]);
            }
         }

         if ($data["compte"] != "") {
            $dataCont = array(
               "ComCodig" => $data["compte"],
               "AsiDocum" => $data["id_movimiento"],
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