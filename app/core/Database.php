<?php
class Database {
    private $connection;
    private $connectionSite;
    
    public function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
        $dsnSite = "mysql:host=" . DB_HOSTSITE . ";dbname=" . DB_NAMESITE . ";charset=utf8";
        
        try {
            $this->connection = new PDO($dsn, DB_USER, DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $this->connectionSite = new PDO($dsnSite, DB_USERSITE, DB_PASSSITE);
            $this->connectionSite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connectionSite->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

         } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }

   }
    
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            error_log("Error en consulta: " . $e->getMessage());
            return false;
        }
    }
    
    public function querySite($sql, $params = []) {
        try {
            $stmt = $this->connectionSite->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            error_log("Error en consulta: " . $e->getMessage());
            return false;
        }
    }



    public function getConnection() {
        return $this->connection;
    }


    public function getConnectionSite() {
        return $this->connectionSite;
    }

}