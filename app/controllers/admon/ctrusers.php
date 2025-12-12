<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/admon/mdlusers.php';
}

class UsersController {

   //*****************************************************************************************
   static public function index(){
      $especies = UsersModel::getAll(null);
      return $especies;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $especies = UsersModel::getWhere($data);
      return $especies;
   }


   //*****************************************************************************************
   static public function getOne() {
      $required = ['id'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      if ($data["id"] == "*") {
         $data["id"] = $_SESSION["user_id"];
      }

      $data["id"] = filter_var($data["id"], FILTER_SANITIZE_NUMBER_INT);
      // var_dump($data);
      if (!filter_var($data["id"], FILTER_VALIDATE_INT)) {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      $user = UsersModel::getOne($data);
      unset($user['password']);
      return $user;
   }


   //*****************************************************************************************
   static public function create() {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => "Método inválido");
         return $response;
      }
      $data = $_POST;
      $required = ['password', 'nombre', 'email', 'role'];
      foreach ($required as $field) {
         if (empty($_POST[$field]) || $_POST[$field] == "") {
            $response = array("success" => false, "message" => "Campo $field es requerido");
            return $response;
         }
      }

      $data["nombre"] = trim($data["nombre"]);
      $data["nombre"] = strip_tags($data["nombre"]);
      $data["nombre"] = strtoupper(htmlspecialchars($data["nombre"], ENT_QUOTES, 'UTF-8'));

      $data["email"] = trim($data["email"]);
      $data["email"] = strip_tags($data["email"]);
      $data["email"] = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
      if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
         $response = array("success" => false, "message" => htmlspecialchars("Campo email es inválido", ENT_QUOTES, 'UTF-8'));
         return $response;
      }

      $data["role"] = trim($data["role"]);
      $data["role"] = strip_tags($data["role"]);
      $data["role"] = filter_var($data["role"], FILTER_SANITIZE_NUMBER_INT);

      $encriptar = crypt($_POST["password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

      $photo = "assets/img/team/avatar.png";
      if(isset($_FILES["photo"]["tmp_name"]) && $_FILES['photo']['error'] === UPLOAD_ERR_OK){
         list($ancho, $alto) = getimagesize($_FILES["photo"]["tmp_name"]);
         $nuevoAncho = 500;
         $nuevoAlto = 500;
         $directorio = "../assets/img/users/".$_SESSION["id_empresa"];
         if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
         }
         $aleatorio = date("Y-m-d H:i:s").mt_rand(100,999);
         $aleatorio = str_replace(array(" ", "-", ":"), "", $aleatorio);
         if($_FILES["photo"]["type"] == "image/jpeg"){
            $photo = $directorio."/".$aleatorio.".jpg";
            $origen = imagecreatefromjpeg($_FILES["photo"]["tmp_name"]);
            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
            imagejpeg($destino, $photo);
         }
         if($_FILES["photo"]["type"] == "image/png"){
            $photo = $directorio."/".$aleatorio.".png";
            $origen = imagecreatefrompng($_FILES["photo"]["tmp_name"]);
            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
            imagepng($destino, $photo);
         }
         if($_FILES["photo"]["type"] == "image/webp"){
            $photo = $directorio."/".$aleatorio.".webp";
            $origen = imagecreatefromwebp($_FILES["photo"]["tmp_name"]);
            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
            imagewebp($destino, $photo);
         }
         $photo = str_replace("../", "", $photo);
      }
      $data["photo"] = $photo;
      $data["password"] = $encriptar;
      // $data["token_recovery"] = bin2hex(random_bytes(16));
      // $data["token_expiration"] = date("Y-m-d H:i:s", strtotime('+1 hour'));
      $data["token_recovery"] = null;
      $data["token_expiration"] = null;
      $data["host_user"] = $_SESSION["empdb"];
      $data["user_user"] = $_SESSION["empusu"];
      $data["pass_user"] = $_SESSION["empcla"];
      $response = UsersModel::create($data);
      return $response;
   }


