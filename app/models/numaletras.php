<?php
  function basico($numero) {
    $valor = array ('un','dos','tres','cuatro','cinco','seis','siete','ocho',
    'nueve','diez','once','doce', 'trece', 'catorce', 'quince','dieciseis','diecisiete',
    'dieciocho', 'diecinueve', 'veinte', 'veintiuno', 'veintidos', 'veintitres',
    'veinticuatro','veinticinco', 'veintiséis','veintisiete','veintiocho','veintinueve');
    return $valor[$numero - 1];
  }

  function decenas($n) {
    $decenas = array (30=>'treinta',40=>'cuarenta',50=>'cincuenta',60=>'sesenta',
    70=>'setenta',80=>'ochenta',90=>'noventa');
    if( $n <= 29) return basico($n);
    $x = $n % 10;
    if ( $x == 0 ) {
      return $decenas[$n];
    } else return $decenas[$n - $x].' y '. basico($x);
  }

  function centenas($n) {
    $cientos = array (100 =>'cien',200 =>'doscientos',300=>'trecientos',
    400=>'cuatrocientos', 500=>'quinientos',600=>'seiscientos',
    700=>'setecientos',800=>'ochocientos', 900 =>'novecientos');
    if( $n >= 100) {
      if ( $n % 100 == 0 ) {
        return $cientos[$n];
      } else {
        $u = (int) substr($n,0,1);
        $d = (int) substr($n,1,2);
        return (($u == 1)?'ciento':$cientos[$u*100]).' '.decenas($d);
      }
    } else return decenas($n);
  }

  function miles($n) {
    if($n > 999) {
      if( $n == 1000) {return 'mil';}
      else {
        $l = strlen($n);
        $c = (int)substr($n,0,$l-3);
        $x = (int)substr($n,-3);
        if($c == 1) {$cadena = 'mil '.centenas($x);}
        else if($x != 0) {$cadena = centenas($c).' mil '.centenas($x);}
        else $cadena = centenas($c). ' mil';
        return $cadena;
      }
    } else return centenas($n);
  }

  function millones($n) {
    if($n == 1000000) {return 'un millón';}
    else {
      $l = strlen($n);
      $c = (int)substr($n, 0, $l-6);
      $x = (int)substr($n, -6);
      if($c == 1) {
        $cadena = ' millón ';
      } else {
        $cadena = ' millones ';
      }
      if ($x == 0) {
        $cadena .= ' de ';
      }
      return miles($c).$cadena.(($x > 0) ? miles($x):'');
    }
  }

  function convertir($n,$p='0') {
    if(intval($n) != $n){
      $ni = intval($n);
      $nd = substr($n,strlen($n)-2,2);
    }else {
      $ni = intval($n);
      $nd = "00";
    }
    // return $ni;
    switch (true) {
      case ($ni == 0): $nl = "cero"; break;
      case ($ni >= 1 && $ni <= 29) : $nl = basico($ni); break;
      case ($ni >= 30 && $ni < 100) : $nl =  decenas($ni); break;
      case ($ni >= 100 && $ni < 1000) : $nl =  centenas($ni); break;
      case ($ni >= 1000 && $ni <= 999999): $nl =  miles($ni); break;
      case ($ni >= 1000000): $nl =  millones($ni);
    }
    if($p == 1){
      if($ni == 1){
        return $nl." peso "."con ".$nd." centavos";
      }else return $nl." pesos "."con ".$nd." centavos";
      return $nl." pesos "."con ".$nd." centavos";
    }else return $nl." con ".$nd." decimas";
  }

	/************************** CONVERTIR FECHA LARGA ********************/
	function longDate($fecha) {
		$meses = array("Mes Nulo", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		$fecha_array = explode("-", $fecha);
		return $fecha_array[2] . " de " . $meses[intval($fecha_array[1])] . " del año " . $fecha_array[0];
	}




?>
