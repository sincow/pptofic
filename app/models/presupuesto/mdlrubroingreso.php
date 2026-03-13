<?php
if (!defined('CONFIG_PATH')) {
   // require_once "../config/config.php";
   define("CONFIG_PATH", "../config");
}
require_once CONFIG_PATH . "/Database.php";
if (!isset($_SESSION)) {
	session_start();
}

class RubroIngresoModel {

   /**********************************************************************/
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT a.RubroIngresoId, a.Nombre, a.TipoFinanciacionId, a.CtaPucIdxRecon, a.CtaPucIdxCob, a.UsuarioId, 
         a.FechaCreacion, a.FechaModificacion, a.Estado, ifnull(b.Nombre, 'N/A') as TipoFinanciacionNombre, a.Movimiento
         FROM poRubroIngreso a
               left join poTipoFinanciacion b on a.EmpresaId = b.EmpresaId AND a.TipoFinanciacionId = b.TipoFinanciacionId
         WHERE a.EmpresaId = :id_empresa
         ORDER BY a.Nombre"
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
      $status = "1";
      $where = "a.EmpresaId = :id_empresa AND ";
      foreach ($listWhere as $key => $value) {
         $where .= $value["id"] . " = :" . $value["id"] . " AND ";
      }
      if ($where != "") {
         $where = substr($where, 0, -4);
         $where = " AND " . $where;
      }
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.RubroIngresoId, a.Nombre, a.TipoFinanciacionId, a.CtaPucIdxRecon, a.CtaPucIdxCob, a.UsuarioId, 
         a.FechaCreacion, a.FechaModificacion, a.Estado  
         FROM poRubroIngreso a 
         WHERE 1 = 1 " . $where . "
         ORDER BY a.nombre"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      foreach ($listWhere as $key => $value) {
         $stmt->bindParam(":" . $value["id"], $value["value"]);
      }
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   /**********************************************************************/
   static public function getOne($id) {
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.RubroIngresoId, a.Nombre, a.TipoFinanciacionId, a.CtaPucIdxRecon, a.CtaPucIdxCob, a.UsuarioId, 
         a.FechaCreacion, a.FechaModificacion, a.Estado
         FROM poRubroIngreso a 
         WHERE a.EmpresaId = :id_empresa AND a.RubroIngresoId = :id"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id", $id, PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }

public static function mdlValidarCodigo($codigo)
    {
        $niveles = [1, 2, 4, 6, 9, 12, 14, 16, 18, 20];
        $longitudCodigo = strlen(trim($codigo));

        $posNiv = 0;
        $indiceNivel = -1;

        foreach ($niveles as $index => $nivel) {
            if ($longitudCodigo === $nivel) {
                $posNiv = $nivel;
                $indiceNivel = $index;
                break;
            }
        }

        if ($posNiv === 0) {
            return [
                'success' => false,
                'message' => 'Error, código no corresponde a la estructura de niveles. Codigo: ' . $codigo
            ];
        }

      $connection = Database::getConnection();
         // Validar duplicado
        $sql = "SELECT RubroIngresoId, Movimiento
                FROM poRubroIngreso
                WHERE RubroIngresoId = :codigo
                LIMIT 1";

        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $stmt->execute();

        $existe = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existe) {
            return [
                'success' => false,
                'message' => 'Error, el código ya existe.'
            ];
        }

        // Si es nivel 1, no tiene dependencia
        if ($posNiv === $niveles[0]) {
            return [
                'success' => true,
                'message' => '',
                'dependencia' => ''
            ];
        }

        $nivelAnterior = $niveles[$indiceNivel - 1];
        $dependencia = substr($codigo, 0, $nivelAnterior);

        $sqlPadre = "SELECT RubroIngresoId, Movimiento
                     FROM poRubroIngreso
                     WHERE RubroIngresoId = :dependencia
                     LIMIT 1";

        $stmtPadre = $connection->prepare($sqlPadre);
        $stmtPadre->bindParam(':dependencia', $dependencia, PDO::PARAM_STR);
        $stmtPadre->execute();

        $padre = $stmtPadre->fetch(PDO::FETCH_ASSOC);

        if (!$padre) {
            return [
                'success' => false,
                'message' => "Error, el código {$dependencia} no ha sido creado."
            ];
        }

        if ((int)$padre['Movimiento'] === 1) {
            return [
                'success' => false,
                'message' => 'Error, existe un código auxiliar en nivel superior.'
            ];
        }

        return [
            'success' => true,
            'message' => '',
            'dependencia' => $dependencia
        ];
    }

}