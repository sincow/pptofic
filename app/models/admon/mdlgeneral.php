<?php
if (!defined('ENVIRONMENT')) {
   $configPath = '../config/config.php';
   if (file_exists($configPath)) {
		require_once $configPath;
   } else {
      define('ENVIRONMENT', 'production');
   }
}

if (!defined('CONFIG_PATH')) {
   // require_once "../config/config.php";
   define("CONFIG_PATH", "../config");
}
require_once CONFIG_PATH . "/Database.php";
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

class GeneralModel {

	/********************** GET ALL ***********************/
	static public function getAll($tabla, $order, $where, $connection = null) {
		$conn = false;
		$resp = "";
		if ($connection == null) {
			$conn = true;
			$connection = Database::getConnection();
		}
		if ($tabla != null) {
			$query = "SELECT * FROM $tabla WHERE $where ORDER BY $order";
			$stmt = $connection->prepare($query);
			// $stmt = $connection->prepare("SELECT * FROM $tabla WHERE $where ORDER BY $order");
			$stmt->execute();
			$resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// }else{
			// 	$stmt = null;
			// 	return $resp;
		}
		// $stmt = null;
		// return $resp;
		// }else{
      if ($conn == true) {
         $connection = null;
      }
		$stmt = null;
		return $resp;
	}


	/********************** GET ALL ***********************/
	static public function getAllCities($tabla, $order, $where, $conn = null) {
		$resp = "";
		if ($conn == null) {
			$conn = Database::getConnection();
		}
		if ($conn != null) {
			if ($tabla != null) {
				$stmt = $conn->prepare("SELECT a.*, b.code_department, b.name_department 
					FROM $tabla a 
					LEFT JOIN departments b ON a.id_department_city = b.id_department 
					WHERE $where ORDER BY $order"
				);
				$stmt->execute();
				$resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
		}
		$stmt = null;
		$conn = null;
		return $resp;
	}


	/********************** GET DB USER **************************/
	static public function getDbUser($ConNumer) {
		$stmtC = Database::getConnectionSite()->prepare("SELECT d.EmpCodig, d.EmpUsuar, d.EmpClave, d.EmpServi
			FROM users d
			WHERE d.ConNumer = :connum AND d.UsuEstad >= 1 LIMIT 1"
		);
		$stmtC->bindParam(":connum", $ConNumer, PDO::PARAM_STR);
		$stmtC->execute();
		$respC = $stmtC->fetch(PDO::FETCH_ASSOC);
		if ($respC == null) {
			$EmpCodig = BD_USERDEFUSU;
			$EmpServi = BD_SERVDEFUSU;
			$EmpUsuar = BD_USERDEFUSU;
			$EmpClave = BD_PASSDEFUSU;
		} else {
			$EmpCodig = $respC["EmpCodig"];
			$EmpServi = 'mysql:host=' . $respC["EmpServi"] . ';dbname=' . $EmpCodig;
			$EmpUsuar = $respC["EmpUsuar"];
			$EmpClave = $respC["EmpClave"];
		}
		$opciones = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
		);
		$conn = new PDO($EmpServi, $EmpUsuar, $EmpClave, $opciones);
		$conn->exec("set names utf8");
		return $conn;
	}


	/*********************** GET ALL Site *********************/
	static public function getAllSite($tabla, $where) {
		$resp = "";
		$conn = Database::getConnectionSite();
		if ($conn != null) {
			if ($tabla != null) {
				$stmt = $conn->prepare("SELECT * FROM $tabla WHERE $where");
				$stmt->execute();
				$resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$stmt = null;
				return $resp;
			}
			$stmt = null;
			return $resp;
		} else {
			$stmt = null;
			return $resp;
		}
	}


