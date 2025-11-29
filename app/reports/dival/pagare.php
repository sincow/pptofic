<?php
if (!isset($_SESSION)) {
	session_start();
}
// require_once '../../../config/Database.php';
$_SESSION['reportPath'] = '../../';
require_once "../../models/numaletras.php";
require_once "../../models/dival/mdlcheques.php";
//error_reporting(0);
require_once '../../../vendor/fpdf/fpdf.php';
require_once '../../../vendor/PHPExcel/Classes/PHPExcel.php';
unset($_SESSION['reportPath']);
$token = $_GET['token'] ?? '';
if (empty($token) || !isset($_SESSION['report_temp_' . $token])) {
   echo "<script>
      alert('Token inválido');
      window.close();
   </script>";
   exit;
}
$reportData = $_SESSION['report_temp_' . $token];
if (time() - $reportData['timestamp'] > 585) {
   unset($_SESSION['report_temp_' . $token]);
   echo "<script>
      alert('Token inválido');
      window.close();
   </script>";
   exit;
}

// Obtener datos para el informe
$id_cheque = $reportData['id_cheque'];
$informe = ChequesModel::getOne($reportData);


//**************************************************************************************
class PDF extends FPDF{
	protected $B = 0;
	protected $I = 0;
	protected $U = 0;
	protected $HREF = '';
	protected $angle = 0;

   public $numGrn = '';
   public $po_number = '';
   public $wh_name = '';
   public $vendor = '';
   public $dateGrn = '';
   public $session_status = '';
   public $w = '';
	public $fecha = '';

   //**************************************************************************************
   function Header() {
      // $this->Image('../resources/img/logo.png', 10, 8, 33);
      // $this->SetFont('Arial', 'B', 15);
      /*
		$this->SetDrawColor(190, 190, 190);
		$this->SetFont('Arial', 'B', 12);
      $this->SetFillColor(43, 114, 171);
		$this->SetTextColor(255, 255, 255);
      $this->Cell(95);
		$this->Cell(30, 14, "", 1, 0, 'L', false);
		$this->Cell(30, 5, 'LETRA DE CAMBIO', 0, 0, 'L', true);
      $this->Ln(3);
		$this->SetFont('Arial', '', 9);
      $this->Cell(180, 0, isset($_SESSION["parameters"]["companyName"]) ? $_SESSION["parameters"]["companyName"] : 'Polylogik' ,0, 0, 'L');
      $this->Cell(5);
		$this->Cell(22, 0, Lang::get('grn'), 0, 0, 'R', false);
      $this->Ln(4);
      $this->Cell(180, 0, isset($_SESSION["parameters"]["companyAddress"]) ? $_SESSION["parameters"]["companyAddress"] : 'address' ,0, 0, 'L');
      $this->Cell(5);
      $this->Cell(22, 0, 'GRN-'.str_pad($this->numGrn, 6, "0", STR_PAD_LEFT), 0, 0, 'R');
      $this->Ln(4);
      $this->Cell(185);
      $this->Cell(22, 0, 'Date: '.$this->dateGrn, 0, 0, 'R');
      $this->Ln(5);
		$this->Cell(102, 20, "", 1, 0, 'L', false);
      $this->Cell(3);
		$this->Cell(102, 20, "", 1, 0, 'L', false);
      $this->Ln(3);
		$this->SetFont('Arial', '', 9);
		$this->Cell(102, 0, strtoupper(Lang::get('vendor')), 0, 0, 'L', false);
      $this->Cell(3);
      $this->Cell(102, 0, strtoupper(Lang::get('warehouse')), 0, 0, 'L', false);
      $this->Ln(4);
		$this->SetFont('Arial', '', 9);
      $this->Cell(102, 0, $this->vendor, 0, 0, 'L', false);
      $this->Cell(3);
      $this->Cell(102, 0, $this->wh_name, 0, 0, 'L', false);
      $this->Ln(6);
      $this->SetFont('Arial', '', 9);
      $this->Cell(102, 0, strtoupper(Lang::get('po_number')), 0, 0, 'L', false);
      $this->Cell(3);
      $this->Cell(102, 0, strtoupper(Lang::get('session') .' '. Lang::get('status')), 0, 0, 'L', false);
      $this->Ln(4);
      $this->SetFont('Arial', '', 9);
      $this->Cell(102, 0, $this->po_number, 0, 0, 'L', false);
      $this->Cell(3);
      $this->Cell(102, 0, strtoupper($this->session_status), 0, 0, 'L', false);
      $this->Ln(5);
      $this->SetFillColor(43, 114, 171);
		$this->SetTextColor(255, 255, 255);
		$this->SetFont('Arial', '', 8);
		$this->Cell($this->w[0], 5, Lang::get('line'), 0, 0, 'R', true);
		$this->Cell($this->w[1], 5, Lang::get('sku'), 0, 0, 'L', true);
		$this->Cell($this->w[2], 5, Lang::get('name'), 0, 0, 'L', true);
		$this->Cell($this->w[3], 5, Lang::get('ordered').' '.Lang::get('quantity'), 0, 0, 'R', true);
		$this->Cell($this->w[4], 5, Lang::get('received').' '.Lang::get('quantity'), 0, 0, 'R', true);
		$this->Cell($this->w[5], 5, Lang::get('damaged').' '.Lang::get('quantity'), 0, 0, 'R', true);
		$this->SetTextColor(0, 0, 0);
		$this->Ln(6);
		$this->SetFont('Arial', '', 8);
      */
   }


