<?php
if (!defined('CONFIG_PATH')) {
   // require_once "../config/config.php";
   define("CONFIG_PATH", "../config");
}
if (!isset($_SESSION)) {
	session_start();
}
if (isset($_SESSION['reportPath'])) {
   require_once $_SESSION['reportPath'].CONFIG_PATH . "/Database.php";
} else {
   require_once CONFIG_PATH . "/Database.php";
}
// $lang = Language::getInstance("../app/languages/");
// $lang->loadTranslations();

class ConsignaModel {


   //**************************************************************************************
   static public function getPorConsig() {
      $fecha = date("Y-m-d");
      $fecha = '2025-10-18';
      $connection = Database::getConnection();
      $sql = "SELECT a.*, c.TerNombr, l.codigo, l.nombre AS banco_nombre, k.sucursal AS banco_sucursal, 
         k.numero_cuenta AS banco_num_cuenta, 
         COALESCE((SELECT MAX(n.fecha) FROM DvAplaza n WHERE a.id_empresa = n.id_empresa AND a.id_cheque = n.id_cheque), a.vencimiento) as UltVenci 
         FROM DvCheque a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa
         LEFT JOIN CoTercer  c ON b.EmpCodig = c.EmpCodig AND a.TerDocId = c.TerDocId 
         LEFT JOIN DvBanCli  k ON a.id_empresa = k.id_empresa AND a.id_bancli = k.id_bancli 
         LEFT JOIN DvBancos  l ON k.id_empresa = l.id_empresa AND k.id_banco = l.id_banco 
         WHERE a.id_empresa = :id_empresa AND clase = '1' AND (a.status = '1' OR a.status = 'D') AND COALESCE(
         (SELECT MAX(n.fecha) FROM DvAplaza n 
            WHERE a.id_empresa = n.id_empresa AND a.id_cheque = n.id_cheque), a.vencimiento) <= :vencimiento";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":vencimiento", $fecha);
      $stmt->execute();
      $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //**************************************************************************************
   static public function getLastConsigna() {
      $connection = Database::getConnection();
      $sql = "SELECT * FROM DvConsig 
         WHERE consecutivo = (SELECT MAX(consecutivo) FROM DvConsig 
         WHERE id_empresa = :idEmpresa)
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


   //**************************************************************************************
   static public function getConsignacion($data) {
      $connection = Database::getConnection();
      $sql = "SELECT a.id_empresa, a.id_consigna, a.consecutivo, a.id_cheque, a.BanCodig, d.BanNombr, a.fecha, 
         a.status, a.ConConta, a.id_user, a.creado_el, a.actualizado_el, c.numero, c.consecutivo AS consecutivo_cheque, 
         c.valor_cheque, c.comision, l.codigo AS banco_codigo, e.TerDocId, f.TerNombr, 
         COALESCE((SELECT MAX(n.fecha) FROM DvAplaza n WHERE a.id_empresa = n.id_empresa AND c.id_cheque = n.id_cheque), c.vencimiento) as UltVenci 
         FROM DvConsig a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa 
         LEFT JOIN DvCheque  c ON a.id_empresa = c.id_empresa AND a.id_cheque = c.id_cheque 
         LEFT JOIN BaCuenta  d ON b.EmpCodig = d.EmpCodig AND a.BanCodig = d.BanCodig 
         LEFT JOIN DvClient  e ON a.id_empresa = e.id_empresa AND c.id_dvcliente = e.id_dvcliente 
         LEFT JOIN DvBanCli  k ON c.id_empresa = k.id_empresa AND c.id_bancli = k.id_bancli 
         LEFT JOIN DvBancos  l ON k.id_empresa = l.id_empresa AND k.id_banco = l.id_banco 
         LEFT JOIN CoTercer  f ON b.EmpCodig = f.EmpCodig AND e.TerDocId = f.TerDocId 
         WHERE a.id_empresa = :idEmpresa AND a.id_consigna = :id_consigna AND a.status = '1'
      ";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(":idEmpresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id_consigna", $data["idConsigna"], PDO::PARAM_INT);
      $stmt->execute();
      $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $connection = null;
      $stmt = null;
      return $response;
   }


}