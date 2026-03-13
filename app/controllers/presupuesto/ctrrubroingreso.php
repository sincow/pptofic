<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/presupuesto/mdlrubroingreso.php';
}

class RubroIngresoController {

   //*****************************************************************************************************
   static public function index(){
      $rubroingreso = RubroIngresoModel::getAll(null);
      return $rubroingreso;
   }


   //*****************************************************************************************************
   static public function getWhere(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" => 'Método inválido');
         return $response;
      }
      $data = $_POST;
      $rubroingreso = RubroIngresoModel::getWhere($data);
      return $rubroingreso;
   }


   //*****************************************************************************************************
   static public function getOne($id) {
      $rubroingreso = RubroIngresoModel::getOne($id);
      return $rubroingreso;
   }


   //*****************************************************************************************************
   static public function create() {
      $required = ['codigo', 'nombre'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }

      $codigo = trim($_POST['codigo'] ?? '');
      $validacion = RubroingresoModel::mdlValidarCodigo($codigo);
      if (!$validacion['success']) {
         return $validacion;
      }

      $post['rubrodependiente'] = $validacion['dependencia'];
      $data = $_POST;
      $data["nombre"] = trim($data["nombre"]);
      $data["nombre"] = strip_tags($data["nombre"]);
      $data["nombre"] = strtoupper(htmlspecialchars($data["nombre"], ENT_QUOTES, 'UTF-8'));
      $data["tipofinanciacion"] = trim($data["tipofinanciacion"]);
      $data["tipofinanciacion"] = strip_tags($data["tipofinanciacion"]);
      $data["tipofinanciacion"] = strtoupper(htmlspecialchars($data["tipofinanciacion"], ENT_QUOTES, 'UTF-8'));
      $data["ctarecon"] = trim($data["ctarecon"]);
      $data["ctarecon"] = strip_tags($data["ctarecon"]);
      $data["ctarecon"] = strtoupper(htmlspecialchars($data["ctarecon"], ENT_QUOTES, 'UTF-8'));
      $data["ctacob"] = trim($data["ctacob"]);
      $data["ctacob"] = strip_tags($data["ctacob"]);
      $data["ctacob"] = strtoupper(htmlspecialchars($data["ctacob"], ENT_QUOTES, 'UTF-8'));
      $movimiento = isset($_POST["movimiento"]) ? 1 : 0;

      $tabla = "poRubroIngreso";
      $dataUpdt = array(
         "EmpresaId" => $_SESSION["id_empresa"],
         "RubroIngresoId"    => $data["codigo"], 
         "Nombre"    => $data["nombre"], 
         "TipoFinanciacionId" => $data["tipofinanciacion"],
         "CtaPucIdxRecon" => $data["ctarecon"],
         "CtaPucIdxCob" => $data["ctacob"],
         "Movimiento" => $movimiento,
         "RubroDependiente" => $data["rubrodependiente"],
         "UsuarioId"   => $_SESSION["user_id"]
      );

      $response = GeneralModel::save($tabla, $dataUpdt, null);
      return $response;
   }


   //*****************************************************************************************************
   static public function update() {
      $required = ['codigo', 'nombre'];
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }
      $data = $_POST;
      if (empty(trim($data["codigo"])) || !preg_match('/^[0-9]+$/', trim($data["codigo"]))) {
         $response = array("success" => false, "message" => 'Registro inválido: ' . $data["codigo"]);
         return $response;
      }
      $data["id"] = filter_var($data["id"], FILTER_SANITIZE_NUMBER_INT);
      $data["codigo"] = filter_var(trim($data["codigo"]), FILTER_SANITIZE_NUMBER_INT);
      $data["nombre"] = trim($data["nombre"]);
      $data["nombre"] = strip_tags($data["nombre"]);
      $data["nombre"] = strtoupper(htmlspecialchars($data["nombre"], ENT_QUOTES, 'UTF-8'));
      $data["tipofinanciacion"] = trim($data["tipofinanciacion"]);
      $data["tipofinanciacion"] = strip_tags($data["tipofinanciacion"]);
      $data["tipofinanciacion"] = strtoupper(htmlspecialchars($data["tipofinanciacion"], ENT_QUOTES, 'UTF-8'));
      $data["ctarecon"] = trim($data["ctarecon"]);
      $data["ctarecon"] = strip_tags($data["ctarecon"]);
      $data["ctarecon"] = strtoupper(htmlspecialchars($data["ctarecon"], ENT_QUOTES, 'UTF-8'));
      $data["ctacob"] = trim($data["ctacob"]);
      $data["ctacob"] = strip_tags($data["ctacob"]);
      $data["ctacob"] = strtoupper(htmlspecialchars($data["ctacob"], ENT_QUOTES, 'UTF-8'));
      $movimiento = isset($_POST["movimiento"]) ? 1 : 0;
      $tabla = "poRubroIngreso";
      $dataUpdt = array(
         "Nombre"    => $data["nombre"], 
         "TipoFinanciacionId" => $data["tipofinanciacion"],
         "CtaPucIdxRecon" => $data["ctarecon"],
         "CtaPucIdxCob" => $data["ctacob"],
         "Movimiento" => $movimiento,
         "UsuarioId"   => $_SESSION["user_id"]
      );
      $where = array(
         "RubroIngresoId"    => $data["codigo"], 
         "EmpresaId" => $_SESSION["id_empresa"]
      );
      $response = GeneralModel::update($tabla, $dataUpdt, $where, null);
      return $response;
   }


   //*****************************************************************************************************
   static public function delete() {
      $required = ['id', 'status'];
      
      $verification = GeneralController::verifyRequiredFields($required, $_POST);
      if ($verification["success"] == false) {
         return $verification;
      }  
      if (empty($_POST["id"]) || !preg_match('/^[0-9]+$/',trim($_POST["id"]))) {
         $response = array("success" => false, "message" => 'Registro inválido');
         return $response;
      }
      $idRubroIngreso = filter_var(trim($_POST["id"]), FILTER_SANITIZE_NUMBER_INT);
      $tabla = "poRubroIngreso";
      $data =array(
         "Estado" => $_POST["status"]
      );
      $where = array(
         "EmpresaId" => $_SESSION["id_empresa"],
         "RubroIngresoId"   => $idRubroIngreso
      );
      $response = GeneralModel::update($tabla, $data, $where, null);
      return $response;
   }

    public function validarCodigo($post)
    {
        $codigo = isset($post['codigo']) ? trim($post['codigo']) : '';

        if ($codigo === '') {
            return [
                'success' => true,
                'message' => '',
                'rubrodependiente' => ''
            ];
        }

        if (!ctype_digit($codigo)) {
            return [
                'success' => false,
                'message' => 'El código debe contener solo números.'
            ];
        }

        $resultado = RubroingresoModel::mdlValidarCodigo($codigo);

        return $resultado;
    }
}