   //**********************************************************************************
	function Footer() {
		// $this->SetY(-25);
	}


   //**********************************************************************************
	function vcell($LongText, $c_width, $c_height, $x_axis, $text, $justi = 'L') {
		$len = strlen($text);
		$lengthToSplit = $LongText;
		$w_y = 3;
		if ($len > $lengthToSplit) {
			$w_text = str_split($text, $lengthToSplit);
			foreach ($w_text as $t) {
				$this->SetX($x_axis);
				$this->Cell($c_width, $w_y, $t, 0, 0, $justi);
				$w_y += 6;
			}
			$x_fin = $this->getx();
			$this->SetX($x_fin);
		} else {
			$this->SetX($x_axis);
			$this->Cell($c_width, $c_height, $text, 0, 0, $justi);
		}
	}


   //**********************************************************************************
	function Rotate($angle, $x = -1, $y = -1) {
		if ($x == -1)
			$x = $this->x;
		if ($y == -1)
			$y = $this->y;
		if ($this->angle != 0)
			$this->_out('Q');
		$this->angle = $angle;
		if ($angle != 0) {
			$angle *= M_PI / 180;
			$c = cos($angle);
			$s = sin($angle);
			$cx = $x * $this->k;
			$cy = ($this->h - $y) * $this->k;
			$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
		}
	}


   //**********************************************************************************
	function temporaire($texte) {
		$this->SetFont('Arial', 'B', 50);
		$this->SetTextColor(203, 203, 203);
		$this->Rotate(45, 55, 190);
		$this->Text(55, 190, $texte);
		$this->Rotate(0);
		$this->SetTextColor(0, 0, 0);
	}
}


