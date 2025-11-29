<?php
// if (!isset($_POST["option"])) {
// 	require_once APP_PATH . '/models/mdlreports.php';
// }
if (!isset($_SESSION)) {
   session_start();
}

class ReportsController {

   //**************************************************************************************
   static public function repliquida(){
      $required = ['repNroDomum'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      if (!isset($_POST["GenHojCal"])) {
         $_POST["GenHojCal"] = 0;
      }
      $data = $_POST;
      $letra = ReportsModel::repDocument($data);
      if ($letra == null) {
         return array(
            'success' => false,
            'message' => 'No se encontro el Documento'
         );
      }
      $idCheque = $letra["id_cheque"];
      $dateTime = new DateTime();
      $fechaHora = $dateTime->format('Y-m-d H:i:s');
      $table = "reports";
      $dataUpt = array(
         "last_generate_report" => $fechaHora
      );
      $where = array("link_report" => 'repliquida');
      $userupdt = GeneralModel::update($table, $dataUpt, $where);
      if ($userupdt["success"] == false) {
         // throw new PDOException($userupdt["message"], $userupdt["code"]);
      }

      $token = bin2hex(random_bytes(16));
      $_SESSION['report_temp_' . $token] = [
         'tipo' => 'Liquidación',
         'clase' => $data['clase'],
         'id_cheque' => $idCheque,
         'GenHojCal' => $_POST['GenHojCal'],
         'token' => $token,
         'timestamp' => time()
      ];
      $reportUrl = "app/reports/dival/liquidacion.php?token=" . urlencode($token);
      return array(
         'success' => true,
         'url' => $reportUrl,
         'message' => 'Informe generado correctamente'
      );
   }


   //**************************************************************************************
   static public function repletra(){
      $required = ['repNroDomum'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      if (!isset($_POST["GenHojCal"])) {
         $_POST["GenHojCal"] = 0;
      }
      $data = $_POST;
      if ($data['clase'] == 3) {
         $data["repNroDomum"] = 'P'.str_pad($data["repNroDomum"], 7, "0", STR_PAD_LEFT);
      }
      if ($data['clase'] == 5) {
         $data["repNroDomum"] = 'L'.str_pad($data["repNroDomum"], 7, "0", STR_PAD_LEFT);
      }

      $letra = ReportsModel::repDocument($data);
      if ($letra == null) {
         return array(
            'success' => false,
            'message' => 'No se encontro la letra'
         );
      }
      $idCheque = $letra["id_cheque"];
      $dateTime = new DateTime();
      $fechaHora = $dateTime->format('Y-m-d H:i:s');
      $table = "reports";
      $dataUpt = array(
         "last_generate_report" => $fechaHora
      );
      $where = array("link_report" => 'repletra');
      $userupdt = GeneralModel::update($table, $dataUpt, $where);
      if ($userupdt["success"] == false) {
         // throw new PDOException($userupdt["message"], $userupdt["code"]);
      }

      $token = bin2hex(random_bytes(16));
      $_SESSION['report_temp_' . $token] = [
         'tipo' => 'Letra',
         'clase' => $data['clase'],
         'id_cheque' => $idCheque,
         'GenHojCal' => $_POST['GenHojCal'],
         'token' => $token,
         'timestamp' => time()
      ];
      $reportUrl = "app/reports/dival/letra.php?token=" . urlencode($token);
      return array(
         'success' => true,
         'url' => $reportUrl,
         'message' => 'Informe generado correctamente'
      );
   }


   //**************************************************************************************
   static public function reppagare(){
      $required = ['repNroDomum'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      if (!isset($_POST["GenHojCal"])) {
         $_POST["GenHojCal"] = 0;
      }
      $data = $_POST;
      if ($data['clase'] == 3) {
         $data["repNroDomum"] = 'P'.str_pad($data["repNroDomum"], 7, "0", STR_PAD_LEFT);
      }
      if ($data['clase'] == 5) {
         $data["repNroDomum"] = 'L'.str_pad($data["repNroDomum"], 7, "0", STR_PAD_LEFT);
      }
      $pagare = ReportsModel::repDocument($data);
      if ($pagare == null) {
         return array(
            'success' => false,
            'message' => 'No se encontro el pagare'
         );
      }
      $idCheque = $pagare["id_cheque"];
      $dateTime = new DateTime();
      $fechaHora = $dateTime->format('Y-m-d H:i:s');
      $table = "reports";
      $dataUpt = array(
         "last_generate_report" => $fechaHora
      );
      $where = array("link_report" => 'reppagare');
      $userupdt = GeneralModel::update($table, $dataUpt, $where);
      if ($userupdt["success"] == false) {
         // throw new PDOException($userupdt["message"], $userupdt["code"]);
      }
      $token = bin2hex(random_bytes(16));
      $_SESSION['report_temp_' . $token] = [
         'tipo' => 'Pagaré',
         'clase' => $data['clase'],
         'id_cheque' => $idCheque,
         'GenHojCal' => $_POST['GenHojCal'],
         'token' => $token,
         'timestamp' => time()
      ];
      $reportUrl = "app/reports/dival/pagare.php?token=" . urlencode($token);
      return array(
         'success' => true,
         'url' => $reportUrl,
         'message' => 'Informe generado correctamente'
      );
   }


   //**************************************************************************************
   static public function repplacomisi() {
      $required = ['repFecPlanilla'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      if (!isset($_POST["GenHojCal"])) {
         $_POST["GenHojCal"] = 0;
      }
      $data = $_POST;
      $dateTime = new DateTime();
      $fechaHora = $dateTime->format('Y-m-d H:i:s');
      $table = "reports";
      $dataUpt = array(
         "last_generate_report" => $fechaHora
      );
      $where = array("link_report" => 'repplacomisi');
      $userupdt = GeneralModel::update($table, $dataUpt, $where);
      if ($userupdt["success"] == false) {
         // throw new PDOException($userupdt["message"], $userupdt["code"]);
      }
      $token = bin2hex(random_bytes(16));
      $_SESSION['report_temp_' . $token] = [
         'tipo' => 'Planilla de comisiones',
         'clase' => $data['clase'],
         'fecCambioSearchFrom' => $data['repFecPlanilla'],
         'fecCambioSearchTo' => $data['repFecPlanilla'],
         'GenHojCal' => $_POST['GenHojCal'],
         'token' => $token,
         'timestamp' => time()
      ];
      $reportUrl = "app/reports/dival/placomisi.php?token=" . urlencode($token);
      return array(
         'success' => true,
         'url' => $reportUrl,
         'message' => 'Informe generado correctamente'
      );
   }


   //**************************************************************************************
   static public function repplafectivo() {
      $required = ['repFecPlanilla'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      if (!isset($_POST["GenHojCal"])) {
         $_POST["GenHojCal"] = 0;
      }
      $data = $_POST;
      $dateTime = new DateTime();
      $fechaHora = $dateTime->format('Y-m-d H:i:s');
      $table = "reports";
      $dataUpt = array(
         "last_generate_report" => $fechaHora
      );
      $where = array("link_report" => 'repplafectivo');
      $userupdt = GeneralModel::update($table, $dataUpt, $where);
      if ($userupdt["success"] == false) {
         // throw new PDOException($userupdt["message"], $userupdt["code"]);
      }
      $token = bin2hex(random_bytes(16));
      $_SESSION['report_temp_' . $token] = [
         'tipo' => 'Planilla de efectivo',
         'repFecPlanilla' => $data['repFecPlanilla'],
         'GenHojCal' => $_POST['GenHojCal'],
         'token' => $token,
         'timestamp' => time()
      ];
      $reportUrl = "app/reports/dival/plafectivo.php?token=" . urlencode($token);
      return array(
         'success' => true,
         'url' => $reportUrl,
         'message' => 'Informe generado correctamente'
      );
   }


   //**************************************************************************************
   static public function repplaarqueo() {
      $required = ['repFecPlanilla', 'repValContado'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      if (!isset($_POST["GenHojCal"])) {
         $_POST["GenHojCal"] = 0;
      }
      $data = $_POST;
      $data["repValContado"] = str_replace(',', '', $data["repValContado"]);
      $data["repValContado"] = filter_var($data["repValContado"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $dateTime = new DateTime();
      $fechaHora = $dateTime->format('Y-m-d H:i:s');
      $table = "reports";
      $dataUpt = array(
         "last_generate_report" => $fechaHora
      );
      $where = array("link_report" => 'repplaarqueo');
      $userupdt = GeneralModel::update($table, $dataUpt, $where);
      if ($userupdt["success"] == false) {
         // throw new PDOException($userupdt["message"], $userupdt["code"]);
      }
      $token = bin2hex(random_bytes(16));
      $_SESSION['report_temp_' . $token] = [
         'tipo' => 'Planilla de arqueo',
         'repFecPlanilla' => $data['repFecPlanilla'],
         'repValContado' => $data['repValContado'],
         'GenHojCal' => $_POST['GenHojCal'],
         'token' => $token,
         'timestamp' => time()
      ];
      $reportUrl = "app/reports/dival/plaarqueo.php?token=" . urlencode($token);
      return array(
         'success' => true,
         'url' => $reportUrl,
         'message' => 'Informe generado correctamente'
      );
   }


   //**************************************************************************************
   static public function rephiscliente() {
      $required = ['repIdCliente', 'repFecIniHisCli', 'repFecFinHisCli'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      if (!isset($_POST["GenHojCal"])) {
         $_POST["GenHojCal"] = 0;
      }
      $data = $_POST;
      $dateTime = new DateTime();
      $fechaHora = $dateTime->format('Y-m-d H:i:s');
      $table = "reports";
      $dataUpt = array(
         "last_generate_report" => $fechaHora
      );
      $where = array("link_report" => 'rephiscliente');
      $userupdt = GeneralModel::update($table, $dataUpt, $where);
      if ($userupdt["success"] == false) {
         // throw new PDOException($userupdt["message"], $userupdt["code"]);
      }
      $token = bin2hex(random_bytes(16));
      $_SESSION['report_temp_' . $token] = [
         'tipo' => 'Historial del cliente',
         'TerDocId' => $data['repIdCliente'],
         'repFecIniHisCli' => $data['repFecIniHisCli'],
         'repFecFinHisCli' => $data['repFecFinHisCli'],
         'GenHojCal' => $_POST['GenHojCal'],
         'token' => $token,
         'timestamp' => time()
      ];
      $reportUrl = "app/reports/dival/hiscliente.php?token=" . urlencode($token);
      return array(
         'success' => true,
         'url' => $reportUrl,
         'message' => 'Informe generado correctamente'
      );
   }


   //**************************************************************************************
   static public function reppreliqui() {
      $required = ['repIdCliente', 'repFecPreliq', 'diasHabiles', 'documPreliqList'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      if (!isset($_POST["GenHojCal"])) {
         $_POST["GenHojCal"] = 0;
      }
      if (!isset($_POST["mostrarEstCta"])) {
         $_POST["mostrarEstCta"] = 0;
      } else {
         $_POST["mostrarEstCta"] = 1;
      }
      $data = $_POST;

      $dateTime = new DateTime();
      $fechaHora = $dateTime->format('Y-m-d H:i:s');
      $table = "reports";
      $dataUpt = array(
         "last_generate_report" => $fechaHora
      );
      $where = array("link_report" => 'reppreliqui');
      $userupdt = GeneralModel::update($table, $dataUpt, $where);
      if ($userupdt["success"] == false) {
         // throw new PDOException($userupdt["message"], $userupdt["code"]);
      }
      $tabla = "NoFestiv";
      $order = "FecFesti";
      $fecha = date('Y-m-d', strtotime('-1 years'));
      $where = "EmpCodig = '".$_SESSION["empdef"]."' AND FecFesti >= '".$fecha."' AND FecEstad = 1";
      $diasFestivos = GeneralModel::getAll($tabla, $order, $where);
      // var_dump($diasFestivos);

      $token = bin2hex(random_bytes(16));
      $_SESSION['report_temp_' . $token] = [
         'tipo' => 'Preliquidacion',
         'id_dvcliente' => $data['repIdCliente'],
         'repFecPreliq' => $data['repFecPreliq'],
         'diasHabiles' => $data['diasHabiles'],
         'mostrarEstCta' => $data['mostrarEstCta'],
         'obserPreliq' => $data['obserPreliq'],
         'documPreliqList' => $data['documPreliqList'],
         'diasFestivos' => $diasFestivos,
         'GenHojCal' => $_POST['GenHojCal'],
         'token' => $token,
         'timestamp' => time()
      ];
      $reportUrl = "app/reports/dival/preliqui.php?token=" . urlencode($token);
      return array(
         'success' => true,
         'url' => $reportUrl,
         'message' => 'Informe generado correctamente'
      );

   }

}