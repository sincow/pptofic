<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/mdlcajas.php';
}
require_once '../'. APP_PATH . '/controllers/contabilidad/ctrmovimientos.php';
require_once '../'. APP_PATH . '/models/contabilidad/mdlmovimientos.php';
require_once '../'. APP_PATH . '/controllers/contabilidad/ctrcuentas.php';
require_once '../'. APP_PATH . '/models/contabilidad/mdlcuentas.php';

class CajasController {


   //*********************************************************************************************
   static public function addDocumCaja(){
      $required = ['tipoDoc', 'terceroVale', 'valDetalle', 'valeFecha', 'entrValor', 'valeValor', 'cuentaVale'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
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

}