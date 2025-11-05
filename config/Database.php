<?php
class Database {
   private static ?PDO $connection = null;

   public static function getConnection() {
      if (self::$connection === null) {
         $opciones = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
         );
			if (isset($_SESSION["empdb"])) {
				$dsn = "mysql:host=" . $_SESSION["empser"] . ";dbname=" . $_SESSION["empdb"];
				$userBBDD = $_SESSION["empusu"];
				$passBBDD = $_SESSION["empcla"];
			} else {
				$dsn = DB_HOST;
				$userBBDD = DB_USER;
				$passBBDD = DB_PASS;
			}
         try {
            self::$connection = new PDO(
               $dsn,
               // "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
               $userBBDD,
               $passBBDD,
               $opciones
               // [
               //    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
               //    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
               // ]
            );
            $link = new PDO($dsn, $userBBDD, $passBBDD, $opciones);
            $link->exec("set names utf8");
            return $link;
         } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
         }
      }
      return self::$connection;
   }


   public static function getConnectionSite(): ?PDO {
      // Aquí puedes implementar la lógica para obtener una conexión a otra base de datos si es necesario
      return self::getConnection(); // Por ahora, devuelve la misma conexión
   }
}