//**************************************************************************************
class imprimirDocumento {
	public $informe, $id_cheque, $numero, $token;
	public function traerImpresionDocumento() {

      $pdf = new PDF('P', 'mm', 'letter');
      $this->numero = $this->id_cheque;
      $title = 'Pagaré';
		$pdf->SetTitle($title,true);
		// $icon = "../views/img/favicons/favicon-32x32.png";
		$icon = "../../../assets/img/favicons/favicon.ico";
		$pdf->SetIcon($icon);
		$pdf->SetAuthor("Tincolsas", true);
		$pdf->SetAutoPageBreak(true, 0);
		$pdf->SetMargins(8, 12, 9);
		$pdf->AliasNbPages();
		$pdf->AddPage();
      if ($this->informe == null) {
			$pdf->SetFont('Arial', 'B', 35);
			$pdf->SetTextColor(203, 203, 203);
			$pdf->Rotate(45, 55, 230);
			$pdf->Text(115, 200, 'Registro no encontrado');
			$pdf->Rotate(0);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
         $pdf->Output('I', $this->numero.'.pdf', true);
         return;
      }
      $c_height = 5;
      $pdf->SetDrawColor(190, 190, 190);
		$pdf->SetFont('Arial', 'B', 12);
      $pdf->SetFillColor(43, 114, 171);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetTextColor(0, 0, 0);
      // $pdf->Cell(158);
		$pdf->Cell(200, 5, 'PAGARÉ', 0, 0, 'C', false);
      $pdf->Ln(0);
		$pdf->Cell(190, 5, $this->informe['numero'], 0, 0, 'R', false);
      $pdf->Ln(10);
		$pdf->SetFont('Arial', '', 9);
      $longDate = longDate($this->informe['fecha']);
      $pdf->Cell(200, 0, "LUGAR Y FECHA DE FIRMA: BARRANQUILLA, ".$longDate, 0, 0, 'L', false);
      $pdf->Ln(5);
      $pdf->Cell(200, 0, "PAGARÉ NÚMERO: ".$this->informe['numero'], 0, 0, 'L', false);
      $pdf->Ln(3);
		$valletras = convertir($this->informe['valor_cheque'], '1');
		// $valletras = strtoupper(convertir(110101001.45, '1'));
      $cantidad = "VALOR: ".strtoupper($valletras)." ($".number_format($this->informe['valor_cheque'],2).")";
      $x_axis = $pdf->getx();
      $pdf->MultiCell(200, 4, $cantidad, 0, 'L');
      $pdf->Ln(3);
      $pdf->Cell(200, 0, "INTERESES DURANTE EL PLAZO: MAXIMO LEGAL VIGENTE SUPERBANCARIA", 0, 0, 'L', false);
      $pdf->Ln(5);
      $pdf->Cell(200, 0, "INTERESES DE MORA: MAXIMO LEGAL VIGENTE SUPERBANCARIA", 0, 0, 'L', false);
      $pdf->Ln(3);
      $pdf->MultiCell(200, 4, "PERSONA A QUIEN DEBE HACERSE EL PAGO: RICARDO RAMON OLIVA MEDINA Y/O ".$_SESSION['companyname'], 0, 'L');
      $pdf->Ln(3);
      $longDate = longDate($this->informe['vencimiento']);
		$pdf->SetFont('Arial', 'B', 9);
      $pdf->Cell(200, 0, "FECHA DE VENCIMIENTO DE LA OBLIGACION: ".$longDate, 0, 0, 'L', false);
		$pdf->SetFont('Arial', '', 9);
      $pdf->Ln(5);
      $pdf->Cell(200, 0, "DEUDORES:", 0, 0, 'L', false);
      $pdf->Ln(3);
      $pdf->MultiCell(200, 4, "Nombre e identificación: ".$this->informe['TerNombr']." Con ".$this->informe['TerTiDoc']." Nro ".$this->informe['TerDocId'], 0,'L');
      $pdf->Ln(1);
      $pdf->MultiCell(200, 4, "Nombre e identificación: ".$this->informe['TerNombr2']." Con ".$this->informe['TerTiDoc2']." Nro ".$this->informe['TerDocId2'], 0, 'L');
      $pdf->Ln(1);
      if ($this->informe['TerDocId3'] != null && $this->informe['TerNombr3'] != null) {
         $pdf->MultiCell(200, 4, "Nombre e identificación: ".$this->informe['TerNombr3']." Con ".$this->informe['TerTiDoc3']." Nro ".$this->informe['TerDocId3'], 0, 'L');
         $pdf->Ln(1);
      }
      if ($this->informe['TerDocId4'] != null && $this->informe['TerNombr4'] != null) {
         $pdf->MultiCell(200, 4, "Nombre e identificación: ".$this->informe['TerNombr4']." Con ".$this->informe['TerTiDoc4']." Nro ".$this->informe['TerDocId4'], 0, 'L');
         $pdf->Ln(1);
      }
      $pdf->Ln(1);
      $pdf->SetFont('Arial', 'B', 9);
      // $pdf->Write(4, 0, "Declaramos PRIMERA.- OBJETO: ", 0, 0);
      $pdf->Write(4, "Declaramos PRIMERA.- OBJETO: ");
      $pdf->SetFont('Arial', '', 9);
      $pdf->Write(4, "Que por virtud del presente título valor pagaré(mos) incondicionalmente, a la orden de RICARDO RAMON OLIVA MEDINA Y/O ".
      $_SESSION['companyname']." o a quien represente sus derechos, en la ciudad y dirección indicados, en las fechas de amortización por cuotas señaladas en la cláusula tercera de este pagaré, la suma de: ".
      $valletras." ($".number_format($this->informe['valor_cheque'],2)."),  más los intereses señalados en la cláusula segunda de este documento.");
      $pdf->Ln(6);
      $pdf->SetFont('Arial', 'B', 9);
      // $pdf->MultiCell(200, 4, "SEGUNDA.- INTERESES: Que sobre la suma debida reconoceré(mos) intereses a la tasa máxima legal vigente por la Superintendencia Bancaria, sobre el capital o su saldo insoluto. En caso de mora reconoceré(mos) intereses a la tasa máxima legal autorizada.", 0, 'L');
      $pdf->Write(4, "SEGUNDA.- INTERESES: ");
      $pdf->SetFont('Arial', '', 9);
      $pdf->Write(4, "Que sobre la suma debida reconoceré(mos) intereses a la tasa máxima legal vigente por la Superintendencia Bancaria, sobre el capital o su saldo insoluto. En caso de mora reconoceré(mos) intereses a la tasa máxima legal autorizada.");
      $pdf->Ln(6);
      $pdf->SetFont('Arial', 'B', 9);
      $pdf->Write(4, "TERCERA.- PLAZO: ");
      $pdf->SetFont('Arial', '', 9);
      $pdf->Write(4, "Que pagaré(mos) el capital indicado en la cláusula primera en una cuota unica de: ".$valletras." ($".number_format($this->informe['valor_cheque'],2));
      $pdf->Ln(6);
      $pdf->SetFont('Arial', 'B', 9);
      $pdf->Write(4, "CUARTA.-  CONSTITUCION EN MORA: ");
      $pdf->SetFont('Arial', '', 9);
      $pdf->Write(4, "Los deudores renunciamos a ser constituidos en mora, tal y como lo contemplan los artículos 1608 y subsiguiente del código civil.");
      $pdf->Ln(6);
      $pdf->SetFont('Arial', 'B', 9);
      $pdf->Write(4, "QUINTA.- el presente documento PRESTA MERITO EJECUTIVO, ");
      $pdf->SetFont('Arial', '', 9);
      $pdf->Write(4, "Tal y como lo dispone el artículo 488 del Código de Procedimiento Civil y artículo 711 del Código de Comercio.");
      $pdf->Ln(7);
      $longDate = longDate($this->informe['fecha']);
      $pdf->Cell(200, 0, "En constancia de lo anterior, se suscribe este documento el dia ".$longDate, 0, 0, 'L', false);
      $pdf->Ln(7);
      $pdf->Cell(200, 0, "OTORGANTES ", 0, 0, 'L', false);
      $pdf->Ln(7);

      $pdf->SetDrawColor(244, 244, 244);
      $pdf->SetFillColor(244, 244, 244);
		$pdf->Cell(90, 24, "", 1, 0, 'L', true);
      $pdf->Cell(5);
		$pdf->Cell(90, 24, "", 1, 0, 'L', true);
      $pdf->Ln(2);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(90, 0, 'DEUDOR', 0, 0, 'L', false);
      $pdf->Cell(5);
		$pdf->Cell(90, 0, 'DEUDOR', 0, 0, 'L', false);
      $pdf->Ln(16);
		$pdf->Cell(90, 0, '________________________________________________________', 0, 0, 'L', false);
      $pdf->Cell(5);
		$pdf->Cell(90, 0, '________________________________________________________', 0, 0, 'L', false);
      $pdf->Ln(4);
		$pdf->Cell(90, 0, 'CC o NIT Nro', 0, 0, 'L', false);
      $pdf->Cell(5);
		$pdf->Cell(90, 0, 'CC o NIT Nro', 0, 0, 'L', false);
      $pdf->Ln(8);
		$pdf->Cell(90, 24, "", 1, 0, 'L', true);
      $pdf->Cell(5);
		$pdf->Cell(90, 24, "", 1, 0, 'L', true);
      $pdf->Ln(2);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(90, 0, 'DEUDOR', 0, 0, 'L', false);
      $pdf->Cell(5);
		$pdf->Cell(90, 0, 'DEUDOR', 0, 0, 'L', false);
      $pdf->Ln(16);
		$pdf->Cell(90, 0, '________________________________________________________', 0, 0, 'L', false);
      $pdf->Cell(5);
		$pdf->Cell(90, 0, '________________________________________________________', 0, 0, 'L', false);
      $pdf->Ln(4);
		$pdf->Cell(90, 0, 'CC o NIT Nro', 0, 0, 'L', false);
      $pdf->Cell(5);
		$pdf->Cell(90, 0, 'CC o NIT Nro', 0, 0, 'L', false);


      /*
      $pdf->Cell(15, 75, "", 1, 0, 'L', true);
		$pdf->Cell(15, 75, "", 1, 0, 'L', true);
      $pdf->Cell(2);
		$pdf->Cell(155, 19, "", 1, 0, 'L', false);
      $pdf->Ln(0);
      $pdf->SetFillColor(43, 114, 171);
		$pdf->Cell(45, 5, 'ACEPTADA', 0, 0, 'C', true);
      $pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(5);
      $pdf->Cell(47);
		$pdf->Cell(50, 0, 'FECHA: '.$this->informe['fecha'], 0, 0, 'L', false);
		$pdf->Cell(8, 0, 'Nro:', 0, 0, 'L', false);
      $pdf->Ln(-2);
      $pdf->Cell(105);
		$pdf->Cell(20, 4, "", 1, 0, 'L', false);
      $pdf->Ln(2);
      $pdf->Cell(105);
		$pdf->Cell(20, 0, $this->informe['numero'], 0, 0, 'C', false);
      $pdf->Cell(35);
		$pdf->Cell(10, 0, 'Por $', 0, 0, 'L', false);
      $pdf->Ln(-2);
      $pdf->Cell(170);
		$pdf->Cell(28, 4, "", 1, 0, 'L', false);
      $pdf->Ln(2);
      $pdf->Cell(170);
		$pdf->Cell(28, 0, number_format($this->informe['valor_cheque'],2), 0, 0, 'R', false);
      $pdf->Ln(-1);
      $pdf->SetFillColor(43, 114, 171);
		$pdf->SetTextColor(255, 255, 255);
      $pdf->Cell(45, 5, '(Girados)', 0, 0, 'C', true);
      $pdf->Ln(6);
      $pdf->Cell(47);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Cell(150, 0, "Señor(es): ".$this->informe['TerNombr'], 0, 0, 'L', false);
      $pdf->Ln(6);
      $pdf->Cell(47);
      $longDate = longDate($this->informe['vencimiento']);
		$pdf->Cell(150, 0, "El día ".$longDate, 0, 0, 'L', false);
      $pdf->Ln(3);
      $pdf->Cell(47);
		$pdf->Cell(155, 16, "", 1, 0, 'L', false);
      $pdf->Ln(3);
      $pdf->Cell(47);
      $pdf->Cell(150, 0, "Se servirá(n) Usted(es) pagar solidariamente en BARRANQUILLA", 0, 0, 'L', false);
      $pdf->Ln(5);
      $pdf->Cell(47);
      $pdf->Cell(150, 0, "Por esta Letra de cambio sin protesto, excusado el aviso de rechazo a la orden de RICARDO RAMON", 0, 0, 'L', false);
      $pdf->Ln(5);
      $pdf->Cell(47);
      $pdf->Cell(150, 0, "OLIVA MEDINA Y/O ".$_SESSION['companyname'], 0, 0, 'L', false);

      $pdf->Ln(3);
      $pdf->Cell(47);
		$pdf->Cell(155, 19, "", 1, 0, 'L', false);
      $pdf->Ln(2);
      $pdf->Cell(47);
		$valletras = convertir($this->informe['valor_cheque'], '1');
		// $valletras = strtoupper(convertir(99113947897.45, '1'));
      $cantidad = "La Cantidad de: ".$valletras." ($".number_format($this->informe['valor_cheque'],2)."), más intereses durante el plazo del ____________ (     %) mensual y de mora a la tasa máxima legal autorizada.";
      $x_axis = $pdf->getx();
      $pdf->MultiCell(155, $c_height, $cantidad, 0, 'L');
      // $pdf->vcell(80, 80, 4, $x_axis, "La cantidad de: $valletras", 'L');
      if (strlen($valletras) < 90) {
         $pdf->Ln(7);
      } else {
         $pdf->Ln(2);
      }
      $pdf->Cell(52);
		$pdf->Cell(150, 5, "", 1, 0, 'L', false);
      $pdf->Ln(0);
      $pdf->Cell(47);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->Cell(5, 21, "", 1, 0, 'L', false);
		$pdf->Cell(70, 21, "", 1, 0, 'L', false);
		$pdf->Cell(35, 21, "", 1, 0, 'L', false);
		$pdf->Cell(45, 21, "", 1, 0, 'L', false);
      $pdf->Ln(0);
      $pdf->Cell(52);
		$pdf->Cell(70, 5, 'DIRECCION ACEPTANTES', 0, 0, 'L', true);
		$pdf->Cell(35, 5, 'TELEFONOS', 0, 0, 'L', true);
		$pdf->Cell(45, 5, 'Atentamente', 0, 0, 'L', true);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(8);
      $pdf->Cell(52);
      $pdf->Cell(70, 0, $this->informe['TerDirec'], 0, 0, 'L', false);
      $pdf->Cell(35, 0, $this->informe['TerTele1'], 0, 0, 'L', false);
      $pdf->Ln(5);
      $pdf->Cell(52);
      $pdf->Cell(70, 0, $this->informe['TerDirec2'], 0, 0, 'L', false);
      $pdf->Cell(35, 0, $this->informe['TerTele12'], 0, 0, 'L', false);
      $pdf->Ln(5);
      $pdf->Cell(52);
      $pdf->Cell(70, 0, $this->informe['TerDirec3'], 0, 0, 'L', false);
      $pdf->Cell(35, 0, $this->informe['TerTele13'], 0, 0, 'L', false);
      $pdf->Ln(1);
		$pdf->SetFont('Arial', '', 6);
      $pdf->Cell(157);
      $pdf->Cell(45, 0, '(GIRADOR)', 0, 0, 'C', false);
      $pdf->Ln(2);
      $pdf->Cell(50);

      // $pdf->Rotate(90, 55, 230);
      // $pdf->Rotate(90, 20, 165);
      // $pdf->Text(115, 200, 'GIRADOS');

      $pdf->Rotate(90);
      $pdf->Cell(15, 0, 'GIRADOS', 0 , 0, 'C', false);

      // $pdf->Rotate(0);
      // $pdf->Ln(0);
      // $pdf->Cell(13);
      // $pdf->Rotate(90);
      // $pdf->Cell(30, 0, 'CC o NIT', 0 ,0, 'L', false);
      // $pdf->Ln(15);
      // $pdf->Cell(13);
      // $pdf->Cell(30, 0, 'CC o NIT', 0 ,0, 'L', false);
      // $pdf->Ln(15);
      // $pdf->Cell(13);
      // $pdf->Cell(30, 0, 'CC o NIT', 0 ,0, 'L', false);
      

      $pdf->Rotate(90, 13, 195);
      $pdf->Text(119, 202, 'CC o NIT');
      $pdf->Rotate(90, 20, 188);
      $pdf->Text(119, 202, 'CC o NIT');
      $pdf->Rotate(90, 27, 181);
      $pdf->Text(119, 204, 'CC o NIT');

      $pdf->Rotate(0);
      // $pdf->Ln(-27);
      // $pdf->Cell(-4);
		// $pdf->Cell(217, 0, "", 1, 0, 'L', false);
      */

      $pdf->Output('I', $this->informe['numero'].'.pdf', true);


   }


   //**************************************************************************************
   public function traerHojaCalculo() {
   }
}


$documento = new imprimirDocumento();
$documento->informe   = $informe;
$documento->id_cheque = $id_cheque;
$documento->token     = $token;
if ($reportData["GenHojCal"] == '1') {
	$documento->traerHojaCalculo();
} else $documento->traerImpresionDocumento();