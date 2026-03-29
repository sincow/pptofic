<?php
//require_once "../models/connection.php";
require_once '../models/general_model.php';

$modulo = $_POST["modulo"] ?? '';
$submodulo = $_POST["option"] ?? '';
$accion = $_POST["action"] ?? '';
//$data = $_POST["data"];
//var_dump($_POST["data"]);
$rutaCtr = "../controllers/$modulo/ctr".$submodulo.".php";
$rutaMdl = "../models/$modulo/mdl".$submodulo.".php";
if (!file_exists($rutaCtr)) exit(json_encode(["success" => false, "message" => "Invalid path"]));
require_once $rutaCtr;
require_once $rutaMdl;
$clase = "ctr".$submodulo;
$clase = ucfirst($submodulo)."Controller";
$instancia = new $clase();
if (!method_exists($instancia, $accion)) exit(json_encode(["success" => false, "message" => "Action not found"]));
echo json_encode($instancia->$accion($_POST));