<?php
if (!defined('CONFIG_PATH')) {
   // require_once "../config/config.php";
   define("CONFIG_PATH", "../config");
}
require_once CONFIG_PATH . "/Database.php";
if (!isset($_SESSION)) {
	session_start();
}

class MovimientosModel {

   static public function save($data, $connection = null) {
		$conn = false;
		try {
			if ($connection == null) {
				$conn = true;
				$connection = Database::getConnection();
         }
         if ($data["origen"] == 1) {
            $where = "ComCodig = ".$data["ComCodig"];
            $respuesta = GeneralModel::getAll('CoCompro', 'ComCodig', $where, $connection);
            if ($respuesta) {
               $ConConse = $respuesta["ComConse"];
               $AsiDoc = (int)$ConConse;
               $AsiDoc = $AsiDoc + 1;
               $AsiDocum = str_pad((string)($AsiDoc),10, "0",  STR_PAD_LEFT);
            } else {
               $AsiDocum = '0000000001';
            }
         } else {
            $AsiDocum = str_pad((string)($data["AsiDocum"]),10, "0",  STR_PAD_LEFT);
         }
         $SdoAno = date("Y", strtotime(str_replace('/','-',$data["AsiFecha"])));
         $SdoMes = date("m", strtotime(str_replace('/','-',$data["AsiFecha"])));
         $SdoValTe = "";
         $SdoValCe = "";
         $stmtAsi = $connection->prepare("INSERT INTO CoMovimi (EmpCodig, ComCodig, AsiDocum, AsiFecha, 
            AsiDocSo, CueCodig, TerDocId, CenCodig, CenCodAu, AsiDescr, AsiValor, AsiNatur, AsiVBase, 
            ImpCodig, UsuCodig, AsiNumer) VALUES
            (:EmpCodig, :ComCodig, :AsiDocum, :AsiFecha, :AsiDocSo, :CueCodig, :TerDocId, :CenCodig,
            :CenCodAu, :AsiDescr, :AsiValor, :AsiNatur, :AsiVBase, :ImpCodig, :UsuCodig, :AsiNumer)"
         );
         $stmtAsi->bindParam(":EmpCodig", $_SESSION["empdef"], PDO::PARAM_STR);
         $stmtAsi->bindParam(":ComCodig", $data["ComCodig"]);
         $stmtAsi->bindParam(":AsiDocum", $AsiDocum);
         $stmtAsi->bindParam(":AsiFecha", $data["AsiFecha"]);
         $stmtAsi->bindParam(":AsiDocSo", $data["AsiDocSo"]);
         $stmtAsi->bindParam(":UsuCodig", $_SESSION["usuario"], PDO::PARAM_STR);
         if ($data["origen"] == 1) {
            $stmtDoc = $connection->prepare("UPDATE CoCompro SET ComConse = :AsiDocum
               WHERE EmpCodig = :EmpCodig AND ComCodig = :ComCodig ");
            $stmtDoc->bindParam(":EmpCodig", $_SESSION["empdef"], PDO::PARAM_STR);
            $stmtDoc->bindParam(":ComCodig", $data["ComCodig"]);
            $stmtDoc->bindParam(":AsiDocum", $AsiDocum);
         }

         //$acountingList = json_decode($data["partidas"], true);
         $acountingList = $data["partidas"];
         foreach ($acountingList as $acounting) {
				$stmtAsi->bindParam(":CueCodig", $acounting["CueCodig"]);
				$stmtAsi->bindParam(":TerDocId", $acounting["TerDocId"]);
				$stmtAsi->bindParam(":CenCodig", $acounting["CenCodig"]);
				$stmtAsi->bindParam(":CenCodAu", $acounting["CenCodAu"]);
				$stmtAsi->bindParam(":AsiDescr", $acounting["AsiDescr"]);
				$stmtAsi->bindParam(":AsiValor", $acounting["AsiValor"]);
				$stmtAsi->bindParam(":AsiNatur", $acounting["AsiNatur"]);
				$stmtAsi->bindParam(":AsiVBase", $acounting["AsiVBase"]);
				$stmtAsi->bindParam(":ImpCodig", $acounting["ImpCodig"]);
				$stmtAsi->bindParam(":AsiNumer", $acounting["AsiNumer"]);
				$SdoValTe = $acounting["AsiNatur"].$SdoMes."Terce";
            $SenTer = "INSERT INTO CoSdoTer (EmpCodig, SdoAno, CueCodig, TerDocId, ".$SdoValTe.")
            VALUES (:EmpCodig, :SdoAno, :CueCodig, :TerDocId, :SdoValor)
            ON DUPLICATE KEY UPDATE ".$SdoValTe." = ".$SdoValTe." + :SdoValor2";
            $stmtTer = $connection->prepare($SenTer);

				$stmtTer->bindParam(":EmpCodig", $_SESSION["empdef"], PDO::PARAM_STR);
				$stmtTer->bindParam(":SdoAno", $SdoAno);
				$stmtTer->bindParam(":CueCodig", $acounting["CueCodig"]);
				$stmtTer->bindParam(":TerDocId", $acounting["TerDocId"]);
				$stmtTer->bindParam(":SdoValor", $acounting["AsiValor"]);
				$stmtTer->bindParam(":SdoValor2", $acounting["AsiValor"]);
				$SdoValCe = $acounting["AsiNatur"].$SdoMes."CenCo";
            $SenCen = "INSERT INTO CoSdoCen (EmpCodig, SdoAno, CueCodig, 
            CenCodig, CenCodAu, $SdoValCe) VALUES (:EmpCodig, :SdoAno, :CueCodig, :CenCodig, 
            :CenCodAu, :SdoValor) ON DUPLICATE KEY UPDATE $SdoValCe = $SdoValCe + :SdoValor2";
				$stmtCen = $connection->prepare($SenCen);
				$stmtCen->bindParam(":EmpCodig", $_SESSION["empdef"], PDO::PARAM_STR);
				$stmtCen->bindParam(":SdoAno", $SdoAno);
				$stmtCen->bindParam(":CueCodig", $acounting["CueCodig"]);
				$stmtCen->bindParam(":CenCodig", $acounting["CenCodig"]);
				$stmtCen->bindParam(":CenCodAu", $acounting["CenCodAu"]);
				$stmtCen->bindParam(":SdoValor", $acounting["AsiValor"]);
				$stmtCen->bindParam(":SdoValor2", $acounting["AsiValor"]);

				$CueDepen = $acounting["CueCodig"];
				$SdoValCu = $acounting["AsiNatur"].$SdoMes."Cuent";
            $SenPla = "INSERT INTO CoSdoPla (EmpCodig, SdoAno, CueCodig, $SdoValCu)
				VALUES (:EmpCodig, :SdoAno, :CueCodig, :SdoValor) ON DUPLICATE KEY
				UPDATE $SdoValCu = $SdoValCu + :SdoValor2";
				$stmtCue = $connection->prepare($SenPla);
				$stmtCue->bindParam(":EmpCodig", $_SESSION["empdef"], PDO::PARAM_STR);
				$stmtCue->bindParam(":SdoAno", $SdoAno);
				$stmtCue->bindParam(":CueCodig", $CueDepen);
				$stmtCue->bindParam(":SdoValor", $acounting["AsiValor"]);
				$stmtCue->bindParam(":SdoValor2", $acounting["AsiValor"]);
				while ($CueDepen != "") {
					$stmtCue->execute();
					if(strlen($CueDepen) == 9){
						$CueDepen = substr($CueDepen,0,-3);
					}else if(strlen($CueDepen) == 6){
						$CueDepen = substr($CueDepen,0,-2);
					}else if(strlen($CueDepen) == 4){
						$CueDepen = substr($CueDepen,0,-2);
					}else if(strlen($CueDepen) == 2){
						$CueDepen = substr($CueDepen,0,-1);
					}else{
						$CueDepen = "";
					}
				}
				$stmtAsi->execute();
            $stmtTer->execute();
            $stmtCen->execute();
         }
         if ($data["origen"] == 1) {
				$stmtDoc->execute();
         }
			$response = array("success" => true, "message" => 'Registro guardado exitosamente', "lastId" => $AsiDocum);
      } catch (PDOException $e) {
         // var_dump($e->errorInfo[1]);
			$errorInfo = GeneralController::handleMySQLerror($e->errorInfo[1], $e->errorInfo[2]);
			if (ENVIRONMENT == 'development') {
				$messageError = $errorInfo["error_code"]." ".$errorInfo["technical_message"];
			} else {
				$messageError = $errorInfo["error_code"]." ".$errorInfo["error_message"];
			}
			$response = array("success" => false, "message" => $messageError, "code" => $errorInfo["error_code"]);
      }

      if ($conn == true) {
         $connection = null;
      }
      return $response;
   }


}