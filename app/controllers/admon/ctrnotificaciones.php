<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/admon/mdlnotificaciones.php';
}

class NotificacionesController {

   //******************************************************************************************
   static public function index(){
      $notificacione = NotificacionesModel::getAll(null);
      return $notificacione;
   }


   //*******************************************************************************************
   static public function filter(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
		$data = filter_input_array(INPUT_POST);
      $especies = NotificacionesModel::filter($data);
      return $especies;
   }


   //******************************************************************************************
   static public function getMisNotificaciones(){
      $data = $_POST;
      $notificaciones = NotificacionesModel::getMisNotificaciones($data);
      return $notificaciones;
   }


   //******************************************************************************************
   static public function getNotificacion() {
      $required = ['idNotifi'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      if (empty($data["idNotifi"]) || !filter_var($data["idNotifi"], FILTER_VALIDATE_INT)) {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      $data["id_notifi"] = filter_var($data["idNotifi"], FILTER_SANITIZE_NUMBER_INT);
      $notificacion = NotificacionesModel::getOne($data);
      $tabla = "GrNotifi";
      $dataUpdt = array(
         "revisada"  => "1"
      );
      $where = array(
         "id_empresa" => $_SESSION["id_empresa"],
         "id_notifi" => $data["id_notifi"]
      );
      $response = GeneralModel::update($tabla, $dataUpdt, $where);

      return $notificacion;
   }


   //******************************************************************************************
   static public function getDetails() {
      $required = ['idNotifi'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      if (empty($data["idNotifi"]) || !filter_var($data["idNotifi"], FILTER_VALIDATE_INT)) {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      $data["id_notifi"] = filter_var($data["idNotifi"], FILTER_SANITIZE_NUMBER_INT);
      $responseOne = NotificacionesModel::getOne($data);
      $responseSeg = null;
      $responseRep = null;
      $responseCie = null;
      if ($responseOne != null ) {
         $data["tipo"] = "12";
         $responseSeg = NotificacionesModel::getSeguimientos($data);
         // $data["tipo"] = "2";
         // $responseRep = NotificacionesModel::getSeguimientos($data);
         $data["tipo"] = "3";
         $responseCie = NotificacionesModel::getSeguimientos($data);
      }
      $response = array("tarea" => $responseOne, "seguim" => $responseSeg, "cierre" => $responseCie);
      return $response;
   }


   //******************************************************************************************
   static public function addnotify() {
      $required = ['iduser', 'fecha', 'fechaentrega', 'titulo', 'detalle', 'prioridad' ];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      if (empty($data["iduser"]) || !filter_var($data["iduser"], FILTER_VALIDATE_INT)) {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      $data["iduser"] = filter_var($data["iduser"], FILTER_SANITIZE_NUMBER_INT);
      $data["titulo"] = trim($data["titulo"]);
      $data["titulo"] = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
      $data["titulo"] = strip_tags($data["titulo"]);
      $data["titulo"] = strtoupper(htmlspecialchars($data["titulo"], ENT_QUOTES, 'UTF-8'));
      // $data["detalle"] = trim($data["detalle"]);
      // $data["detalle"] = filter_input(INPUT_POST, 'detalle', FILTER_SANITIZE_SPECIAL_CHARS);
      // $data["detalle"] = strip_tags($data["detalle"]);
      // $data["detalle"] = strtoupper(htmlspecialchars($data["detalle"], ENT_QUOTES, 'UTF-8'));

      $data["numero"] = 1;
      $lastRecord = NotificacionesModel::getLastNotificacion();
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


   //******************************************************************************************
   static public function follownotify() {
      $required = ['idtarea', 'idTipo', 'fecha', 'comentario', 'cumplimiento' ];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      $data["idtarea"] = filter_var($data["idtarea"], FILTER_SANITIZE_NUMBER_INT);
      $data["idTipo"] = filter_var($data["idTipo"], FILTER_SANITIZE_NUMBER_INT);
      $data["cumplimiento"] = filter_var($data["cumplimiento"], FILTER_SANITIZE_NUMBER_INT);
      // $data["comentario"] = trim($data["comentario"]);
      // $data["comentario"] = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_SPECIAL_CHARS);
      // $data["comentario"] = strip_tags($data["comentario"]);
      // $data["comentario"] = strtoupper(htmlspecialchars($data["comentario"], ENT_QUOTES, 'UTF-8'));
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         if ($data["idTipo"] == 2) {
            $tabla = "GrNotifi";
            $dataUpdt = array(
               'fecha_reprogra' => $data["fecha"]
               // 'user_id_user' => $_SESSION["user_id"]
            );
            $where = array(
               "id_empresa" => $_SESSION["id_empresa"],
               "id_notifi" => $data["idtarea"]
            );
            $response = GeneralModel::update($tabla, $dataUpdt, $where, $connection);
            if ($response["success"] === false) {
               throw new PDOException($response["message"], $response["code"]);
            }
         } else if ($data["idTipo"] == 3) {
            $tabla = "GrNotifi";
            $dataUpdt = array(
               'cumplimiento' => $data["cumplimiento"],
               'status'       => "9"
               // 'user_id_user' => $_SESSION["user_id"]
            );
            $where = array(
               "id_empresa" => $_SESSION["id_empresa"],
               "id_notifi" => $data["idtarea"]
            );
            $response = GeneralModel::update($tabla, $dataUpdt, $where, $connection);
            if ($response["success"] === false) {
               throw new PDOException($response["message"], $response["code"]);
            }
         }
         $tabla = "GrNotiSeg";
         $dataUpdt = array(
            'id_empresa' => $_SESSION["id_empresa"],
            'id_notifi'  => $data["idtarea"],
            'tipo'       => $data["idTipo"],
            'fecha'      => $data["fecha"],
            'detalle'    => $data["comentario"],
            'status'     => "1",
            'id_user'    => $_SESSION["user_id"]
         );
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
		return array("success" => $response["success"], "message" =>  $response["message"], "reportUrl" => $reportUrl);
   }

}