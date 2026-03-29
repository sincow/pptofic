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

class CertDisponibilidadModel {

   static public function getValorUsado($data) {
      $listWhere = json_decode($data["listWhere"], true);
      $where = "c.EmpresaId = :id_empresa AND d.valor > 0  ";
      $params = [];

      foreach ($listWhere as $item) {
      

         $campo = $item["id"]; // ej: c.PeriodoFiscal
         $param = str_replace('.', '_', $campo); // ej: c_PeriodoFiscal

         $where .= " AND $campo = :$param";
         $params[$param] = $item["value"];
      }
      
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT IFNULL(SUM(d.Valor - d.LibValor), 0) AS AprUsado
            FROM PoCertDisp c 
            LEFT JOIN  PoCertDispDet d ON c.EmpresaId = d.EmpresaId AND c.PeriodoFiscal = d.PeriodoFiscal AND c.CertDispId = d.CertDispId
            WHERE " . $where  
         
      );
      $stmt->bindParam(":id_empresa", $_SESSION["empdef"], PDO::PARAM_INT);
      foreach ($params as $param => $valor) {
           $stmt->bindValue(":$param", $valor);
      }
            //       echo "<pre>";
            // echo "PARAMS:\n";
            // print_r($params);
            // echo "</pre>";

      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }
  
   static public function create($data) {
      $connection = null;

      try {
         $required = [
            'fecha',
            'expiracion',
            'peridofiscal',
            'dependencia',
            'ordenadorgasto',
            'tipodocumento',
            'documentonro',
            'concepto'
         ];

         foreach ($required as $field) {
            if (!isset($data[$field]) || trim((string)$data[$field]) === '') {
               return array(
                  "success" => false,
                  "message" => "Falta el campo obligatorio: " . $field
               );
            }
         }

         if (
            !isset($data["detalleCodigo"]) || !is_array($data["detalleCodigo"]) || count($data["detalleCodigo"]) === 0 ||
            !isset($data["detalleTipoFinanciacion"]) || !is_array($data["detalleTipoFinanciacion"]) ||
            !isset($data["detalleSaldo"]) || !is_array($data["detalleSaldo"]) ||
            !isset($data["detalleValor"]) || !is_array($data["detalleValor"])
         ) {
            return array(
               "success" => false,
               "message" => "Debe agregar al menos un detalle válido."
            );
         }

         $empresa = $_SESSION["empdef"];
         $usuario = $_SESSION["user_id"];

         $fecha = trim($data["fecha"]);
         $expiracion = trim($data["expiracion"]);
         $periodo = trim($data["peridofiscal"]);
         $dependencia = trim($data["dependencia"]);
         $ordenador = trim($data["ordenadorgasto"]);
         $tipoDocumento = trim($data["tipodocumento"]);
         $documentoNro = trim($data["documentonro"]);
         $concepto = strtoupper(htmlspecialchars(trim($data["concepto"]), ENT_QUOTES, 'UTF-8'));

         $detalleCodigo = $data["detalleCodigo"];
         $detalleTipoFinanciacion = $data["detalleTipoFinanciacion"];
         $detalleSaldo = $data["detalleSaldo"];
         $detalleValor = $data["detalleValor"];

         $cantidad = count($detalleCodigo);

         if (
            $cantidad !== count($detalleTipoFinanciacion) ||
            $cantidad !== count($detalleSaldo) ||
            $cantidad !== count($detalleValor)
         ) {
            return array(
               "success" => false,
               "message" => "Los datos del detalle están incompletos o desalineados."
            );
         }

         $connection = Database::getConnection();
         $connection->beginTransaction();

         // 1. Buscar consecutivo actual
         $stmt = $connection->prepare("
            SELECT Ni2Tabla
            FROM SinTabla
            WHERE EmpCodig = :empresa
              AND ModCodig = 20
              AND CodTabla = '03'
              AND Ni1Tabla = :periodo
            LIMIT 1
         ");
         $stmt->bindParam(":empresa", $empresa);
         $stmt->bindParam(":periodo", $periodo);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);

         if ($row) {
            $movDoc = str_pad((string)((int)$row["Ni2Tabla"] + 1), 8, "0", STR_PAD_LEFT);

            if (strlen($movDoc) > 8) {
               $movDoc = "00000001";
            }

            $stmtUpd = $connection->prepare("
               UPDATE SinTabla
               SET Ni2Tabla = :movdoc
               WHERE EmpCodig = :empresa
                 AND ModCodig = 20
                 AND CodTabla = '03'
                 AND Ni1Tabla = :periodo
            ");
            $stmtUpd->bindParam(":movdoc", $movDoc);
            $stmtUpd->bindParam(":empresa", $empresa);
            $stmtUpd->bindParam(":periodo", $periodo);
            $stmtUpd->execute();
         } else {
            $movDoc = "00000001";

            $stmtIns = $connection->prepare("
               INSERT INTO SinTabla (EmpCodig, ModCodig, CodTabla, Ni1Tabla, Ni2Tabla)
               VALUES (:empresa, 20, '03', :periodo, :movdoc)
            ");
            $stmtIns->bindParam(":empresa", $empresa);
            $stmtIns->bindParam(":periodo", $periodo);
            $stmtIns->bindParam(":movdoc", $movDoc);
            $stmtIns->execute();
         }

         // 2. Insertar detalle
         $stmtDetalle = $connection->prepare("
            INSERT INTO PoCertDispDet
            (EmpresaId, PeriodoFiscal, CertDispId, RubroGastoId, TipoFinanciacionId, Valor, RubroSaldo)
            VALUES
            (:empresa, :periodo, :cdpNumer, :rgaCodig, :tfiCodig, :cdpValor, :rubSaldo)
         ");

         for ($i = 0; $i < $cantidad; $i++) {
            $rubro = trim($detalleCodigo[$i]);
            $tipoFin = trim($detalleTipoFinanciacion[$i]);
            $saldo = (float)$detalleSaldo[$i];
            $valor = (float)$detalleValor[$i];

            if ($rubro === '' || $tipoFin === '' || $valor <= 0) {
               throw new Exception("Hay registros inválidos en el detalle.");
            }

            $stmtDetalle->bindParam(":empresa", $empresa);
            $stmtDetalle->bindParam(":periodo", $periodo);
            $stmtDetalle->bindParam(":cdpNumer", $movDoc);
            $stmtDetalle->bindParam(":rgaCodig", $rubro);
            $stmtDetalle->bindParam(":tfiCodig", $tipoFin);
            $stmtDetalle->bindParam(":cdpValor", $valor);
            $stmtDetalle->bindParam(":rubSaldo", $saldo);
            $stmtDetalle->execute();


           /// saldo anula 
           $saldoReal = PresupuestoAnualModel::getSaldoDisponible(
               $connection,
               $empresa,
               $periodo,
               $rubro,
               $tipoFin
            );

            if ($valor > $saldoReal) {
               throw new Exception(
                  "El valor ($valor) supera el saldo disponible ($saldoReal) del rubro {$rubro}"
               );
            } 

            $mesMov = date('m', strtotime($fecha));

            PresupuestoAnualModel::actualizarMovimiento($connection, $empresa, $periodo, $mesMov, $rubro, $tipoFin, '8', $valor);

         }

         // 3. Insertar encabezado
         $stmtEnc = $connection->prepare("
            INSERT INTO PoCertDisp
            (EmpresaId, CertDispId, Fecha, Expiracion, PeriodoFiscal, DependenciaId, Concepto, TipoDocumentoId, TipoDocumentoNr, OrdGastoId, UsuarioId)
            VALUES
            (:empresa, :cdpNumer, :cdpFecha, :cdpExpir, :periodo, :depCodig, :cdpConce, :tdoCodig, :tdoNumer, :nitOrGas, :usuario)
         ");

         $stmtEnc->bindParam(":empresa", $empresa);
         $stmtEnc->bindParam(":cdpNumer", $movDoc);
         $stmtEnc->bindParam(":cdpFecha", $fecha);
         $stmtEnc->bindParam(":cdpExpir", $expiracion);
         $stmtEnc->bindParam(":periodo", $periodo);
         $stmtEnc->bindParam(":depCodig", $dependencia);
         $stmtEnc->bindParam(":cdpConce", $concepto);
         $stmtEnc->bindParam(":tdoCodig", $tipoDocumento);
         $stmtEnc->bindParam(":tdoNumer", $documentoNro);
         $stmtEnc->bindParam(":nitOrGas", $ordenador);
         $stmtEnc->bindParam(":usuario", $usuario);
         $stmtEnc->execute();

         $connection->commit();

         return array(
            "success" => true,
            "message" => "Ok, documento grabado. Número de CDP " . $movDoc,
            "cdpNumero" => $movDoc
         );

      } catch (Exception $e) {
         if ($connection && $connection->inTransaction()) {
            $connection->rollBack();
         }

         return array(
            "success" => false,
            "message" => "No fue posible grabar el documento. " . $e->getMessage()
         );
      }
   }

   static public function getCertDisponibilidadReporte($empresa, $periodo, $cdpNumero) {

      $connection = Database::getConnection();

      $stmt = $connection->prepare("
         SELECT 
            a.CertDispId,
            a.Fecha,
            a.PeriodoFiscal,
            a.Concepto,
            d.Nombre AS DependenciaNombre,
            e.Nombre AS TipoDocumentoNombre,
            a.TipoDocumentoNr,
            b.RubroGastoId,
            f.Nombre AS RubroGastoNombre,
            b.Valor
         FROM PoCertDisp a       
               INNER JOIN PoCertDispDet  b ON a.EmpresaId = b.EmpresaId AND a.PeriodoFiscal = b.PeriodoFiscal AND a.CertDispId = b.CertDispId
               LEFT JOIN PoDependencia   d ON a.EmpresaId = d.EmpresaId AND a.DependenciaId = d.DependenciaId
               LEFT JOIN PoTipoDocumento e ON a.EmpresaId = e.EmpresaId AND a.TipoDocumentoId = e.TipoDocumentoId
               LEFT JOIN PoRubroGasto    f ON b.EmpresaId = f.EmpresaId AND b.RubroGastoId = f.RubroGastoId
         WHERE a.EmpresaId = :empresa
               AND a.PeriodoFiscal = :periodo
               AND a.CertDispId = :cdp
      ");

      $stmt->execute([
         ":empresa" => $empresa,
         ":periodo" => $periodo,
         ":cdp" => $cdpNumero
      ]);

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

}



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