   //*****************************************************************************************
   static public function update() {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => "Método inválido");
         return $response;
      }
      $data = $_POST;
      if (empty($data["id"]) || !filter_var($data["id"], FILTER_VALIDATE_INT)) {
         $response = array("success" => false, "message" => "Registro inválido (Id)");
         return $response;
      }
      $required = ['id', 'nombre', 'email', 'role'];
      foreach ($required as $field) {
         if (empty($_POST[$field]) || $_POST[$field] == "") {
            $response = array("success" => false, "message" => "Campo $field es requerido");
            return $response;
         }
      }
      $data["id"] = filter_var($data["id"], FILTER_SANITIZE_NUMBER_INT);
      $data["nombre"] = trim($data["nombre"]);
      $data["nombre"] = strip_tags($data["nombre"]);
      $data["nombre"] = strtoupper(htmlspecialchars($data["nombre"], ENT_QUOTES, 'UTF-8'));

      $data["email"] = trim($data["email"]);
      $data["email"] = strip_tags($data["email"]);
      $data["email"] = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
      if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
         $response = array("success" => false, "message" => htmlspecialchars("Campo email es inválido", ENT_QUOTES, 'UTF-8'));
         return $response;
      }
      $data["role"] = trim($data["role"]);
      $data["role"] = strip_tags($data["role"]);
      $data["role"] = filter_var($data["role"], FILTER_SANITIZE_NUMBER_INT);

      $photo = $_POST["photoPrev"];
      if(isset($_FILES["photo"]["tmp_name"]) && $_FILES['photo']['error'] === UPLOAD_ERR_OK){
         list($ancho, $alto) = getimagesize($_FILES["photo"]["tmp_name"]);
         $nuevoAncho = 500;
         $nuevoAlto = 500;
         $directorio = "../assets/img/users/".$_SESSION["id_empresa"];
         if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
         }
         $aleatorio = mt_rand(100,999);
         if($_FILES["photo"]["type"] == "image/jpeg"){
            $photo = $directorio."/".$_POST["id"].$aleatorio.".jpg";
            $origen = imagecreatefromjpeg($_FILES["photo"]["tmp_name"]);
            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
            imagejpeg($destino, $photo);
         }
         if($_FILES["photo"]["type"] == "image/png"){
            $photo = $directorio."/".$_POST["id"].$aleatorio.".png";
            $origen = imagecreatefrompng($_FILES["photo"]["tmp_name"]);
            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
            imagepng($destino, $photo);
         }
         if($_FILES["photo"]["type"] == "image/webp"){
            $photo = $directorio."/".$_POST["id"].$aleatorio.".webp";
            $origen = imagecreatefromwebp($_FILES["photo"]["tmp_name"]);
            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
            imagewebp($destino, $photo);
         }
         $photo = str_replace("../", "", $photo);
      }
      $data["photo"] = $photo;
      $response = UsersModel::update($data);
      if ($response["success"] && $_POST["photoPrev"] != $photo) {
         $photoPrev = "../".$_POST["photoPrev"];
         if (file_exists($photoPrev)) {
            unlink($photoPrev);
         }
      }
      return $response;
   }


   //*****************************************************************************************
   static public function updtPass() {
      $required = ['id', 'passAct', 'pass', 'passRep'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      if (empty($data["id"]) || !filter_var($data["id"], FILTER_VALIDATE_INT)) {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      $data["id"] = filter_var($data["id"], FILTER_SANITIZE_NUMBER_INT);
      $user = UsersModel::getOne($data);
      if ($user == null) {
         $response = array("success" => false, "message" => 'Usuario inválido');
         return $response;
      }
      if (!password_verify($data["passAct"], $user['password'])) {
         $response = array("success" => false, "message" => 'Contraseña actual inválida');
         return $response;
      }
      if ($data["pass"] != $data["passRep"]) {
         $response = array("success" => false, "message" => 'Error al verificar contraseña');
         return $response;
      }
      $encriptar = crypt($data["pass"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

      $data["pass"] = password_hash($data["pass"], PASSWORD_DEFAULT);
      try {
         $connection = Database::getConnection();
         $connection->beginTransaction();
         $tabla = "users";
         $dataUpdt = array(
            'password' => $encriptar
         );
         $where = array(
            "id_empresa" => $_SESSION["id_empresa"],
            "id_user" => $data["id"]
         );
         $response = GeneralModel::update($tabla, $dataUpdt, $where, $connection);
         if ($response["success"] === false) {
            throw new PDOException($response["message"], $response["code"]);
         }
         $connection->commit();
         $response = array("success" => true, "message" => "Registro guardado exitosamente");
      } catch (PDOException $ex) {
         $connection->rollBack();
         $response = array("success" => false, "message" => $ex->getMessage(), "code" => $ex->getCode());
      }
		$reportUrl = null;
		return array("success" => $response["success"], "message" =>  $response["message"], "reportUrl" => $reportUrl);
   }


   //*****************************************************************************************
   static public function delete() {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => "Método inválido");
         return $response;
      }
      if (empty($_POST["id"]) || $_POST["id"] == "") {
         $response = array("success" => false, "message" => "Debe seleccionar un Usuario");
         return $response;
      }
      if (!filter_var($_POST["id"], FILTER_VALIDATE_INT)) {
         $response = array("success" => false, "message" => "Registro inválido");
         return $response;
      }
      $data = $_POST;
      $data["id"] = filter_var($data["id"], FILTER_SANITIZE_NUMBER_INT);
      $response = UsersModel::delete($data);
      return $response;
   }
}