	/*********************** ADD ************************/
	public static function save($table, $data, $connection = null) {
		$conn = false;
		$columns = "";
		$params = "";
		try {
			if ($connection == null) {
				$conn = true;
				$connection = Database::getConnection();
			}
			foreach ($data as $key => $value) {
				$columns .= $key . ",";
				$params .= ":" . $key . ",";
			}
			$columns = substr($columns, 0, -1);
			$params = substr($params, 0, -1);
			$sql = "INSERT INTO $table ($columns) VALUES ($params)";
			$stmt = $connection->prepare($sql);
			foreach ($data as $key => $value) {
				$stmt->bindParam(":" . $key, $data[$key]);
			}
			$resp = $stmt->execute();
			$lastId = $connection->lastInsertId();
         $response = array("success" => true, "message" => 'Registro guardado exitosamente', "lastId" => $lastId);

		} catch (PDOException $e) {
			// var_dump($e);
			$errorInfo = GeneralController::handleMySQLerror($stmt->errorInfo()[1], $stmt->errorInfo()[2]);
			if (ENVIRONMENT == 'development') {
				$messageError = $errorInfo["error_code"]." ".$errorInfo["technical_message"];
			} else {
				$messageError = $errorInfo["error_code"]." ".$errorInfo["error_message"];
			}
			$response = array("success" => false, "message" => $messageError, "code" => $errorInfo["error_code"]);

			// if ($stmt->errorInfo()[1] == 1062) {
			// 	$response = array("success" => false, "message" =>"Ya existe un registro con estos datos");
			// } else {
			// 	$response = array("success" => false, "message" => $stmt->errorInfo()[1]." ".$stmt->errorInfo()[2]);
			// }
		}
		// if ($stmt->execute()) {
		// 	$resp = "ok";
		// 	$lastId2 = "";
		// } else {
		// 	$resp = "error";
		// 	$lastId1 = $stmt->errorInfo()[2];
		// 	$lastId2 = $stmt->errorInfo()[1];
      //    $response = array("success" => false, "message" => $stmt->errorInfo()[1]." ".$stmt->errorInfo()[2]);
		// }
		if ($conn == true) {
			$connection = null;
		}
		return $response;
	}


	/*********************** ADD OR UPDATE ************************/
	public static function saveupdate($table, $data, $dataUpdt, $connection = null) {
		$columns = "";
		$params = "";

		$conn = false;
		try {
			if ($connection == null) {
				$conn = true;
				$connection = Database::getConnection();
			}

			foreach ($data as $key => $value) {
				$columns .= $key . ",";
				$params .= ":" . $key . ",";
			}
			$columns = substr($columns, 0, -1);
			$params = substr($params, 0, -1);
			$columnList = array_keys($dataUpdt);
			$listaCampos = '';
			for ($i = 0; $i < count($dataUpdt); $i++) {
				$listaCampos = $listaCampos . array_keys($dataUpdt)[$i] . " = :" . array_keys($dataUpdt)[$i] . "2";
				$listaCampos = $listaCampos . ", ";
			}
			$listaCampos = substr($listaCampos, 0, -2);
			$sql = "INSERT INTO $table ($columns) VALUES ($params) ON DUPLICATE KEY UPDATE $listaCampos ";
			// if ($conn == null) {
			// 	$link = Database::getConnection();
			// } else {
			// 	$link = $conn;
			// }
			$stmt = $connection->prepare($sql);
			foreach ($data as $key => $value) {
				$stmt->bindParam(":" . $key, $data[$key]);
			}
			for ($i = 0; $i < count($dataUpdt); $i++) {
				$stmt->bindParam(":" . array_keys($dataUpdt)[$i] . "2", $dataUpdt[$columnList[$i]]);
			}
			$resp = $stmt->execute();
         $response = array("success" => true, "message" => 'Registro guardado exitosamente');
			// if ($stmt->execute()) {
			// 	$resp1 = 200;
			// 	$resp2 = $conn->lastInsertId();
			// 	$resp3 = "";
			// } else {
			// 	$resp1 = 400;
			// 	$resp2 = $stmt->errorInfo()[1];
			// 	$resp3 = $stmt->errorInfo()[2];
			// }
		} catch (PDOException $e) {
			$errorInfo = GeneralController::handleMySQLerror($stmt->errorInfo()[1], $stmt->errorInfo()[2]);
			if (ENVIRONMENT == 'development') {
				$messageError = $errorInfo["error_code"]." ".$errorInfo["technical_message"];
			} else {
				$messageError = $errorInfo["error_code"]." ".$errorInfo["error_message"];
			}
			$response = array("success" => false, "message" => $messageError);
		}
		if ($conn == true) {
			$connection = null;
		}
		return $response;

		// $resp = array($resp1, $resp2, $resp3);
		// return $resp;
	}


