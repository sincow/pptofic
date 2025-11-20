<?php
if (!defined('CONFIG_PATH')) {
   // require_once "../config/config.php";
   define("CONFIG_PATH", "../config");
}
require_once CONFIG_PATH . "/Database.php";
if (!isset($_SESSION)) {
	session_start();
}
// $lang = Language::getInstance("../app/languages/");
// $lang->loadTranslations();

class ClientesModel {

   //******************************************************************************************
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT a.id_dvcliente, a.TerDocId, a.direccion_residencia, a.fecha_nacimiento, 
         a.ciudad_nacimiento, a.referencia_comercial, a.telefono_refcomercial, a.referencia_personal, 
         a.telefono_refpersonal, a.valor_cupo, a.valor_cupotemporal, a.valor_saldo, a.id_actividad, a.tipo_telefono, 
         a.persona_responde, a.origen_recursos, a.nivel_riezgo, a.pep, a.status, a.id_user, a.creado_el, 
         a.actualizado_el, c.TerNomb1, c.TerNomb2, c.TerApel1, c.TerApel2, c.TerRaSoc, c.TerTiDoc, c.TerNombr, c.TerTele1, 
         c.TerTele2, c.TerEmail, c.TerDirec, c.CiuCodig, c.TerGrCon, c.TerAuRet, c.TerRetie, c.TerResAu, c.TerFreAu, 
         c.TerRegim  
         FROM DvClient a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa
         LEFT JOIN CoTercer  c ON b.EmpCodig = c.EmpCodig AND a.TerDocId = c.TerDocId 
         LEFT JOIN GrTablas  d ON c.TerTiDoc = d.TabNive1 AND d.TabCodig = '01'
         WHERE a.id_empresa = :id_empresa
         ORDER BY c.TerNombr"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   //******************************************************************************************
   static public function getWhere($data) {
      $listWhere = json_decode($data["listWhere"], true);
      $status = "1";
      $where = "a.id_empresa = :id_empresa AND ";
      foreach ($listWhere as $key => $value) {
         $where .= $value["id"] . " = :" . $value["id"] . " AND ";
      }
      if ($where != "") {
         $where = substr($where, 0, -4);
         $where = " AND " . $where;
      }
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.id_dvcliente, a.TerDocId, a.direccion_residencia, a.fecha_nacimiento, 
         a.ciudad_nacimiento, a.referencia_comercial, a.telefono_refcomercial, a.referencia_personal, 
         a.telefono_refpersonal, a.valor_cupo, a.valor_cupotemporal, a.valor_saldo, a.id_actividad, a.tipo_telefono, 
         a.persona_responde, a.origen_recursos, a.nivel_riezgo, a.pep, a.status, a.id_user, a.creado_el, 
         a.actualizado_el, c.TerNomb1, c.TerNomb2, c.TerApel1, c.TerApel2, c.TerRaSoc, c.TerTiDoc, c.TerNombr, c.TerTele1, 
         c.TerTele2, c.TerEmail, c.TerDirec, c.CiuCodig, c.TerGrCon, c.TerAuRet, c.TerRetie, c.TerResAu, c.TerFreAu, 
         c.TerRegim  
         FROM DvClient a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa
         LEFT JOIN CoTercer  c ON b.EmpCodig = c.EmpCodig AND a.TerDocId = c.TerDocId 
         LEFT JOIN GrTablas  d ON c.TerTiDoc = d.TabNive1 AND d.TabCodig = '01'
         WHERE 1 = 1 " . $where . "
         ORDER BY c.TerNombr"
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


   //*************************************************************************************************
   static public function searchClient($data) {
      $searchTerm = trim($data["searchTerm"]);
      $connection = Database::getConnection();
      $sql = "SELECT a.id_dvcliente, a.TerDocId, a.direccion_residencia, a.referencia_comercial, a.telefono_refcomercial, 
         a.referencia_personal, a.telefono_refpersonal, a.valor_cupo, a.valor_cupotemporal, a.id_actividad, 
         a.origen_recursos, a.nivel_riezgo, a.pep, a.status, a.id_user, a.creado_el, a.actualizado_el, e.TerNombr, 
         e.TerEmail, e.TerTele1 
         FROM DvClient a 
         LEFT JOIN companies d ON a.id_empresa = d.id_empresa
         LEFT JOIN CoTercer  e ON d.EmpCodig = e.EmpCodig AND a.TerDocId = e.TerDocId 
         WHERE (e.TerNombr LIKE :search1 OR e.TerDocId LIKE :search2 OR e.TerEmail LIKE :search3) AND 
         a.id_empresa = :id_empresa AND a.status = '1' 
         ORDER BY e.TerNombr";
      $stmt = $connection->prepare($sql);
      for ($i = 1; $i <= 3; $i++) {
         $stmt->bindValue(":search$i", "%$searchTerm%", PDO::PARAM_STR);
      }
      $stmt->bindValue(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   //******************************************************************************************
   static public function getOne($id) {
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.id_dvcliente, a.TerDocId, a.direccion_residencia, a.fecha_nacimiento, 
         a.ciudad_nacimiento, a.referencia_comercial, a.telefono_refcomercial, a.referencia_personal, 
         a.telefono_refpersonal, a.valor_cupo, a.valor_cupotemporal, a.valor_saldo, a.id_actividad, a.tipo_telefono, 
         a.persona_responde, a.origen_recursos, a.nivel_riezgo, a.pep, a.status, a.id_user, a.creado_el, 
         a.actualizado_el, c.TerNomb1, c.TerNomb2, c.TerApel1, c.TerApel2, c.TerRaSoc, c.TerTiDoc, c.TerNombr, c.TerTele1, 
         c.TerTele2, c.TerEmail, c.TerDirec, c.CiuCodig, c.TerGrCon, c.TerAuRet, c.TerRetie, c.TerResAu, c.TerFreAu, 
         c.TerRegim  
         FROM DvClient a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa
         LEFT JOIN CoTercer  c ON b.EmpCodig = c.EmpCodig AND a.TerDocId = c.TerDocId 
         LEFT JOIN GrTablas  d ON c.TerTiDoc = d.TabNive1 AND d.TabCodig = '01'
         WHERE a.id_empresa = :id_empresa AND a.id_dvcliente = :id"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id", $id, PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   //*********************************************************************************************
	static public function getByQuery($query, $page){
      $connection = Database::getConnection();
		$results_per_page = 10;
		$offset = ($page -1) * $results_per_page;
		if ($query != '') {
			$queryLike = "%$query%";
			$stmt = $connection->prepare("SELECT a.TerDocId, c.TerNombr
				FROM 	DvClient a
            LEFT JOIN companies b ON a.id_empresa = b.id_empresa
            LEFT JOIN CoTercer  c ON b.EmpCodig = c.EmpCodig AND a.TerDocId = c.TerDocId 
				WHERE a.id_empresa = :id_empresa AND (a.TerDocId LIKE :query OR c.TerNombr LIKE :query2) 
				ORDER BY c.TerNombr ASC 
				LIMIT :limite OFFSET :offset"
			);
			$stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
			$stmt->bindParam(":query", $queryLike, PDO::PARAM_STR);
			$stmt->bindParam(":query2", $queryLike, PDO::PARAM_STR);
			$stmt->bindParam(":limite", $results_per_page, PDO::PARAM_INT);
			$stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
			// print_r($stmt);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt = null;
			$stmt_count = $connection->prepare("SELECT COUNT(*) 
				FROM DvClient 
				WHERE id_empresa = :id_empresa AND (TerDocId LIKE :query OR TerNombr LIKE :query2)"
			);
			$stmt_count->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
			$stmt_count->bindParam(":query", $queryLike, PDO::PARAM_STR);
			$stmt_count->bindParam(":query2", $queryLike, PDO::PARAM_STR);
			$stmt_count->execute();
			$total_results = $stmt_count->fetchColumn();
			$more = $total_results > ($page * $results_per_page);
			$stmt_count = null;
			$resp = array(['items' => array_map(function($row) {
				return [
					'id' => $row['TerDocId'], 
					'text' => $row['TerNombr']];
			}, $results), 'pagination' => ['more' => $more]]);
		} else {
			$resp = array(['items' => [
					[
						'id' => '0', 
						'text' => 'Sin Resultados'
					]
				], 'pagination' => ['more' => false]
			]);
		}
		return $resp;
	}


   //******************************************************************************************
   static public function getSaldo($id) {
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.*, d.TerNombr, IFNULL(e.nombre,'') as BanNombr, b.valor_cupo, b.valor_cupotemporal  
         FROM DvCheque a 
         LEFT JOIN DvClient  b ON a.id_empresa = b.id_empresa AND a.id_dvcliente = b.id_dvcliente 
         LEFT JOIN companies c ON b.id_empresa = c.id_empresa
         LEFT JOIN CoTercer  d ON c.EmpCodig = d.EmpCodig AND b.TerDocId = d.TerDocId 
         LEFT JOIN DvBanCli  f ON a.id_empresa = f.id_empresa AND a.id_bancli = f.id_bancli
         LEFT JOIN DvBancos  e ON f.id_empresa = e.id_empresa AND f.id_banco = e.id_banco 
         WHERE a.id_empresa = :id_empresa AND a.id_dvcliente = :id AND a.valor_cheque > a.capital_pagado AND 
         a.status = '1'"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
      $stmt->bindParam(":id", $id, PDO::PARAM_INT);
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }

}