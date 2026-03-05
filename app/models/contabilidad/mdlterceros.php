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

class TercerosModel {


   //*********************************************************************************************
   static public function getAll($conn = null) {
      if ($conn == null) {
			$connection = Database::getConnection();
		}
      $stmt = $connection->prepare("SELECT * 
         FROM CoTercer
         WHERE EmpCodig = :id_empresa
         ORDER BY ComDescr"
      );
      $stmt->bindParam(":id_empresa", $_SESSION["empdef"], PDO::PARAM_STR);
      $stmt->execute();
      $resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;
      $connection = null;
      return $resp;
   }


   //*********************************************************************************************
   static public function getWhere($data) {
      $listWhere = json_decode($data["listWhere"], true);


      $where = "a.EmpCodig = :id_empresa AND ";
      foreach ($listWhere as $key => $value) {
         $where .= $value["id"] . " = :" . $value["id"] . " AND ";
      }
      if ($where != "") {
         $where = substr($where, 0, -4);
         $where = " AND " . $where;
      }
      $connection = Database::getConnection();
      $stmt = $connection->prepare("SELECT a.* 
         FROM CoTercer a 
         WHERE 1 = 1 " . $where . "
         ORDER BY a.TerNombr"
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


   //*********************************************************************************************
   static public function getOne($id) {
      $connection = Database::getConnection();
     
      $stmt = $connection->prepare("SELECT a.*
         FROM CoTercer a 
         WHERE a.EmpCodig = :id_empresa AND a.TerDocId = :id"
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
			$stmt = $connection->prepare("SELECT a.TerDocId, a.TerNombr
				FROM 	CoTercer a
				WHERE a.EmpCodig = :empcod AND (a.TerDocId LIKE :query OR a.TerNombr LIKE :query2) 
				ORDER BY a.TerNombr ASC 
				LIMIT :limite OFFSET :offset"
			);
			$stmt->bindParam(":empcod", $_SESSION["empdef"], PDO::PARAM_STR);
			$stmt->bindParam(":query", $queryLike, PDO::PARAM_STR);
			$stmt->bindParam(":query2", $queryLike, PDO::PARAM_STR);
			$stmt->bindParam(":limite", $results_per_page, PDO::PARAM_INT);
			$stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
			// print_r($stmt);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt = null;
			$stmt_count = $connection->prepare("SELECT COUNT(*) 
				FROM CoTercer 
				WHERE EmpCodig = :empcod AND (TerDocId LIKE :query OR TerNombr LIKE :query2)"
			);
			$stmt_count->bindParam(":empcod", $_SESSION["empdef"], PDO::PARAM_STR);
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

}