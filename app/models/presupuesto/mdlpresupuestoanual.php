<?php
if (!defined('CONFIG_PATH')) {
   // require_once "../config/config.php";
   //define("CONFIG_PATH", "../config");
    define('CONFIG_PATH', dirname(__DIR__, 3) . '/config');
}
require_once CONFIG_PATH . "/Database.php";
require_once __DIR__ . "/../presupuesto/mdlpresupuestoanual.php";

if (!isset($_SESSION)) {
	session_start();
}
// $lang = Language::getInstance("../app/languages/");
// $lang->loadTranslations();

class PresupuestoAnualModel {


   // /**********************************************************************/
   // static public function getAll($conn = null) {
   //    if ($conn == null) {
	// 		$connection = Database::getConnection();
	// 	}
   //    $stmt = $connection->prepare("SELECT OrigIngresoId, Nombre, Estado, UsuarioId, 
   //       FechaCreacion, FechaModificacion
   //       FROM poOrigIngreso
   //       WHERE EmpresaId = :id_empresa
   //       ORDER BY Nombre"
   //    );
   //    $stmt->bindParam(":id_empresa", $_SESSION["empdef"], PDO::PARAM_INT);
   //    $stmt->execute();
   //    $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
   //    $stmt = null;
   //    $connection = null;
   //    return $resp;
   // }


   /**********************************************************************/
   static public function getSaldo($data) {
      $listWhere = json_decode($data["listWhere"], true);
      $status = "1";
      $where = "EmpresaId = :id_empresa AND ";
      foreach ($listWhere as $key => $value) {
         $where .= $value["id"] . " = :" . $value["id"] . " AND ";
      }
      if ($where != "") {
         $where = substr($where, 0, -4);
         $where = " AND " . $where;
      }
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT (Valor+ PptAdi01- PptRet01+ PptCre01- PptCCr01 +PptAdi02- PptRet02+ PptCre02- PptCCr02+ PptAdi03- PptRet03+
               PptCre03 - PptCCr03 + PptAdi04 - PptRet04 + PptCre04 - PptCCr04 + PptAdi05 - PptRet05 + PptCre05 - PptCCr05 +
               PptAdi06 - PptRet06 + PptCre06 - PptCCr06 + PptAdi07 - PptRet07 + PptCre07 - PptCCr07 + PptAdi08 - PptRet08 + PptCre08 - PptCCr08+
               PptAdi09 - PptRet09 + PptCre09 - PptCCr09 + PptAdi10 - PptRet10 + PptCre10 - PptCCr10 + PptAdi11 - PptRet11 + PptCre11 - PptCCr11+
               PptAdi12 - PptRet12 + PptCre12 - PptCCr12) as AprValor   
         FROM PoPresupuestoAnual  
         WHERE 1 = 1 " . $where  
         
      );
      // var_dump($stmt->queryString);
      // var_dump($listWhere);
      $stmt->bindParam(":id_empresa", $_SESSION["empdef"], PDO::PARAM_INT);
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
   // static public function getOne($id) {
   //    $connection = Database::getConnection();
   //    $stmt = $connection->prepare("SELECT a.OrigIngresoId, a.Nombre, a.Estado, a.UsuarioId, 
   //       a.FechaCreacion, a.FechaModificacion
   //       FROM poOrigIngreso a 
   //       WHERE a.EmpresaId = :id_empresa AND OrigIngresoId = :id"
   //    );
   //    $stmt->bindParam(":id_empresa", $_SESSION["empdef"], PDO::PARAM_INT);
   //    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
   //    $stmt->execute();
   //    $resp = $stmt->fetch(PDO::FETCH_ASSOC);
   //    $stmt = null;
   //    $connection = null;
   //    return $resp;
   // }

      /**********************************************************************/
   // static public function asegurarRegistro($connection, $empresa, $anoMov, $rubCod, $tfiCod) {
   //    $stmt = $connection->prepare("
   //       SELECT COUNT(*) AS total
   //       FROM PoPresupuestoAnual
   //       WHERE EmpresaId = :empresa
   //         AND PeriodoFiscal = :anoMov
   //         AND RubCodig = :rubCod
   //         AND TipoFinanciacionId = :tfiCod
   //    ");

   //    $stmt->execute([
   //       ":empresa" => $empresa,
   //       ":anoMov" => $anoMov,
   //       ":rubCod" => $rubCod,
   //       ":tfiCod" => $tfiCod
   //    ]);

