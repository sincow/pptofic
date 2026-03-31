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

class RegisPresupuestalModel {

   
  static public function create($data) {
   $connection = Database::getConnection();

   try {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         return ["success" => false, "message" => "Método inválido"];
      }

      $empresaId = $_SESSION["empdef"];
      $usuarioId = $_SESSION["user_id"];

      $periodoFiscal   = trim($data["periodofiscal"] ?? '');
      $certDispId      = trim($data["nrocdp"] ?? '');
      $fecha           = trim($data["fecha"] ?? '');
      $expiracion      = trim($data["fechaplazo"] ?? '');
      $dependenciaId   = trim($data["dependenciaid"] ?? '');
      $terceroId       = trim($data["tercerocrp"] ?? '');
      $tipoContratoId  = trim($data["tipocontrato"] ?? '');
      $tipoContratoNr  = trim($data["contratonro"] ?? '');
      $concepto        = trim($data["concepto"] ?? '');
      $tipoDocumentoId = trim($data["tipodocumentoid"] ?? '');
      $tipoDocumentoNr = trim($data["documentonro"] ?? '');
      $ordGastoId      = trim($data["ordenadorgastoid"] ?? '');

      $detalleRubro    = $data["rubroGastoId"] ?? [];
      $detalleTfi      = $data["tipoFinanciacionId"] ?? [];
      $detalleRec      = $data["recCodig"] ?? [];
      $detalleOei      = $data["oeiCodig"] ?? [];
      $detalleDre      = $data["dreCodig"] ?? [];
      $detalleFga      = $data["fgaCodig"] ?? [];
      $detalleValor    = $data["valorDetalle"] ?? [];
      $detalleSaldo    = $data["saldoCdp"] ?? [];

      $validacion = self::validarDatos($data);
      if (!$validacion["success"]) {
         return $validacion;
      }

      $fechaSql = self::fechaTextoASql($fecha);
      $expiracionSql = self::fechaTextoASql($expiracion);

      $connection->beginTransaction();

      $registroPresId = self::tomarConsecutivo($connection, $empresaId, $periodoFiscal);

      $conArt = 0;

      for ($i = 0; $i < count($detalleRubro); $i++) {
         $rubroGastoId = trim($detalleRubro[$i] ?? '');
         $tipoFinanciacionId = trim($detalleTfi[$i] ?? '');
         $recCodig = trim($detalleRec[$i] ?? '');
         $oeiCodig = trim($detalleOei[$i] ?? '');
         $dreCodig = trim($detalleDre[$i] ?? '');
         $fgaCodig = trim($detalleFga[$i] ?? '');
         $valor = self::limpiarNumero($detalleValor[$i] ?? 0);
         $saldo = self::limpiarNumero($detalleSaldo[$i] ?? 0);

         if ($valor <= 0) {
            continue;
         }

         if ($valor > $saldo) {
            throw new Exception("El valor del rubro {$rubroGastoId} no puede ser mayor que el saldo disponible.");
         }

         PresupuestoAnualModel::actualizarMovimiento(
            $connection,
            $empresaId,
            $periodoFiscal,
            date('m', strtotime($fechaSql)),
            $rubroGastoId,
            $tipoFinanciacionId,
            '9',
            $valor
         );

         $stmtDet = $connection->prepare("
            INSERT INTO PoRegistroPresDet (
               EmpresaId,
               PeriodoFiscal,
               RegistroPresId,
               RubroGastoId,
               TipoFinanciacionId,
               RecCodig,
               OEICodig,
               DReCodig,
               FGaCodig,
               Valor,
               RubroSaldo,
               LibValor
            ) VALUES (
               :empresa,
               :periodo,
               :registro,
               :rubro,
               :tfi,
               :rec,
               :oei,
               :dre,
               :fga,
               :valor,
               :saldo,
               0
            )
         ");

         $stmtDet->execute([
            ":empresa"  => $empresaId,
            ":periodo"  => $periodoFiscal,
            ":registro" => $registroPresId,
            ":rubro"    => $rubroGastoId,
            ":tfi"      => $tipoFinanciacionId,
            ":rec"      => $recCodig,
            ":oei"      => $oeiCodig,
            ":dre"      => $dreCodig,
            ":fga"      => $fgaCodig,
            ":valor"    => $valor,
            ":saldo"    => $saldo
         ]);

         $conArt++;
      }

      if ($conArt <= 0) {
         throw new Exception("No hay detalle válido para grabar.");
      }

      $stmtEnc = $connection->prepare("
         INSERT INTO PoRegistroPres (
            EmpresaId,
            PeriodoFiscal,
            RegistroPresId,
            CertDispId,
            Fecha,
            Expiracion,
            DependenciaId,
            TerceroId,
            TipoContratoId,
            TipoContratoNr,
            Concepto,
            TipoDocumentoId,
            TipoDocumentoNr,
            OrdGastoId,
            Estado,
            UsuarioId,
            CrpCesio
         ) VALUES (
            :empresa,
            :periodo,
            :registro,
            :cdp,
            :fecha,
            :expiracion,
            :dependencia,
            :tercero,
            :tipocontrato,
            :nrocontrato,
            :concepto,
            :tipodocumento,
            :nrodocumento,
            :ordgasto,
            1,
            :usuario,
            0
         )
      ");

      $stmtEnc->execute([
         ":empresa"       => $empresaId,
         ":periodo"       => $periodoFiscal,
         ":registro"      => $registroPresId,
         ":cdp"           => $certDispId,
         ":fecha"         => $fechaSql,
         ":expiracion"    => $expiracionSql,
         ":dependencia"   => $dependenciaId,
         ":tercero"       => $terceroId,
         ":tipocontrato"  => $tipoContratoId,
         ":nrocontrato"   => $tipoContratoNr,
         ":concepto"      => $concepto,
         ":tipodocumento" => $tipoDocumentoId,
         ":nrodocumento"  => $tipoDocumentoNr,
         ":ordgasto"      => $ordGastoId,
         ":usuario"       => $usuarioId
      ]);

      $connection->commit();

      return [
         "success" => true,
         "message" => "Documento grabado correctamente. RP: " . $registroPresId,
         "registroPresId" => $registroPresId
      ];

   } catch (Exception $e) {
      if ($connection->inTransaction()) {
         $connection->rollBack();
      }

      return [
         "success" => false,
         "message" => $e->getMessage()
      ];
   }
}


static private function validarDatos($data) {
   if (empty(trim($data["fecha"] ?? ''))) {
      return ["success" => false, "message" => "Debe especificar la fecha del Registro Presupuestal"];
   }

   if (empty(trim($data["periodofiscal"] ?? ''))) {
      return ["success" => false, "message" => "Debe especificar el Periodo Fiscal"];
   }

   if (empty(trim($data["tercerocrp"] ?? ''))) {
      return ["success" => false, "message" => "Debe especificar el Tercero"];
   }

   if (empty(trim($data["tipocontrato"] ?? ''))) {
      return ["success" => false, "message" => "Debe especificar el Tipo de Contrato"];
   }

   if (empty(trim($data["contratonro"] ?? ''))) {
      return ["success" => false, "message" => "Debe especificar el Número del Contrato"];
   }

   if (empty(trim($data["concepto"] ?? ''))) {
      return ["success" => false, "message" => "Debe especificar el Concepto del Registro Presupuestal"];
   }

   $detalle = $data["rubroGastoId"] ?? [];
   if (empty($detalle) || count($detalle) === 0) {
      return ["success" => false, "message" => "No ha definido el detalle de la apropiación"];
   }

   return ["success" => true];
}

   
static private function tomarConsecutivo($connection, $empresaId, $periodoFiscal) {
   $stmt = $connection->prepare("
      SELECT Ni2Tabla
      FROM SinTabla
      WHERE CodEmpre = :empresa
        AND ModCodig = 20
        AND CodTabla = '04'
        AND Ni1Tabla = :periodo
      FOR UPDATE
   ");

   $stmt->execute([
      ":empresa" => $empresaId,
      ":periodo" => $periodoFiscal
   ]);

   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   if (!$row) {
      $nuevo = '00000001';

      $stmtInsert = $connection->prepare("
         INSERT INTO SinTabla (
            CodEmpre,
            ModCodig,
            CodTabla,
            Ni1Tabla,
            Ni2Tabla
         ) VALUES (
            :empresa,
            20,
            '04',
            :periodo,
            :numero
         )
      ");

      $stmtInsert->execute([
         ":empresa" => $empresaId,
         ":periodo" => $periodoFiscal,
         ":numero"  => $nuevo
      ]);

      return $nuevo;
   }

   $nuevo = str_pad(((int)$row["Ni2Tabla"]) + 1, 8, '0', STR_PAD_LEFT);

   if (strlen($nuevo) > 8) {
      $nuevo = '00000001';
   }

   $stmtUpdate = $connection->prepare("
      UPDATE SinTabla
      SET Ni2Tabla = :numero
      WHERE CodEmpre = :empresa
        AND ModCodig = 20
        AND CodTabla = '04'
        AND Ni1Tabla = :periodo
   ");

   $stmtUpdate->execute([
      ":numero"  => $nuevo,
      ":empresa" => $empresaId,
      ":periodo" => $periodoFiscal
   ]);

   return $nuevo;
}

static private function limpiarNumero($valor) {
   $valor = str_replace('.', '', (string)$valor);
   $valor = str_replace(',', '', $valor);
   return (float)$valor;
}

static private function fechaTextoASql($fecha) {
   $fecha = trim((string)$fecha);

   if ($fecha === '') {
      return null;
   }

   $partes = explode('/', $fecha);
   if (count($partes) === 3) {
      return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
   }

   return $fecha;
}


}




