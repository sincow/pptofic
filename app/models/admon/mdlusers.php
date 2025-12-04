<?php
if (!defined('CONFIG_PATH')) {
   define("CONFIG_PATH", "../config");
}
require_once CONFIG_PATH . "/Database.php";
if (!isset($_SESSION)) {
	session_start();
}

class UsersModel {

   //*****************************************************************************************
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT a.id_user, a.id_empresa, a.name, a.email, a.password, 
         a.id_role, a.photo, a.token_recovery, a.token_expiration, a.host_user, a.user_user, 
         a.last_login, a.pass_user, a.status, a.id_user_at, a.created_at, a.updated_at, 
         b.description as role 
         FROM users a 
         LEFT JOIN roles b ON a.id_role = b.id_role 
         WHERE a.id_empresa = :id_empresa AND email <> 'admin@hotmail.com' 
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
   static public function getWhere($data) {
      $listWhere = json_decode($data["listWhere"], true);
      $where = "a.id_empresa = :id_empresa AND ";
      foreach ($listWhere as $key => $value) {
         $where .= $value["id"] . " = :" . $value["id"] . " AND ";
      }
      if ($where != "") {
         $where = substr($where, 0, -4);
         $where = " AND " . $where;
      }
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.* 
         FROM users a 
         WHERE 1 = 1 " . $where . " AND email <> 'admin@hotmail.com'
         ORDER BY a.name"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_STR);
      foreach ($listWhere as $key => $value) {
         $stmt->bindParam(":" . $value["id"], $value["value"]);
      }
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   //*****************************************************************************************
   static public function getOne($id) {
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.id_user, a.id_empresa, a.name, a.email, a.password, 
         a.id_role, a.photo, a.token_recovery, a.token_expiration, a.host_user, a.user_user, 
         a.last_login, a.pass_user a.status, a.id_user_at, a.created_at, a.updated_at, 
         b.description as role 
         FROM users a 
         LEFT JOIN roles b ON a.id_role = b.id_role 
         WHERE a.id_empresa = :id_empresa AND a.id_user = :id_user AND email <> 'admin@hotmail.com' "
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_user", $id, PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   //*****************************************************************************************
   static public function create($data) {
      try {
         $connection = Database::getConnection();
         $stmt = $connection->prepare("INSERT INTO users (id_empresa, name, email, password, 
            id_role, photo, token_recovery, token_expiration, host_user, user_user, pass_user, 
            id_user_at) VALUES 
            (:id_empresa, :name, :email, :password, :id_role, :photo, :token_recovery, :token_expiration, 
            :host_user, :user_user, :pass_user, :id_user_at)"
         );
         $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
         $stmt->bindParam(":name", $data["nombre"], PDO::PARAM_INT);
         $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
         $stmt->bindParam(":password", $data["password"], PDO::PARAM_STR);
         $stmt->bindParam(":id_role", $data["role"], PDO::PARAM_INT);
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


   //*****************************************************************************************
   static public function update($data) {
      try {
         $connection = Database::getConnection();
         $stmt = $connection->prepare("UPDATE users SET name = :name, 
            email = :email, id_role = :id_role, photo = :photo, id_user_at = :id_user_at 
            WHERE id_empresa = :id_empresa AND id_user = :id_user"
         );
         $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
         $stmt->bindParam(":id_user", $data["id"], PDO::PARAM_INT);
         $stmt->bindParam(":name", $data["nombre"], PDO::PARAM_STR);
         $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
         $stmt->bindParam(":id_role", $data["role"], PDO::PARAM_STR);
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


   //*****************************************************************************************
   static public function delete($data) {
      try {
         $connection = Database::getConnection();
         $stmt = $connection->prepare("UPDATE users SET status = :status 
         WHERE id_empresa = :id_empresa AND id_user = :id_user");
         $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
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