	/********************* UPDATE *************************/
	public static function update($table, $data, $where, $connection = null) {
		$conn = false;
		try {
			if ($connection == null) {
				$conn = true;
				$connection = Database::getConnection();
			}
			$resp = null;
			$columnListString = implode(",", array_keys($data));
			$columnList = array_keys($data);
			$columnWhere = array_keys($where);
			$placeHolders = array();
			$listaCampos = '';
			for ($i = 0; $i < count($data); $i++) {
				$listaCampos = $listaCampos . array_keys($data)[$i] . " = :" . array_keys($data)[$i];
				$listaCampos = $listaCampos . ", ";
			}
			$listaCampos = substr($listaCampos, 0, -2);

			$listaWhere = '';
			for ($i = 0; $i < count($where); $i++) {
				$listaWhere = $listaWhere . array_keys($where)[$i] . " = :" . array_keys($where)[$i];
				if ($i < count($where)) $listaWhere = $listaWhere . " AND ";
			}
			$listaWhere = substr($listaWhere, 0, -5);
			$stmt = $connection->prepare("UPDATE $table SET $listaCampos WHERE $listaWhere");
			for ($i = 0; $i < count($data); $i++) {
				$stmt->bindParam(":" . array_keys($data)[$i], $data[$columnList[$i]]);
			}
			for ($i = 0; $i < count($where); $i++) {
				$stmt->bindParam(":" . array_keys($where)[$i], $where[$columnWhere[$i]]);
			}
			$resp = $stmt->execute();
         $response = array("success" => true, "message" => 'Registro guardado exitosamente');

			// if ($stmt->execute()) {
			// 	$response = array("success" => true, "message" => 'Proceso completado correctamente');
			// 	// $resp1 = "ok";
			// 	// $resp2 = "";
			// } else {
			// 	$response = array("success" => false, "message" => $stmt->errorInfo()[1]." ".$stmt->errorInfo()[2]);
			// 	// $resp1 = $stmt->errorInfo()[1];
			// 	// $resp2 = $stmt->errorInfo()[2];
			// }

		} catch (PDOException $e) {
			var_dump($e);
			$errorInfo = GeneralController::handleMySQLerror($stmt->errorInfo()[1], $stmt->errorInfo()[2]);
			if (ENVIRONMENT == 'development') {
				$messageError = $errorInfo["error_code"]." ".$errorInfo["technical_message"];
			} else {
				$messageError = $errorInfo["error_code"]." ".$errorInfo["error_message"];
			}
			$response = array("success" => false, "message" => $messageError, "code" =>  $errorInfo["error_code"]);
		}
		if ($conn == true) {
			$connection = null;
		}
		return $response;
	}


	/******************** GENERR TOKEN ************************/
	public static function generateToken() {
		$gen = md5(uniqid(mt_rand(), false));
		return $gen;
	}


	/************************** CONVERTIR FECHA LARGA ********************/
	public static function longDate($fecha) {
		$meses = array("Mes Nulo", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		$fecha_array = explode("-", $fecha);
		return $fecha_array[2] . " de " . $meses[$fecha_array[1]] . " del año " . $fecha_array[0];
	}


	/************************ CANCEL **********************/
	public static function cancel($table, $where, $conn) {
		$db = Database::getConnection();
		$stmt = $db->prepare("UPDATE $table SET status_customer = 9 WHERE id_customer = :id");
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		if ($stmt->execute()) {
			$resp = "ok";
		} else {
			$resp = "error";
		}
		$stmt = null;
		$db = null;
		return $resp;
	}


	/************************* DELETE *********************/
	public static function delete($table, $where, $operator, $conn) {
		$listaWhere = '';
		$columnWhere = array_keys($where);
		for ($i = 0; $i < count($where); $i++) {
			// $listaWhere = $listaWhere.array_keys($where)[$i]." = :".array_keys($where)[$i];
			$listaWhere = $listaWhere . array_keys($where)[$i] . $operator . " :" . array_keys($where)[$i];
			if ($i < count($where)) $listaWhere = $listaWhere . " AND ";
		}
		$listaWhere = substr($listaWhere, 0, -5);
		$stmt = $conn->prepare("DELETE FROM $table WHERE $listaWhere");
		for ($i = 0; $i < count($where); $i++) {
			$stmt->bindParam(":" . array_keys($where)[$i], $where[$columnWhere[$i]]);
		}
		if ($stmt->execute()) {
			$resp1 = "ok";
			$resp2 = "";
		} else {
			$resp1 = $stmt->errorInfo()[1];
			$resp2 = $stmt->errorInfo()[2];
		}
		$resp = array($resp1, $resp2);
		return $resp;
	}


	/************************ CANCEL **********************/
	public static function resetAutoIncre($table, $conn) {
		// $db = Connection::getConnection();
		$stmt = $conn->prepare("ALTER TABLE $table AUTO_INCREMENT = 1");
		if ($stmt->execute()) {
			$resp = "ok";
		} else {
			$resp = "error";
		}
		$stmt = null;
		// $db = null;
		return $resp;
	}


	/************************ SETEAR VARIABLES DE SESSION **********************/
	public static function setDefault($data) {
      $result = true;
		$message = "Settings updated";
      switch ($data["origen"]) {
         case 'companyname':
            $_SESSION["client_id"] = $data["setData1"];
            $_SESSION["companyname"] = $data["setData2"];
            break;
         default:
            $result = false;
				$message = "Failed request";
            break;
      }
		return array('success' => $result, 'message' => $message);
	}
}
