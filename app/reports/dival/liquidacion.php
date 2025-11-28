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

	public $fecha = '';

   //**************************************************************************************
   function Header() {
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
	public $informe, $id_cheque, $token;
	public function traerImpresionDocumento() {
      $pdf = new PDF('P', 'mm', 'medcar');
      $title = 'Liquidación de Documento';
		$pdf->SetTitle($title,true);
		// $icon = "../views/img/favicons/favicon-32x32.png";
		$icon = "../../../assets/img/favicons/favicon.ico";
		$pdf->SetIcon($icon);
		$pdf->SetAuthor("Tincolsas", true);
		$pdf->SetAutoPageBreak(true, 0);
		$pdf->SetMargins(12, 10, 12);
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
         $pdf->Output('I', "LIQ-".$this->id_cheque.'.pdf', true);
         return;
      }

      $pdf->SetDrawColor(190, 190, 190);
      $pdf->SetFillColor(43, 114, 171);
		$pdf->SetTextColor(0, 0, 0);
      // $pdf->Cell(195, 5, $_SESSION['companyname'], 0, 0, 'L', false);
      // $pdf->Ln(5);
		// $pdf->SetFont('Arial', '', 9);
      // $pdf->Cell(195, 5, $_SESSION['companyid'], 0, 0, 'L', false);
      $valEntregado = $this->informe['valor_cheque'] - $this->informe['comision'] - $this->informe['valor_iva'] - ($this->informe['valor_cheque'] * $this->informe['impuesto_banco'] / 100);
      $pdf->Ln(0);
		$pdf->SetFont('Arial', 'B', 12);
      $pdf->Cell(190, 5, 'LIQUIDACION', 0, 0, 'C', false);
      $pdf->Ln(0);
      $pdf->Cell(155);
		$pdf->Cell(35, 5, "", 1, 0, 'L', false);
      $pdf->Ln(0);
      $pdf->Cell(150);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 12);
      $pdf->Cell(12, 5, 'Nro', 0, 0, 'L', true);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(0);
      $pdf->Cell(162);
		$pdf->SetFont('Arial', 'B', 12);
		$pdf->Cell(28, 5, str_pad($this->informe['id_cheque'], 8, "0", STR_PAD_LEFT), 0, 0, 'C', false);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 10);
      $pdf->Ln(5);
      $pdf->Cell(155);
		$pdf->Cell(35, 5, "", 1, 0, 'L', false);
      $pdf->Ln(0);
      $pdf->Cell(150);
		$pdf->SetTextColor(255, 255, 255);
      $pdf->Cell(12, 5, 'Fecha', 0, 0, 'L', true);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(0);
      $pdf->Cell(162);
      $pdf->Cell(28, 5, $this->informe['fecha'], 0, 0, 'C', false);
      $pdf->Ln(7);
		$pdf->Cell(125, 15, "", 1, 0, 'L', false);
      $pdf->Cell(5);
		$pdf->Cell(60, 15, "", 1, 0, 'L', false);
      $pdf->Ln(0);
		$pdf->SetTextColor(255, 255, 255);
      $pdf->Cell(125, 5, 'ENTREGADO A', 0, 0, 'L', true);
      $pdf->Cell(5);
      $pdf->Cell(60, 5, 'VALOR ENTREGADO', 0, 0, 'C', true);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(8);
      $pdf->Cell(125, 0, $this->informe['TerNombr'], 0, 0, 'L', false);
      $pdf->Cell(15);
      $pdf->Cell(30, 0,"$ ". number_format($valEntregado, 2), 0, 0, 'R', false);
      $pdf->Ln(4);
      $pdf->Cell(125, 0, "Doc Identidad: ". $this->informe['TerDocId'], 0, 0, 'L', false);
      $pdf->Ln(5);
		$pdf->Cell(190, 24, "", 1, 0, 'L', false);
      $pdf->Ln(0);
		$pdf->SetTextColor(255, 255, 255);
      $pdf->Cell(190, 5, 'DETALLE:', 0, 0, 'L', true);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(8);
      $pdf->Cell(2);
      $pdf->Cell(45, 0, "Número del Cheque: ", 0, 0, 'L', false);
      $pdf->Cell(70, 0, $this->informe['numero'], 0, 0, 'L', false);
      $pdf->Ln(4);
      $pdf->Cell(2);
      $pdf->Cell(45, 0, "Banco: ", 0, 0, 'L', false);
      $pdf->Cell(70, 0, $this->informe['banco_nombre'], 0, 0, 'L', false);
      $pdf->Ln(4);
      $pdf->Cell(2);
      $pdf->Cell(45, 0, "Sucursal: ", 0, 0, 'L', false);
      $pdf->Cell(70, 0, $this->informe['banco_sucursal'], 0, 0, 'L', false);
      $pdf->Ln(4);
      $pdf->Cell(2);
      $pdf->Cell(45, 0, "Numero de Cuenta: ", 0, 0, 'L', false);
      $pdf->Cell(50, 0, $this->informe['banco_num_cuenta'], 0, 0, 'L', false);
      $pdf->Ln(4);

		$pdf->Cell(190, 34, "", 1, 0, 'L', false);
      $pdf->Ln(4);
      $pdf->Cell(2);
      $pdf->Cell(95, 0, "Valor del Documento: ", 0, 0, 'L', false);
      $pdf->Cell(30, 0, number_format($this->informe['valor_cheque'],2), 0, 0, 'R', false);
      $pdf->Ln(4);
      $pdf->Cell(2);
      $pdf->Cell(95, 0, "Porcentaje Comisión: ", 0, 0, 'L', false);
      $pdf->Cell(30, 0, $this->informe['porcentaje_comision'], 0, 0, 'R', false);
      $pdf->Ln(4);
      $pdf->Cell(2);
      $pdf->Cell(95, 0, "Número de Días: ", 0, 0, 'L', false);
      $pdf->Cell(30, 0, $this->informe['dias_cobrados'], 0, 0, 'R', false);
      $pdf->Ln(4);
      $pdf->Cell(2);
      $pdf->Cell(95, 0, "Valor Comisión: ", 0, 0, 'L', false);
      $pdf->Cell(30, 0, number_format($this->informe['comision'],2), 0, 0, 'R', false);
      $pdf->Ln(4);
      $pdf->Cell(2);
      $pdf->Cell(95, 0, "Valor IVA: ", 0, 0, 'L', false);
      $pdf->Cell(30, 0, number_format($this->informe['valor_iva'],2), 0, 0, 'R', false);
      $pdf->Ln(4);
      $pdf->Cell(2);
      $pdf->Cell(95, 0, "Gravamen movimiento Financiero (GMF): ", 0, 0, 'L', false);
      $pdf->Cell(30, 0, number_format($this->informe['valor_cheque'] * $this->informe['impuesto_banco'] / 100 ,2), 0, 0, 'R', false);
      $pdf->Ln(3);
      $pdf->Cell(2);
      $pdf->Cell(95);
		$pdf->Cell(30, 0, "", 1, 0, 'L', false);
      $pdf->Ln(3);
      $pdf->Cell(2);
		$pdf->SetFont('Arial', 'B', 10);
      $pdf->Cell(95, 0, "Valor Entregado: ", 0, 0, 'L', false);
      $pdf->Cell(30, 0, number_format($valEntregado ,2), 0, 0, 'R', false);
		$pdf->SetFont('Arial', '', 10);
      $pdf->Ln(4);
		$valletras = convertir($valEntregado, '1');
		//$valletras = convertir(4887474893, '1');
      
		$pdf->Cell(190, 16, "", 1, 0, 'L', false);
      $pdf->Ln(4);
      $pdf->Cell(100, 0, "Son: ", 0, 0, 'L', false);
      $pdf->Ln(3);
      $pdf->Write(4, $valletras);
      if (strlen($valletras) < 131 ) {
         $pdf->Ln(10);
      } else {
         $pdf->Ln(6);
      }
		$pdf->Cell(88, 16, "", 1, 0, 'L', false);
		$pdf->Cell(102, 16, "", 1, 0, 'L', false);
      $pdf->Ln(4);
      $pdf->Cell(75, 0, 'Elaboró: ', 0, 0, 'L', false);
      $pdf->Cell(15);
      $pdf->Cell(80, 0, 'Recibí: ', 0, 0, 'L', false);
      $pdf->Ln(5);
		$pdf->SetFont('Arial', '', 7);
      $pdf->Cell(75, 0, $this->informe['name'], 0, 0, 'L', false);
		$pdf->SetFont('Arial', '', 10);
      $pdf->Cell(15);
      $pdf->Cell(80, 0, 'Doc Identidad: ', 0, 0, 'L', false);
      $pdf->Output('I', "LIQ-".$this->informe['consecutivo'].'.pdf', true);
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