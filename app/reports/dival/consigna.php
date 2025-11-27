<?php
if (!isset($_SESSION)) {
	session_start();
}
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
$idConsigna = $reportData['idConsigna'];
$informe = ConsignaModel::getConsignacion($reportData);


//**************************************************************************************
class PDF extends FPDF{
	protected $B = 0;
	protected $I = 0;
	protected $U = 0;
	protected $HREF = '';
	protected $angle = 0;

   public $repFecConsig = '';
   public $idConsigna = '';
   public $BanCodig = '';
   public $BanNombr = '';
   public $w = '';
	public $fecha = '';

   //**************************************************************************************
   function Header() {
      // $this->Image('../resources/img/logo.png', 10, 8, 33);
      // $this->SetFont('Arial', 'B', 15);
		$this->SetDrawColor(190, 190, 190);
		$this->SetFont('Arial', 'B', 12);
      $this->SetFillColor(43, 114, 171);
		$this->SetTextColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
      $this->Cell(150, 0, isset($_SESSION['companyname']) ? $_SESSION["companyname"] : 'DEMOSTRACION' ,0, 0, 'L');
		$this->SetFont('Arial', '', 9);
      $this->Cell(6);
		$this->Cell(20, 0,'Fecha: ' . date('Y-m-d'), 0, 0, 'L');
      $this->Ln(4);
		$this->SetFont('Arial', '', 9);
		$this->Cell(150, 0, 'CHEQUES CONSIGNADOS', 0, 0, 'L', false);
      $this->Cell(6);
		$this->Cell(20, 0, 'Página: ' . $this->PageNo() . '/{nb}', 0, 0, 'L');
      $this->Ln(4);
      $this->Cell(55, 0, 'Fecha Consignación: '.$this->repFecConsig, 0, 0, 'L');
      $this->Cell(50, 0, 'Compte Consignación: '.$this->idConsigna, 0, 0, 'L');
      $this->Ln(4);
      $this->Cell(170, 0, 'Para Consignar en: '.$this->BanCodig." ".$this->BanNombr, 0, 0, 'L');
      $this->Ln(5);
      $this->SetFillColor(43, 114, 171);
		$this->SetTextColor(255, 255, 255);
		$this->SetFont('Arial', '', 8);
		$this->Cell($this->w[0], 5, "Compte", 0, 0, 'L', true);
		$this->Cell($this->w[1], 5, "Doc Identidad", 0, 0, 'R', true);
		$this->Cell($this->w[2], 5, "Nombre Cliente", 0, 0, 'L', true);
		$this->Cell($this->w[3], 5, "Cheque", 0, 0, 'L', true);
		$this->Cell($this->w[4], 5, "Bco", 0, 0, 'L', true);
		$this->Cell($this->w[5], 5, "Vlr Cheque", 0, 0, 'R', true);
		$this->Cell($this->w[6], 5, "Comisión", 0, 0, 'R', true);
      $this->SetTextColor(0, 0, 0);
		$this->Ln(6);
		$this->SetFont('Arial', '', 8);
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
	public $informe, $idConsigna, $token;
	public function traerImpresionDocumento() {
      $pdf = new PDF('P', 'mm', 'letter');
      $pdf->idConsigna = $this->idConsigna;
      $w = array(18, 18, 80, 18, 8, 23, 23);
      $pdf->w = $w;
      $title = 'Cheques Consignados';
		$pdf->SetTitle($title,true);
		// $icon = "../views/img/favicons/favicon-32x32.png";
		$icon = "../../../assets/img/favicons/favicon.ico";
		$pdf->SetIcon($icon);
		$pdf->SetAuthor("Tincolsas", true);
		$pdf->SetAutoPageBreak(true, 0);
		$pdf->SetMargins(13, 14, 10);
		$pdf->AliasNbPages();
      if ($this->informe == null) {
         $pdf->AddPage();
			$pdf->SetFont('Arial', 'B', 35);
			$pdf->SetTextColor(203, 203, 203);
			$pdf->Rotate(45, 55, 230);
			$pdf->Text(115, 200, 'Registro no encontrado');
			$pdf->Rotate(0);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
         $pdf->Output('I', "CHE-CONSIGNADOS".$this->idConsigna.'.pdf', true);
         return;
      }
      $pdf->repFecConsig = $this->informe[0]["fecha"];
      $repFecConsig = $this->informe[0]["fecha"];
      $pdf->idConsigna = $this->informe[0]["id_consigna"];
      $pdf->BanCodig = $this->informe[0]["BanCodig"];
      $pdf->BanNombr = $this->informe[0]["BanNombr"];
      $pdf->AddPage();
      $c_height = 3;
      // $pdf->SetDrawColor(190, 190, 190);
      // $pdf->SetFillColor(43, 114, 171);
		// $pdf->SetTextColor(255, 255, 255);
		$pdf->SetTextColor(0, 0, 0);
      $canCheques = count($this->informe);
      $ValCheques = 0;
      $valComisio = 0;
      foreach ($this->informe as $key => $item) {
         $vlrImptoBaco = $item["valor_cheque"] * $item["impuesto_banco"] / 1000;
         $x_axis = $pdf->getx();
         $pdf->vcell($w[0], $w[0], $c_height, $x_axis, $item["consecutivo_cheque"], 'L');
         $x_axis = $pdf->getx();
         $pdf->vcell($w[1], $w[1], $c_height, $x_axis, $item["TerDocId"], 'R');
         $x_axis = $pdf->getx();
         $start_y = $pdf->GetY();
         $pdf->MultiCell($w[2], $c_height, $item["TerNombr"], 0, 'L');
         $pdf->SetXY($x_axis + $w[2], $start_y);
         $x_axis = $pdf->getx();
         $pdf->vcell($w[3], $w[3], $c_height, $x_axis, $item["numero"], 'L');
         $x_axis = $pdf->getx();
         $pdf->vcell($w[4], $w[4], $c_height, $x_axis, $item["banco_codigo"], 'L');
         $x_axis = $pdf->getx();
         $pdf->vcell($w[5], $w[5], $c_height, $x_axis, number_format($item["valor_cheque"], 0), 'R');
         $x_axis = $pdf->getx();
         $pdf->vcell($w[6], $w[6], $c_height, $x_axis, number_format($item["comision"], 0), 'R');
         if (strlen(trim($item["TerNombr"])) > 0) {
            $len = ceil(strlen(trim($item["TerNombr"])) / 41) * 3;
         } else $len = 3;
         if ($len < 3) $len = 3;
         $pdf->Ln($len);
         $ValCheques += $item["valor_cheque"];
         $valComisio += $item["comision"];
      }
      $pdf->Ln(6);
      $pdf->Cell(23, 0, 'Cantidad Cheques:', 0, 0, 'L');
      $pdf->Cell(24, 0, number_format($canCheques, 0), 0, 0, 'R');
      $pdf->Ln(3);
      $pdf->Cell(23, 0, 'Valor Cheques:', 0, 0, 'L');
      $pdf->Cell(24, 0, number_format($ValCheques, 0), 0, 0, 'R');
      $pdf->Ln(3);
      $pdf->Cell(23, 0, 'Comisiones:', 0, 0, 'L');
      $pdf->Cell(24, 0, number_format($valComisio, 0), 0, 0, 'R');
      $pdf->Output('I', "PLA-COMISIONES-".$repFecConsig.'.pdf', true);
      exit;
   }


   //**************************************************************************************
   public function traerHojaCalculo() {
   }
}


$documento = new imprimirDocumento();
$documento->informe   = $informe;
$documento->idConsigna = $idConsigna;
$documento->token     = $token;
if ($reportData["GenHojCal"] == '1') {
	$documento->traerHojaCalculo();
} else $documento->traerImpresionDocumento();