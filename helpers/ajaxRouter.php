<?php
// require_once "../config/languages.php";
// require_once "../app/core/Language.php";
require_once '../app/controllers/admon/ctrgeneral.php';
require_once '../app/models/admon/mdlgeneral.php';

$modulo = $_POST["modulo"] ?? '';
$submodulo = $_POST["option"] ?? '';
$accion = $_POST["action"] ?? '';
//$data = $_POST["data"];
$rutaCtr = "../app/controllers/$modulo/ctr".$submodulo.".php";
//var_dump($rutaCtr);
$rutaMdl = "../app/models/$modulo/mdl".$submodulo.".php";
if (!file_exists($rutaCtr)) exit(json_encode(["success" => false, "message" => "Invalid path"]));
require_once $rutaCtr;
require_once $rutaMdl;
$clase = "ctr".$submodulo;
$clase = ucfirst($submodulo)."Controller";
$instancia = new $clase();
if (!method_exists($instancia, $accion)) exit(json_encode(["success" => false, "message" => "Action not found"]));
echo json_encode($instancia->$accion($_POST));