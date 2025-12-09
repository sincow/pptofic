<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/contabilidad/mdlterceros.php';
}

class TercerosController {

   //*****************************************************************************************************
   static public function index(){
      $especies = TercerosModel::getAll(null);
      return $especies;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $especies = TercerosModel::getWhere($data);
      return $especies;
   }


   //*****************************************************************************************************
   static public function getOne($id) {
      $client = TercerosModel::getOne("clients", "id", $id, null);
      return $client;
   }


   //*****************************************************************************************************
	static public function getByQuery(){
      $query = $_POST["query"];
      $page = $_POST["page"];
      if (strlen($query) < 3 ) {
			$respuesta = array(['items' => [
					[
						'id' => '0', 
						'text' => 'Sin Resultados'
					]
				], 'pagination' => ['more' => false]
			]);
      } else {
         $respuesta = TercerosModel::getByQuery($query, $page);
      }
		return $respuesta;
	}



}