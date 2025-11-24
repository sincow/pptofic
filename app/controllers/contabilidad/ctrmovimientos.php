<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/mdlcheques.php';
}
if (isset($_POST["action"]) && $_POST["action"] == "create") {
   require_once '../'. APP_PATH . '/models/dival/mdlclientes.php';
}

class MovimientosController {


   //**************************************************************************************
   static public function save($data, $connection = null) {
      $acountingList = json_decode($data["partidas"], true);
      $compteList = [];
      $conse = 0;
		$conn = false;
		try {
			if ($connection == null) {
				$conn = true;
				$connection = Database::getConnection();
			}
         foreach ($acountingList as $acounting) {
            $conse++;
            $CueCod = $acounting["CueCodig"];
            $TerDocId = 0;
            $CenCodig = "";
            $CenCodAu = "";
            $ImpCodig = 0;
            $cuenta = CuentasController::getOne($CueCod);
            if ($cuenta == null) {
               throw new PDOException("La Cuenta contable ".$CueCod." de la partida ".$conse." No existe", 400);
            }
            if ($cuenta["CueTerce"] == 1) {
               if ($acounting["TerDocId"] == 0 || $acounting["TerDocId"] == "") {
                  throw new PDOException("La Cuenta contable ".$CueCod." de la partida ".$conse." debe tener un Tercero", 400);
               }
               $TerDocId = $acounting["TerDocId"];
            }
            if ($cuenta["CueCenCo"] == 1) {
               if ($acounting["CenCodig"] == 0 || $acounting["CenCodig"] == "") {
                  throw new PDOException("La Cuenta contable ".$CueCod." de la partida ".$conse." debe tener un Centro de Costos", 400);
               }
               $CenCodig = $acounting["CenCodig"] ?? "";
               $CenCodAu = $acounting["CenCodAu"] ?? "";
            }
            $ImpCodig = $cuenta["ImpCodig"];
            $compteList[] = [
               "CueCodig" => $acounting["CueCodig"],
               "TerDocId" => $TerDocId,
               "CenCodig" => $CenCodig,
               "CenCodAu" => $CenCodAu,
               "AsiDescr" => $acounting["AsiDescr"],
               "AsiValor" => $acounting["AsiValor"],
               "AsiNatur" => $acounting["AsiNatur"],
               "AsiVBase" => $acounting["AsiVBase"],
               "ImpCodig" => $ImpCodig,
               "UsuCodog" => $_SESSION["user_id"],
               "AsiEstad" => "1",
               "AsiNumer" => $conse
            ];
         }
         $data = array(
            "ComCodig" => $data["ComCodig"],
            "AsiDocum" => $data["AsiDocum"],
            "AsiDocSo" => $data["AsiDocSo"],
            "origen"   => $data["origen"],
            "AsiFecha" => $data["AsiFecha"],
            "partidas" => $compteList
         );
         $response = MovimientosModel::save($data, $connection);
      } catch (PDOException $e) {
         $response = array("success" => false, "message" => $e->getMessage(), "code" => $e->getCode());
      }
      if ($conn == true) {
         $connection = null;
      }
      return $response;
   }

}