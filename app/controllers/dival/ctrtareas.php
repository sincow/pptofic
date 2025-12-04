<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/mdltareas.php';
}

class TareasController {

   //******************************************************************************************
   static public function index(){
      $tareas = TareasModel::getAll(null);
      return $tareas;
   }


   //******************************************************************************************
   static public function addtarea(){
      $required = ['iduser', 'fecha', 'fechaentrega', 'titulo', 'detalle', 'prioridad' ];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["numero"] = 1;
      $lastRecord = TareasModel::getLastTarea();
      if ($lastRecord != null ) {
         $data["numero"] = $lastRecord["numero"] + 1;
      }

      $tabla = "GrNotifi";
      $dataUpdt = array(
         'id_empresa'    => $_SESSION["id_empresa"],
         'tipo'          => '1',
         'numero'        => $data["numero"],
         'id_user'       => $data["iduser"],
         'fecha'         => $data["fecha"],
         'fecha_entrega' => $data["fechaentrega"],
         'titulo'        => $data["titulo"],
         'detalle'       => $data["detalle"],
         'prioridad'     => $data["prioridad"],
         'status'        => "1",
         'user_id_user'  => $_SESSION["user_id"]
      );
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         $response = GeneralModel::save($tabla, $dataUpdt, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }
         $idNotifi = $response["lastId"];

         $connection->commit();
         $response = array("success" => true, "message" => "Registro guardado exitosamente", "lastId" => $idNotifi);
      } catch (PDOException $ex) {
         $connection->rollBack();
         $response = array("success" => false, "message" => $ex->getMessage(), "code" => $ex->getCode());
      }
		$reportUrl = null;
		if ($response["success"] == true) {
			$token = bin2hex(random_bytes(16));
			$_SESSION['report_temp_' . $token] = [
				'id_notifi' => $idNotifi,
				'GenHojCal' => 0,
				'token' => $token,
				'timestamp' => time()
			];
         $reportUrl = "app/reports/dival/tarea.php?token=" . urlencode($token);
		}
		return array("success" => $response["success"], "message" =>  $response["message"], "reportUrl" => $reportUrl);
   }

}