   //    $row = $stmt->fetch(PDO::FETCH_ASSOC);

   //    if ((int)$row["total"] === 0) {
   //       $stmtInsert = $connection->prepare("
   //          INSERT INTO PoPresupuestoAnual (
   //             EmpresaId,
   //             PeriodoFiscal,
   //             RubCodig,
   //             TipoFinanciacionId,
   //             Valor
   //          ) VALUES (
   //             :empresa,
   //             :anoMov,
   //             :rubCod,
   //             :tfiCod,
   //             0
   //          )
   //       ");

   //       $stmtInsert->execute([
   //          ":empresa" => $empresa,
   //          ":anoMov" => $anoMov,
   //          ":rubCod" => $rubCod,
   //          ":tfiCod" => $tfiCod
   //       ]);
   //    }

   //    return true;
   // }

 
   // static public function actualizarMovimiento($connection, $empresa, $anoMov, $mesMov, $rubCod, $tfiCod, $aprTip, $rubVal) {
   //    $mesMov = str_pad((string)((int)$mesMov), 2, "0", STR_PAD_LEFT);

   //    self::asegurarRegistro($connection, $empresa, $anoMov, $rubCod, $tfiCod);

   //    switch ($aprTip) {
   //       case '1':
   //          $campo = "Valor";
   //          break;

   //       case '2':
   //          $campo = "PptAdi" . $mesMov;
   //          break;

   //       case '3':
   //          $campo = "PptRet" . $mesMov;
   //          break;

   //       case '4':
   //          $campo = "PptCre" . $mesMov;
   //          break;

   //       case '5':
   //          $campo = "PptCCr" . $mesMov;
   //          break;

   //       case '6':
   //          $campo = "PptApl" . $mesMov;
   //          break;

   //       case '7':
   //          $campo = "PptDAp" . $mesMov;
   //          break;

   //       case '8':
   //          $campo = "PptEj1" . $mesMov;
   //          break;

   //       case '9':
   //          $campo = "PptEj2" . $mesMov;
   //          break;

   //       case 'A':
   //          $campo = "PptEj3" . $mesMov;
   //          break;

   //       case 'B':
   //          $campo = "PptEj4" . $mesMov;
   //          break;

   //       default:
   //          throw new Exception("Tipo de movimiento no soportado: " . $aprTip);
   //    }

   //    $sql = "
   //       UPDATE PoPresupuestoAnual
   //       SET {$campo} = {$campo} + :rubVal
   //       WHERE EmpresaId = :empresa
   //         AND PeriodoFiscal = :anoMov
   //         AND RubCodig = :rubCod
   //         AND TipoFinanciacionId = :tfiCod
   //    ";

   //    $stmt = $connection->prepare($sql);
   //    $stmt->execute([
   //       ":rubVal" => $rubVal,
   //       ":empresa" => $empresa,
   //       ":anoMov" => $anoMov,
   //       ":rubCod" => $rubCod,
   //       ":tfiCod" => $tfiCod
   //    ]);

   //    return true;
   // }



