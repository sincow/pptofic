<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/mdlcheques.php';
}
if (isset($_POST["action"]) && $_POST["action"] == "create") {
   require_once '../'. APP_PATH . '/models/dival/mdlclientes.php';
}

class ChequesController {

   //******************************************************************************************
   static public function create() {
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
      if ($saldoCliente == null) {
         $response = array("success" => false, "message" => 'No se pudo consultar cupo del cliente');
         return $response;
      }
      $valCartera = 0;
      $cupo = $saldoCliente[0]["valor_cupo"] + $saldoCliente[0]["valor_cupotemporal"];
      foreach ($saldoCliente as $key => $value) {
         $valCartera = $valCartera + $value["valor_cheque"] - $value["capital_pagado"] ;
      }
      $valCheque =  str_replace(",", "", $_POST["valor_cheque"]);
      if ($cupo - $valCartera < $valCheque) {
         $response = array("success" => false, "message" => 'Valor del cheque es mayor que cupo disponible');
         return $response;
      }

      $data = $_POST;

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
      
      // Validar tipo
      if(!in_array($_FILES["imagen"]["type"], $allowedTypes)){
         $response = array("success" => false, "message" => 'Formato no permitido. Solo JPG y PDF');
         return $response;
      }
      
      // Validar tamaño
      if($_FILES["imagen"]["type"] == "image/jpeg" && $_FILES["imagen"]["size"] > 2097152){
         $response = array("success" => false, "message" => "La imagen es muy grande. Máximo " . (2097152 / 1024 / 1024) . "MB");
         return $response;
      }
      
      if($_FILES["imagen"]["type"] == "application/pdf" && $_FILES["imagen"]["size"] > 5242880){
         $response = array("success" => false, "message" => "El PDF es muy grande. Máximo " . (5242880 / 1024 / 1024) . "MB");
         return $response;
      }
      
      // Validar que no esté vacío
      if($_FILES["imagen"]["size"] == 0){
         return "El archivo está vacío";
         $response = array("success" => false, "message" => '"El archivo con la imágen dle cheque está vacío');
         return $response;
      }

      // Si pasa la validación, procesar el archivo
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

      /*
      if($_FILES["imagen"]["type"] == "image/jpeg"){
         $photo = $directorio."/".$_POST["id_dvcliente"].$data["numero"].".pdf";
         $origen = imagecreatefromjpeg($_FILES["imagen"]["tmp_name"]);
         $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
         imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
         imagejpeg($destino, $photo);
      }
      */

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
         'consecutivo'         => '0000000',
         'id_dvcliente'        => $data["id_dvcliente"],
         'TerDocId'            => $data["TerDocId"],
         'numero'              => $data["numero"],
         'id_bancli'           => $data["numero_cuenta"],
         'fecha'               => $data["fecha"],
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
      $response = GeneralModel::save($tabla, $dataUpdt, null);
      return $response;
   }
}