<?php
if (!isset($_POST["option"])) {
	require_once 'app/models/admon/mdlgeneral.php';
}
class GeneralController {

   //*********************************************************************************************
	static public function getAll($data) {
      switch ($data["origen"]) {
         case 'payment_methods':
            $order = "name";
            $where = "status = 'active'";
            $tabla = "payment_methods";
            break;
         case 'payment_terms':
            $order = "name";
            $where = "status = 'active'";
            $tabla = "payment_terms";
            break;
         case 'payment_status':
            $order = "id_payment_status";
            $where = "id_payment_status > 0";
            $tabla = "payment_status";
            break;
         case 'companies':
            $order = "id_empresa";
            $where = "id_empresa < 99999";
            $tabla = $data["origen"];
            break;
         default:
            return null;
            break;
      }
      return GeneralModel::getAll($tabla, $order, $where, $conn = null);
   }

   
   //*********************************************************************************************
   static public function verifyRequiredFields($required, $post) {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $response = array("success" => false, "message" =>'Método inválido');
         return $response;
      }
      // var_dump($post);
      foreach ($required as $field) {
         if (!isset($post[$field]) || $post[$field] === "") {
            $response = array("success" => false, "message" => 'Por favor, completa todos los campos obligatorios'.$field);
            return $response;
         }
      }
      $response = array("success" => true, "message" => 'campos obligatorios completados');
      return $response;      
   }


   //*********************************************************************************************
   static public function handleMySQLerror($errorCode, $errorMessage) {
      $errorMessages = [
         /*
         // Errores de Conexión y Acceso
         1044 => "Sin permisos para acceder a la base de datos",
         1045 => "Error de acceso: Credenciales de base de datos incorrectas",
         1049 => "Base de datos no encontrada",
         2002 => "No se puede conectar al servidor MySQL",
         2003 => "Servidor MySQL no disponible",
         2006 => "Timeout de conexión",

         // Errores de Permisos
         1142 => "Sin permisos para ejecutar esta operación en la base de datos",

         // Errores de Sintaxis SQL
         1054 => "Columna no encontrada en la tabla",
         1064 => "Error de sintaxis en la consulta SQL",
         1146 => "La tabla especificada no existe",

         // Errores de Integridad de Datos
         1062 => "Registro duplicado (violación de clave única)",
         1452 => "Violación de clave foránea - registro referenciado no existe",
         1048 => "Campo obligatorio sin valor",
         1265 => "Datos truncados - valor muy largo para la columna",
         1366 => "Valor incorrecto para el tipo de dato de la columna",
         1406 => "Datos demasiado largos para una de los datos",

         // Errores de Límites
         1153 => "Paquete de datos muy grande",
         1040 => "Demasiadas conexiones simultáneas al servidor",

         // Errores de Transacciones  
         1205 => "Timeout de bloqueo - transacción muy larga",
         1213 => "Deadlock detectado - reintentar operación",
         
         1216 => "No se puede agregar registro hijo - registro padre no existe",
         1217 => "No se puede eliminar registro padre - tiene registros hijos",
         1451 => "No se puede eliminar o actualizar registro padre - restricción de clave foránea",
         1452 => "No se puede agregar o actualizar registro hijo - restricción de clave foránea",
         */


         1000 => "Error general del manejador de tablas",
         1001 => "No se puede crear archivo de base de datos",
         1002 => "No se puede crear directorio de base de datos",
         1003 => "No se puede crear tabla",
         1004 => "No se puede crear archivo de base de datos",
         1005 => "No se puede crear tabla (error de clave foránea)",
         1006 => "No se puede crear base de datos",
         1007 => "La base de datos ya existe",
         1008 => "La base de datos no existe",
         1009 => "Error al eliminar base de datos",
         1010 => "Error al eliminar directorio de base de datos",
         1011 => "Error al eliminar archivo de base de datos",
         1012 => "No se puede leer registro en la tabla del sistema",
         1040 => "Demasiadas conexiones al servidor MySQL",
         1042 => "Error de conexión al servidor - host bloqueado",
         1043 => "Handshake incorrecto",
         1044 => "Acceso denegado para el usuario a la base de datos",
         1045 => "Acceso denegado - credenciales incorrectas",
         1046 => "No se ha seleccionado ninguna base de datos",
         1049 => "Base de datos desconocida",
         1050 => "La tabla ya existe",
         1051 => "Tabla desconocida",
         1053 => "Servidor apagado durante la consulta",
         1054 => "Columna desconocida en la consulta",
         1058 => "Número incorrecto de columnas en la consulta",
         1060 => "Nombre de columna duplicado",
         1062 => "Entrada duplicada para clave única",
         1064 => "Error de sintaxis en SQL",
         1068 => "Múltiples claves primarias definidas",
         1072 => "Columna de clave especificada no existe",
         1075 => "Definición de tabla incorrecta - solo una columna autoincremental",
         1081 => "No se puede establecer conexión con el socket",
         1091 => "No se puede eliminar - no existe",
         1101 => "Nombre de tabla o base de datos incorrecto",
         1102 => "Nombre de tabla o base de datos incorrecto",
         1103 => "Nombre de tabla incorrecto en la definición de tabla",
         1104 => "Comando SELECT incorrecto",
         1129 => "Host bloqueado por muchos errores de conexión",
         1130 => "Host no permitido para conectarse",
         1132 => "Sin privilegios para operar en la base de datos",
         1133 => "Usuario no existe o acceso denegado",
         1141 => "Privilegios insuficientes para el usuario",
         1142 => "Comando denegado para el usuario",
         1143 => "Acceso denegado a la columna para el usuario",
         1146 => "La tabla no existe",
         1149 => "Error de sintaxis en SQL",
         1153 => "Paquete recibido es muy grande",
         1154 => "Paquete recibido está incompleto",
         1158 => "Error de comunicación con la red",
         1159 => "Timeout de lectura de la red",
         1160 => "Error de escritura en la red",
         1161 => "Timeout de escritura en la red",
         1162 => "Resultset demasiado grande",
         1163 => "Columna utilizada sin tipo de datos",
         1166 => "Nombre de columna incorrecto",
         1171 => "Todas las partes de la clave primaria deben ser NOT NULL",
         1177 => "La tabla no existe",
         1184 => "Tabla abierta impide operación",
         1193 => "Nombre de variable del sistema desconocido",
         1194 => "Tabla marcada como estrellada y debe ser reparada",
         1195 => "Tabla marcada como estrellada y debe ser reparada",
         1203 => "Demasiadas conexiones de usuario",
         1205 => "Timeout de bloqueo",
         1207 => "Lectura inconsistente durante transacción",
         1211 => "No se puede crear usuario",
         1213 => "Deadlock encontrado",
         1216 => "No se puede agregar registro hijo - registro padre no existe",
         1217 => "No se puede eliminar registro padre - tiene registros hijos",
         1226 => "Límite de recursos excedido para el usuario",
         1227 => "Acceso denegado - necesita privilegios específicos",
         1231 => "Variable no puede ser establecida con el valor dado",
         1235 => "Versión de MySQL no soporta esta característica",
         1241 => "Operando debe contener una columna",
         1242 => "Subconsulta retorna más de una fila",
         1247 => "Referencia no soportada",
         1248 => "Cada tabla derivada debe tener su propio alias",
         1249 => "Seleccione un alias para la tabla",
         1250 => "Cláusula de tabla duplicada",
         1251 => "El cliente no soporta el protocolo de autenticación",
         1252 => "Contraseña incorrecta para el usuario",
         1261 => "Fila no contiene datos para todas las columnas",
         1262 => "Fila truncada durante la inserción",
         1263 => "Columna establecida a valor por defecto",
         1264 => "Valor fuera de rango para la columna",
         1265 => "Datos truncados para la columna",
         1270 => "Nombre de columna duplicado en la consulta",
         1271 => "Tipo de columna duplicado o ilegal en la consulta",
         1280 => "Nombre de columna incorrecto",
         1286 => "Motor de almacenamiento desconocido",
         1290 => "El servidor MySQL está ejecutándose con opciones seguras",
         1292 => "Valor de fecha/tiempo incorrecto",
         1298 => "Zona horaria incorrecta",
         1303 => "Función no existe",
         1304 => "Procedimiento ya existe",
         1305 => "Procedimiento no existe",
         1308 => "Declaración no devuelve un conjunto de resultados",
         1317 => "Consulta interrumpida",
         1318 => "Número incorrecto de argumentos",
         1324 => "Nombre de procedimiento reservado",
         1327 => "Nombre de variable no declarado",
         1329 => "No hay datos - cero filas obtenidas",
         1330 => "Variable duplicada",
         1331 => "Variable sin valor por defecto",
         1336 => "Tabla de destino no es actualizable",
         1347 => "Objeto no coincide con el tipo esperado",
         1348 => "Columna no es actualizable",
         1353 => "Vista no tiene columna de reemplazo",
         1364 => "Campo no tiene un valor por defecto",
         1365 => "División por cero",
         1366 => "Valor incorrecto para la columna",
         1369 => "Verificación de fila falla",
         1370 => "Acceso denegado para rutina específica",
         1396 => "Operación falló para el usuario",
         1406 => "Datos demasiado largos para uno de los datos",
         1410 => "No se permite actualizar tabla en consulta",
         1411 => "Función incorrecta retorna cadena",
         1413 => "Fila duplicada durante la inserción",
         1415 => "No se permite operación en vista",
         1418 => "Función no tiene la misma validación de parámetros",
         1422 => "Subconsreta explícita retorna más de una fila",
         1425 => "Cadena de tamaño incorrecto para rutina",
         1426 => "Tabla temporal con nombre duplicado",
         1427 => "Vista recursiva necesita alias",
         1429 => "No se puede conectar al servidor externo",
         1435 => "Trigger en la tabla no existe",
         1436 => "Límite de hilos alcanzado",
         1437 => "Demasiados niveles de trigger",
         1439 => "Trigger no puede modificar tabla diferente",
         1440 => "Trigger en tabla del sistema no permitido",
         1441 => "Fecha y hora: valor fuera de rango",
         1442 => "Trigger ya existe",
         1451 => "No se puede eliminar o actualizar registro padre",
         1452 => "No se puede agregar o actualizar registro hijo",
         1453 => "Subconsulta retorna más de una fila",
         1458 => "Usuario requiere privilegios más altos",
         1460 => "Demasiados grupos en la consulta",
         1461 => "No se pueden agrupar columnas de texto largo",
         1462 => "No se puede usar tabla en consulta",
         1463 => "No se puede usar tabla en consulta",
         1525 => "Carácter incorrecto en la cadena",
         1558 => "Nombre de columna duplicado en la tabla",
         1560 => "Nombre de columna duplicado en la consulta",
         1562 => "No se puede crear partición",
         1563 => "Partición ya existe",
         1564 => "Partición no existe",
         1566 => "No se puede crear tabla temporal",
         1567 => "No se puede crear tabla",
         1568 => "No se puede crear tabla con particiones",
         1577 => "No se puede eliminar partición",
         1578 => "No se puede truncar partición",
         1580 => "No se puede agregar partición",
         1581 => "No se puede reorganizar partición",
         1582 => "No se puede eliminar todas las particiones",
         1583 => "No se puede reconstruir partición",
         1584 => "No se puede intercambiar partición",
         1585 => "No se puede analizar partición",
         1586 => "No se puede reparar partición",
         1587 => "No se puede optimizar partición",
         1588 => "No se puede comprobar partición",
         1589 => "No se puede actualizar partición",
         1590 => "No se puede cambiar partición",
         1591 => "No se puede renombrar partición",
         1592 => "No se puede truncar tabla con particiones",
         1593 => "No se puede crear trigger en tabla con particiones",
         1594 => "No se puede crear evento",
         1595 => "Evento ya existe",
         1596 => "Evento no existe",
         1597 => "No se puede alterar evento",
         1598 => "No se puede eliminar evento",
         1599 => "No se puede ejecutar evento",
         1600 => "No se puede crear función",
         1601 => "Función ya existe",
         1602 => "Función no existe",
         1603 => "No se puede alterar función",
         1604 => "No se puede eliminar función",
         1605 => "No se puede ejecutar función",

      ];
      $userMessage = $errorMessages[$errorCode] ?? "Error de base de datos: $errorMessage";
      // Log para desarrollo
      error_log("MySQL Error $errorCode: $errorMessage");
      return [
         'success' => false,
         'error_code' => $errorCode,
         'error_message' => $userMessage,
         'technical_message' => $errorMessage // Solo en desarrollo
      ];
   }


   //*********************************************************************************************
   static public function setDefault($data) {
      return GeneralModel::setDefault($data);
      // $ressponse = true;
      // switch ($data["origen"]) {
      //    case 'companyname':
      //       $_SESSION["companyname"] = $data["setData"];
      //       break;
      //    default:
      //       $ressponse = false;
      //       break;
      // }
      // return $ressponse;
   }

}