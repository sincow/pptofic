<?php
if (!isset($_SESSION)) {
	session_start();
}
// require_once '../../../config/Database.php';
$_SESSION['reportPath'] = '../../';
require_once "../../models/numaletras.php";
require_once "../../models/dival/mdlcajas.php";
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
$id_movimiento = $reportData['id_movimiento'];
$informe = CajasModel::getOne($reportData);


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
	public $informe, $id_movimiento, $token;
	public function traerImpresionDocumento() {

      $pdf = new PDF('P', 'mm', 'medcar');
      $title = 'Vale de Caja';
		$pdf->SetTitle($title,true);
		// $icon = "../views/img/favicons/favicon-32x32.png";
		$icon = "../../../assets/img/favicons/favicon.ico";
		$pdf->SetIcon($icon);
		$pdf->SetAuthor("Tincolsas", true);
		$pdf->SetAutoPageBreak(true, 0);
		$pdf->SetMargins(12, 12, 15);
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
         $pdf->Output('I', "VC-".$this->id_movimiento.'.pdf', true);
         return;
      }

      $pdf->SetDrawColor(190, 190, 190);
		$pdf->SetFont('Arial', 'B', 12);
      $pdf->SetFillColor(43, 114, 171);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Cell(195, 5, $_SESSION['companyname'], 0, 0, 'L', false);
      $pdf->Ln(5);
		$pdf->SetFont('Arial', '', 9);
      $pdf->Cell(195, 5, $_SESSION['companyid'], 0, 0, 'L', false);
      $pdf->Ln(0);
      $pdf->Cell(125);
		$pdf->Cell(65, 5, "", 1, 0, 'L', false);
      $pdf->Ln(0);
      $pdf->Cell(125);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 10);
      $pdf->Cell(37, 5, 'VALE DE CAJA Nro', 0, 0, 'L', true);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(0);
      $pdf->Cell(165);
		$pdf->Cell(20, 5, str_pad($this->informe['consecutivo'], 6, "0", STR_PAD_LEFT), 0, 0, 'R', false);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 9);
      $pdf->Ln(6);
      $pdf->Cell(155);
		$pdf->Cell(35, 5, "", 1, 0, 'L', false);
      $pdf->Ln(0);
      $pdf->Cell(188, 5, 'Fecha: '.$this->informe['fecha'], 0, 0, 'R', false);

      $pdf->Ln(10);
		$pdf->Cell(125, 15, "", 1, 0, 'L', false);
      $pdf->Cell(5);
		$pdf->Cell(60, 15, "", 1, 0, 'L', false);
      $pdf->Ln(0);
		$pdf->SetTextColor(255, 255, 255);
      $pdf->Cell(125, 5, 'PAGADO A:', 0, 0, 'L', true);
      $pdf->Cell(5);
      $pdf->Cell(60, 5, 'POR VALOR DE:', 0, 0, 'C', true);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(8);
      $pdf->Cell(125, 0, $this->informe['TerNombr'], 0, 0, 'L', false);
      $pdf->Cell(15);
      $pdf->Cell(30, 0,"$ ". number_format($this->informe['valor_salida'], 2), 0, 0, 'R', false);
      $pdf->Ln(4);
      $pdf->Cell(125, 0, "Doc Identidad: ". $this->informe['TerDocId'], 0, 0, 'L', false);
      $pdf->Ln(5);
		$pdf->Cell(190, 20, "", 1, 0, 'L', false);
      $pdf->Ln(0);
		$pdf->SetTextColor(255, 255, 255);
      $pdf->Cell(190, 5, 'CONCEPTO:', 0, 0, 'L', true);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(6);
      $pdf->Write(4, $this->informe['descripcion']);
      if (strlen($this->informe['descripcion']) < 140 ) {
         $pdf->Ln(14);
      } else {
         $pdf->Ln(10);
      }
		$pdf->Cell(190, 5, "", 1, 0, 'L', false);
      $pdf->Ln(0);
		$pdf->SetTextColor(255, 255, 255);
      $pdf->Cell(18, 5, 'CUENTA: ', 0, 0, 'L', true);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Cell(2);
      $pdf->Cell(170, 5, $this->informe['CueCodig'].' - '.$this->informe['CueNombr'], 0, 0, 'L', false);
      $pdf->Ln(7);
		$pdf->Cell(190, 18, "", 1, 0, 'L', false);
      $pdf->Ln(0);
		$pdf->SetTextColor(255, 255, 255);
      $pdf->Cell(190, 5, 'EL VALOR ES:', 0, 0, 'L', true);
      $pdf->Ln(6);
		$pdf->SetTextColor(0, 0, 0);
		$valletras = convertir($this->informe['valor_salida'], '1');
      $pdf->Write(4, strtoupper($valletras));
      $pdf->Ln(30);
		$pdf->SetTextColor(170, 170, 170);
      $pdf->Cell(80, 0, '______________________________________', 0, 0, 'L', false);
      $pdf->Cell(30);
      $pdf->Cell(80, 0, '______________________________________', 0, 0, 'L', false);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(5);
      $pdf->Cell(80, 0, 'Elaboró: ', 0, 0, 'L', false);
      $pdf->Cell(30);
      $pdf->Cell(80, 0, 'Recibí: ', 0, 0, 'L', false);
      $pdf->Ln(5);
      $pdf->Cell(80, 0, $this->informe['name'], 0, 0, 'L', false);
      $pdf->Cell(30);
      $pdf->Cell(80, 0, 'Doc Identidad: ', 0, 0, 'L', false);

      $pdf->Output('I', "VC-".$this->informe['consecutivo'].'.pdf', true);
   }


   //**************************************************************************************
   public function traerHojaCalculo() {
   }
}


$documento = new imprimirDocumento();
$documento->informe       = $informe;
$documento->id_movimiento = $id_movimiento;
$documento->token         = $token;
if ($reportData["GenHojCal"] == '1') {
	$documento->traerHojaCalculo();
} else $documento->traerImpresionDocumento();