   /**********************************************************************/
   static public function asegurarRegistro($connection, $empresa, $anoMov, $rubCod, $tfiCod) {
      $stmt = $connection->prepare("
         SELECT COUNT(*) AS total
         FROM PoPresupuestoAnual
         WHERE EmpresaId = :empresa
           AND PeriodoFiscal = :anoMov
           AND RubCodig = :rubCod
           AND TipoFinanciacionId = :tfiCod
      ");

      $stmt->execute([
         ":empresa" => $empresa,
         ":anoMov" => $anoMov,
         ":rubCod" => $rubCod,
         ":tfiCod" => $tfiCod
      ]);

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if ((int)$row["total"] === 0) {
         $stmtInsert = $connection->prepare("
            INSERT INTO PoPresupuestoAnual (
               EmpresaId,
               PeriodoFiscal,
               RubCodig,
               TipoFinanciacionId,
               Valor
            ) VALUES (
               :empresa,
               :anoMov,
               :rubCod,
               :tfiCod,
               0
            )
         ");

         $stmtInsert->execute([
            ":empresa" => $empresa,
            ":anoMov" => $anoMov,
            ":rubCod" => $rubCod,
            ":tfiCod" => $tfiCod
         ]);
      }

      return true;
   }


   /**********************************************************************/
   static public function asegurarRegistroPadre($connection, $empresa, $anoMov, $rubCod) {
      $stmt = $connection->prepare("
         SELECT COUNT(*) AS total
         FROM PoPresupuestoAnual
         WHERE EmpresaId = :empresa
           AND PeriodoFiscal = :anoMov
           AND RubCodig = :rubCod
           AND (TipoFinanciacionId IS NULL OR TipoFinanciacionId = '')
      ");

      $stmt->execute([
         ":empresa" => $empresa,
         ":anoMov" => $anoMov,
         ":rubCod" => $rubCod
      ]);

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if ((int)$row["total"] === 0) {
         $stmtInsert = $connection->prepare("
            INSERT INTO PoPresupuestoAnual (
               EmpresaId,
               PeriodoFiscal,
               RubCodig,
               TipoFinanciacionId,
               Valor
            ) VALUES (
               :empresa,
               :anoMov,
               :rubCod,
               '',
               0
            )
         ");

         $stmtInsert->execute([
            ":empresa" => $empresa,
            ":anoMov" => $anoMov,
            ":rubCod" => $rubCod
         ]);
      }

      return true;
   }


   /**********************************************************************/
   static public function actualizarMovimiento($connection, $empresa, $anoMov, $mesMov, $rubCod, $tfiCod, $aprTip, $rubVal) {
      $mesMov = str_pad((string)((int)$mesMov), 2, "0", STR_PAD_LEFT);
      $campo = self::resolverCampoMovimiento($mesMov, $aprTip);

      // 1. Actualiza el rubro de movimiento con tipo de financiación
      self::asegurarRegistro($connection, $empresa, $anoMov, $rubCod, $tfiCod);
      self::actualizarCampoMovimiento(
         $connection,
         $campo,
         $empresa,
         $anoMov,
         $rubCod,
         $rubVal,
         $tfiCod
      );

      // 2. Actualiza rubros padre sin tipo de financiación
      $depCue = self::obtenerRubroPadreGasto($connection, $empresa, $rubCod);
      $niveles = 0;

      while (!empty($depCue) && $niveles < 20) {
         self::asegurarRegistroPadre($connection, $empresa, $anoMov, $depCue);
         self::actualizarCampoMovimientoPadre(
            $connection,
            $campo,
            $empresa,
            $anoMov,
            $depCue,
            $rubVal
         );

         $depCue = self::obtenerRubroPadreGasto($connection, $empresa, $depCue);
         $niveles++;
      }

      return true;
   }


   /**********************************************************************/
   private static function resolverCampoMovimiento($mesMov, $aprTip) {
      switch ($aprTip) {
         case '1':
            return "Valor";

         case '2':
            return "PptAdi" . $mesMov;

         case '3':
            return "PptRet" . $mesMov;

         case '4':
            return "PptCre" . $mesMov;

         case '5':
            return "PptCCr" . $mesMov;

         case '6':
            return "PptApl" . $mesMov;

         case '7':
            return "PptDAp" . $mesMov;

         case '8':
            return "PptEj1" . $mesMov;

         case '9':
            return "PptEj2" . $mesMov;

         case 'A':
            return "PptEj3" . $mesMov;

         case 'B':
            return "PptEj4" . $mesMov;

         default:
            throw new Exception("Tipo de movimiento no soportado: " . $aprTip);
      }
   }


   /**********************************************************************/
   private static function actualizarCampoMovimiento($connection, $campo, $empresa, $anoMov, $rubCod, $rubVal, $tfiCod) {
      $sql = "
         UPDATE PoPresupuestoAnual
         SET {$campo} = {$campo} + :rubVal
         WHERE EmpresaId = :empresa
           AND PeriodoFiscal = :anoMov
           AND RubCodig = :rubCod
           AND TipoFinanciacionId = :tfiCod
      ";

      $stmt = $connection->prepare($sql);
      $stmt->execute([
         ":rubVal" => $rubVal,
         ":empresa" => $empresa,
         ":anoMov" => $anoMov,
         ":rubCod" => $rubCod,
         ":tfiCod" => $tfiCod
      ]);

      return true;
   }


   /**********************************************************************/
   private static function actualizarCampoMovimientoPadre($connection, $campo, $empresa, $anoMov, $rubCod, $rubVal) {
      $sql = "
         UPDATE PoPresupuestoAnual
         SET {$campo} = {$campo} + :rubVal
         WHERE EmpresaId = :empresa
           AND PeriodoFiscal = :anoMov
           AND RubCodig = :rubCod
           AND (TipoFinanciacionId IS NULL OR TipoFinanciacionId = '')
      ";

      $stmt = $connection->prepare($sql);
      $stmt->execute([
         ":rubVal" => $rubVal,
         ":empresa" => $empresa,
         ":anoMov" => $anoMov,
         ":rubCod" => $rubCod
      ]);

      return true;
   }


   /**********************************************************************/
   private static function obtenerRubroPadreGasto($connection, $empresa, $rubCod) {
      $stmt = $connection->prepare("
         SELECT RubroDependiente
         FROM PoRubroGasto
         WHERE EmpresaId = :empresa
           AND RubroGastoId = :rubCod
         LIMIT 1
      ");

      $stmt->execute([
         ":empresa" => $empresa,
         ":rubCod" => $rubCod
      ]);

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$row) {
         return '';
      }

      return trim((string)($row["RubroDependiente"] ?? ''));
   }

   /**********************************************************************/
static public function getSaldoDisponible($connection, $empresa, $anoMov, $rubCod, $tfiCod) {

   $sql = "
      SELECT 
         (
            IFNULL(Valor,0)
            + IFNULL(PptAdi01,0) - IFNULL(PptRet01,0) + IFNULL(PptCre01,0) - IFNULL(PptCCr01,0)
            + IFNULL(PptAdi02,0) - IFNULL(PptRet02,0) + IFNULL(PptCre02,0) - IFNULL(PptCCr02,0)
            + IFNULL(PptAdi03,0) - IFNULL(PptRet03,0) + IFNULL(PptCre03,0) - IFNULL(PptCCr03,0)
            + IFNULL(PptAdi04,0) - IFNULL(PptRet04,0) + IFNULL(PptCre04,0) - IFNULL(PptCCr04,0)
            + IFNULL(PptAdi05,0) - IFNULL(PptRet05,0) + IFNULL(PptCre05,0) - IFNULL(PptCCr05,0)
            + IFNULL(PptAdi06,0) - IFNULL(PptRet06,0) + IFNULL(PptCre06,0) - IFNULL(PptCCr06,0)
            + IFNULL(PptAdi07,0) - IFNULL(PptRet07,0) + IFNULL(PptCre07,0) - IFNULL(PptCCr07,0)
            + IFNULL(PptAdi08,0) - IFNULL(PptRet08,0) + IFNULL(PptCre08,0) - IFNULL(PptCCr08,0)
            + IFNULL(PptAdi09,0) - IFNULL(PptRet09,0) + IFNULL(PptCre09,0) - IFNULL(PptCCr09,0)
            + IFNULL(PptAdi10,0) - IFNULL(PptRet10,0) + IFNULL(PptCre10,0) - IFNULL(PptCCr10,0)
            + IFNULL(PptAdi11,0) - IFNULL(PptRet11,0) + IFNULL(PptCre11,0) - IFNULL(PptCCr11,0)
            + IFNULL(PptAdi12,0) - IFNULL(PptRet12,0) + IFNULL(PptCre12,0) - IFNULL(PptCCr12,0)
         ) 
         -
         (
            IFNULL(PptEj101,0) + IFNULL(PptEj102,0) + IFNULL(PptEj103,0) + IFNULL(PptEj104,0) +
            IFNULL(PptEj105,0) + IFNULL(PptEj106,0) + IFNULL(PptEj107,0) + IFNULL(PptEj108,0) +
            IFNULL(PptEj109,0) + IFNULL(PptEj110,0) + IFNULL(PptEj111,0) + IFNULL(PptEj112,0)
         )
         AS SaldoDisponible
      FROM PoPresupuestoAnual
      WHERE EmpresaId = :empresa
        AND PeriodoFiscal = :anoMov
        AND RubCodig = :rubCod
        AND TipoFinanciacionId = :tfiCod
      LIMIT 1
   ";

   $stmt = $connection->prepare($sql);
   $stmt->execute([
      ":empresa" => $empresa,
      ":anoMov" => $anoMov,
      ":rubCod" => $rubCod,
      ":tfiCod" => $tfiCod
   ]);

   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   if (!$row) {
      return 0;
   }

   return (float)$row["SaldoDisponible"];
}


}