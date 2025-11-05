<?php
if (!defined('CONFIG_PATH')) {
   // require_once "../config/config.php";
   define("CONFIG_PATH", "../config");
}
require_once CONFIG_PATH . "/Database.php";
if (!isset($_SESSION)) {
	session_start();
}

class UsersModel {

   /**********************************************************************/
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT a.id_user, a.id_empresa, a.name, a.email, a.password, 
         a.id_rol, a.photo, a.token_recovery, a.token_expiration, a.host_user, a.user_user, 
         a.pass_user, a.status, a.id_user_at, a.created_at, a.updated_at, b.description as rol 
         FROM users a 
         LEFT JOIN roles b ON a.id_rol = b.id_rol 
         WHERE a.id_empresa = :id_empresa 
         ORDER BY a.name"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   /**********************************************************************/
   static public function getOne($id) {
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.id_user, a.id_empresa, a.name, a.email, a.password, 
         a.id_rol, a.photo, a.token_recovery, a.token_expiration, a.host_user, a.user_user, 
         a.pass_user a.status, a.id_user_at, a.created_at, a.updated_at 
         FROM users a 
         WHERE a.id_user = :id_user"
      );
      $stmt->bindParam(":id_user", $id, PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   /**********************************************************************/
   static public function create($data) {
      try {
         $connection = Database::getConnection();
         $stmt = $connection->prepare("INSERT INTO users (id_empresa, name, email, password, 
            id_rol, photo, token_recovery, token_expiration, host_user, user_user, pass_user, 
            id_user_at) VALUES 
            (:id_empresa, :name, :email, :password, :id_rol, :photo, :token_recovery, :token_expiration, 
            :host_user, :user_user, :pass_user, :id_user_at)"
         );
         $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
         $stmt->bindParam(":name", $data["nombre"], PDO::PARAM_INT);
         $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
         $stmt->bindParam(":password", $data["password"], PDO::PARAM_STR);
         $stmt->bindParam(":id_rol", $data["rol"], PDO::PARAM_INT);
         $stmt->bindParam(":photo", $data["photo"], PDO::PARAM_STR);
         $stmt->bindParam(":token_recovery", $data["token_recovery"], PDO::PARAM_STR);
         $stmt->bindParam(":token_expiration", $data["token_expiration"], PDO::PARAM_STR);
         $stmt->bindParam(":host_user", $data["host_user"], PDO::PARAM_STR);
         $stmt->bindParam(":user_user", $data["user_user"], PDO::PARAM_STR);
         $stmt->bindParam(":pass_user", $data["pass_user"], PDO::PARAM_STR);
         $stmt->bindParam(":id_user_at", $_SESSION['user_id'], PDO::PARAM_INT);
         $resp = $stmt->execute();
         $response = array("success" => true, "message" => "Usuario creado correctamente");
      } catch (PDOException $e) {
			if ($e->getCode() == 23000) {
				$result = "Ya existe un Usuario con esta información.";
			}else {
				$result = htmlspecialchars($e->getMessage());
			}
         $response = array("success" => false, "message" => $result);
      }
      $stmt = null;
      $connection = null;
      return $response;
   }


   /**********************************************************************/
   static public function update($data) {
      try {
         $connection = Database::getConnection();
         $stmt = $connection->prepare("UPDATE users SET name = :name, 
            email = :email, id_rol = :id_rol, photo = :photo, id_user_at = :id_user_at 
            WHERE id_user = :id_user"
         );
         $stmt->bindParam(":id_user", $data["id"], PDO::PARAM_INT);
         $stmt->bindParam(":name", $data["nombre"], PDO::PARAM_STR);
         $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
         $stmt->bindParam(":id_rol", $data["rol"], PDO::PARAM_STR);
         $stmt->bindParam(":photo", $data["photo"], PDO::PARAM_STR);
         $stmt->bindParam(":id_user_at", $_SESSION['user_id'], PDO::PARAM_INT);
         $resp = $stmt->execute();
         $response = array("success" => true, "message" => "Registro actualizado correctamente");
      } catch (PDOException $e) {
			$result = htmlspecialchars($e->getMessage());
         $response = array("success" => false, "message" => $result);
      }
      $stmt = null;
      $connection = null;
      return $response;
   }


   /**********************************************************************/
   static public function delete($data) {
      try {
         $connection = Database::getConnection();
         $stmt = $connection->prepare("UPDATE users SET status = :status WHERE id_user = :id_user");
         $stmt->bindParam(":id_user", $data["id"], PDO::PARAM_INT);
         $stmt->bindParam(":status", $data["status"], PDO::PARAM_INT);
         $resp = $stmt->execute();
         $response = array("success" => true, "message" => "Proceso realizado correctamente");
      } catch (PDOException $e) {
         $result = htmlspecialchars($e->getMessage());
         $response = array("success" => false, "message" => $result);
      }
      $stmt = null;
      $connection = null;
      return $response;
   }
}