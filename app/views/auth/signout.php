<?php
if(!isset($_SESSION))
{
	session_start();
}
$tabla = "users";
$item1 = "UsuSesio";
$valor1 = "0";
$item2 = "user_id";
$valor2 = $_SESSION["user_id"];
// $ultimoLogin = User::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2);
$tabla = "users";
$item1 = "ip_login_usuario";
$valor1 = "";
$item2 = "user_id";
$valor2 = $_SESSION["user_id"];
// $ultimoLogin = UserModel::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2);

//$url = SITE_URL;
$url = 'localhost/vetclinic/';
$url = '../../../';
session_destroy();
unset($_SESSION["user_id"]);
unset($_SESSION["user_name"]);
unset($_SESSION["user_email"]);
unset($_SESSION["profile"]);
unset($_SESSION["photo"]);
unset($_SESSION["login"]);
unset($_SESSION["id_empresa"]);

unset($_SESSION['companyname']);
unset($_SESSION["empdb"]);
unset($_SESSION["empser"]);
unset($_SESSION["empusu"]);
unset($_SESSION["empcla"]);
unset($_SESSION['companyname']);
unset($_SESSION['companyid']);
unset($_SESSION['companylogo']);
unset($_SESSION['companyaddress']);
unset($_SESSION['companycity']);
unset($_SESSION['companyphone']);
unset($_SESSION['companyemail']);
unset($_SESSION['csrf_token']);
unset($_SESSION['csrf_token_time']);

session_unset();
//header('Location: '.$url);

echo '<script>
window.location = "' . $url . '";
</script>';
