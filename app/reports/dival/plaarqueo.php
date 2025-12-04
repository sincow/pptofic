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
$repFecPlanilla = $reportData['repFecPlanilla'];
$repValContado = $reportData['repValContado'];
$informe = ChequesModel::repplaarqueo($reportData);


//**************************************************************************************
class PDF extends FPDF{
	protected $B = 0;
	protected $I = 0;
	protected $U = 0;
	protected $HREF = '';
	protected $angle = 0;

   public $repFecPlanilla = '';
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
		$this->SetDrawColor(190, 190, 190);
		$this->SetFont('Arial', 'B', 12);
      $this->SetFillColor(43, 114, 171);
		$this->SetTextColor(255, 255, 255);
		$this->SetTextColor(0, 0, 0);
      $this->Cell(160, 0, isset($_SESSION['companyname']) ? $_SESSION["companyname"] : 'DEMOSTRACION' ,0, 0, 'L');
		$this->SetFont('Arial', '', 9);
      $this->Cell(10);
		$this->Cell(20, 0,'Fecha: ' . date('Y-m-d'), 0, 0, 'L');
      $this->Ln(4);
		$this->SetFont('Arial', '', 9);
		$this->Cell(160, 0, 'PLANILLA DE ARQUEO', 0, 0, 'L', false);
      $this->Cell(10);
		$this->Cell(20, 0, 'Página: ' . $this->PageNo() . '/{nb}', 0, 0, 'L');
      $this->Ln(4);
      $this->Cell(100, 0, 'Fecha Planilla: '.$this->repFecPlanilla, 0, 0, 'L');
      $this->Ln(5);
      $this->SetFillColor(43, 114, 171);
		$this->SetTextColor(255, 255, 255);
		$this->SetFont('Arial', '', 8);
		$this->Cell($this->w[0], 5, "Concepto", 0, 0, 'L', true);
		$this->Cell($this->w[1], 5, "Tercero", 0, 0, 'L', true);
		$this->Cell($this->w[2], 5, "Valor", 0, 0, 'R', true);
		$this->Cell($this->w[3], 5, "Compte", 0, 0, 'L', true);
		$this->Cell($this->w[4], 5, "Valor ", 0, 0, 'R', true);
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
	public $informe, $repFecPlanilla, $repValContado, $token;
	public function traerImpresionDocumento() {
      $pdf = new PDF('P', 'mm', 'letter');
      $pdf->repFecPlanilla = $this->repFecPlanilla;
      $w = array(70, 70, 23, 16, 23);
      $pdf->w = $w;
      $title = 'Planilla de Arqueo';
		$pdf->SetTitle($title,true);
		// $icon = "../views/img/favicons/favicon-32x32.png";
		$icon = "../../../assets/img/favicons/favicon.ico";
		$pdf->SetIcon($icon);
		$pdf->SetAuthor("Tincolsas", true);
		$pdf->SetAutoPageBreak(true, 0);
		$pdf->SetMargins(7, 14, 7);
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
         $pdf->Output('I', "PLA-ARQUEO".$this->repFecPlanilla.'.pdf', true);
         return;
      }
      $c_height = 3;
      $pdf->SetDrawColor(190, 190, 190);
      $pdf->SetFillColor(43, 114, 171);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetTextColor(0, 0, 0);
      $totConcep00 = 0;
      $totConcep11 = 0;
      $totValVales = 0;
      $this->repValContado = str_replace(',', '', $this->repValContado);
      //$this->repValContado = filter_var($$this->repValContado, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

      $x_axis = $pdf->getx();
      $start_y = $pdf->GetY();
      $pdf->MultiCell($w[0], $c_height, "EFECTIVO CONTADO", 0, 'L');
      $pdf->SetXY($x_axis + $w[0], $start_y);
      $x_axis = $pdf->getx();
      $start_y = $pdf->GetY();
      $pdf->MultiCell($w[1], $c_height, "", 0, 'L');
      $pdf->SetXY($x_axis + $w[1], $start_y);
      $x_axis = $pdf->getx();
      $pdf->vcell($w[2], $w[2], $c_height, $x_axis, number_format($this->repValContado,0), 'R');
      $x_axis = $pdf->getx();
      $pdf->vcell($w[3], $w[3], $c_height, $x_axis, "", 'L');
      $x_axis = $pdf->getx();
      // $pdf->vcell($w[4], $w[4], $c_height, $x_axis, 0, 'R');
      $x_axis = $pdf->getx();
      $pdf->Ln(9);
      $totConcep00 += $this->repValContado;

      if ($this->informe["VC"]) {
         foreach ($this->informe["VC"] as $key => $item) {
            $x_axis = $pdf->getx();
            $start_y = $pdf->GetY();
            $pdf->MultiCell($w[0], $c_height, strtoupper($item["CtoNombr"]), 0, 'L');
            $pdf->SetXY($x_axis + $w[0], $start_y);
            $x_axis = $pdf->getx();
            $start_y = $pdf->GetY();
            $pdf->MultiCell($w[1], $c_height, $item["TerNombr"], 0, 'L');
            $pdf->SetXY($x_axis + $w[1], $start_y);
            $x_axis = $pdf->getx();
            
            $pdf->vcell($w[2], $w[2], $c_height, $x_axis, number_format($item["CtoValor"],0), 'R');
            $x_axis = $pdf->getx();
            $pdf->vcell($w[3], $w[3], $c_height, $x_axis, $item["numero"], 'L');
            $x_axis = $pdf->getx();
            // $pdf->vcell($w[4], $w[4], $c_height, $x_axis, 0, 'R');
            $lenCto = 3;
            if (strlen(trim($item["CtoNombr"])) > 0) {
               $lenCto = ceil(strlen(trim($item["CtoNombr"])) / 60) * 4;
            }
            $lenTer = 3;
            if (strlen(trim($item["TerNombr"])) > 0) {
               $lenTer = ceil(strlen(trim($item["TerNombr"])) / 60) * 4;
            }
            $len = $lenTer;
            if ($lenCto > $lenTer) {
               $len = $lenCto;
            }
            if ($len < 4) $len = 4;
            $pdf->Ln($len);
            $totValVales += $item["CtoValor"];
         }
      }
      $x_axis = $pdf->getx();
      $Pos = $w[0] + $w[1] + $x_axis;
      $pdf->Cell($Pos - $x_axis);
      $pdf->Cell($w[2], 0, "", 1, 0, 'R', true);
      $pdf->Ln(2);

      $x_axis = $pdf->getx();
      $pdf->vcell($w[0], $w[0], $c_height, $x_axis, "TOTAL VALES", 'L');
      $x_axis = $pdf->getx();
      $pdf->vcell($w[1], $w[1], $c_height, $x_axis, "", 'L');
      $x_axis = $pdf->getx();
      $pdf->vcell($w[2], $w[2], $c_height, $Pos, number_format($totValVales,0), 'R');
      $totConcep00 += $totValVales;

      $pdf->SetFont('Arial', '', 8);
      $pdf->Ln(9);
      $x_axis = $pdf->getx();
      $start_y = $pdf->GetY();
      $pdf->MultiCell($w[0], $c_height, "CHEQUES LOCALES", 0, 'L');
      $pdf->SetXY($x_axis + $w[0], $start_y);
      $x_axis = $pdf->getx();
      $start_y = $pdf->GetY();
      $pdf->MultiCell($w[1], $c_height, "", 0, 'L');
      $pdf->SetXY($x_axis + $w[1], $start_y);
      $x_axis = $pdf->getx();
      $pdf->vcell($w[2], $w[2], $c_height, $x_axis, number_format($this->informe['DO'][0]['CheValor'],0), 'R');
      $x_axis = $pdf->getx();
      // $pdf->vcell($w[3], $w[3], $c_height, $x_axis, "02", 'L');
      $x_axis = $pdf->getx();
      $pdf->Ln(9);
      $totConcep00 += $this->informe['DO'][0]['CheValor'];

      $x_axis = $pdf->getx();
      $start_y = $pdf->GetY();
      $pdf->MultiCell($w[0], $c_height, "EFECTIVO RECIBIDO", 0, 'L');
      $pdf->SetXY($x_axis + $w[0], $start_y);
      $x_axis = $pdf->getx();
      $start_y = $pdf->GetY();
      $pdf->MultiCell($w[1], $c_height, "", 0, 'L');
      $pdf->SetXY($x_axis + $w[1], $start_y);
      $x_axis = $pdf->getx();
      // $pdf->vcell($w[2], $w[2], $c_height, $x_axis, 0, 'R');
      $pdf->vcell($w[2], $w[2], $c_height, $x_axis, "", 'R');
      $x_axis = $pdf->getx();
      // $pdf->vcell($w[3], $w[3], $c_height, $x_axis, "11", 'L');
      $pdf->vcell($w[3], $w[3], $c_height, $x_axis, "", 'L');
      $x_axis = $pdf->getx();
      $pdf->vcell($w[4], $w[4], $c_height, $x_axis, number_format($this->informe['PE'][0]['ValorSaldo'] ,0), 'R');
      $x_axis = $pdf->getx();
      $pdf->Ln(9);
      $totConcep11 += $this->informe['PE'][0]['ValorSaldo'];

      $x_axis = $pdf->getx();
      $start_y = $pdf->GetY();
      $pdf->MultiCell($w[0], $c_height, "COMISIONES+IMP BANCARIO", 0, 'L');
      $pdf->SetXY($x_axis + $w[0], $start_y);
      $x_axis = $pdf->getx();
      $start_y = $pdf->GetY();
      $pdf->MultiCell($w[1], $c_height, "", 0, 'L');
      $pdf->SetXY($x_axis + $w[1], $start_y);
      $x_axis = $pdf->getx();
      // $pdf->vcell($w[2], $w[2], $c_height, $x_axis, 0, 'R');
      $pdf->vcell($w[2], $w[2], $c_height, $x_axis, "", 'R');
      $x_axis = $pdf->getx();
      // $pdf->vcell($w[3], $w[3], $c_height, $x_axis, "12", 'L');
      $pdf->vcell($w[3], $w[3], $c_height, $x_axis, "", 'L');
      $x_axis = $pdf->getx();
      $pdf->vcell($w[4], $w[4], $c_height, $x_axis, number_format($this->informe['DO'][0]['CheComis'] + $this->informe['DO'][0]['CheImpBa'] ,0), 'R');
      $x_axis = $pdf->getx();
      $totConcep11 += $this->informe['DO'][0]['CheComis'] + $this->informe['DO'][0]['CheImpBa'];

      $pdf->Ln(6);
      $pdf->SetFont('Arial', 'B', 8);
      $x_axis = $pdf->getx();
      $Pos = $w[0] + $w[1] + $x_axis;
      $pdf->Cell($Pos - $x_axis);
      $pdf->Cell($w[2], 0, "", 1, 0, 'R', true);
      $pdf->Cell(16);
      $pdf->Cell($w[4], 0, "", 1, 0, 'R', true);
      $pdf->Ln(3);
      // $pdf->vcell($w[3], $w[3], $c_height, $Pos, number_format($totConcepto,0), 'R');
      // $totGeneral += $totConcepto;
      // $pdf->Ln(6);
      $x_axis = $pdf->getx();
      $Pos = $w[0] + $w[1] + $x_axis;
      $pdf->vcell($w[1], $w[1], $c_height, $Pos - $w[1], 'TOTALES =====>  ', 'R');
      $x_axis = $pdf->getx();
      $pdf->vcell($w[2], $w[2], $c_height, $x_axis, number_format($totConcep00,0), 'R');
      $x_axis = $pdf->getx();
      $pdf->vcell($w[3], $w[3], $c_height, $x_axis, "", 'L');
      $x_axis = $pdf->getx();
      $pdf->vcell($w[4], $w[4], $c_height, $x_axis, number_format($totConcep11,0), 'R');
      $pdf->Ln(6);
      if ($totConcep00 > $totConcep11) {
         $x_axis = $pdf->getx();
         $Pos = $w[0] + $w[1] + $x_axis;
         $pdf->Cell(25, 0,'SOBRANTE DE  ', 0, 0, 'L');
         $pdf->Cell(23, 0, number_format($totConcep00 - $totConcep11,0), 0, 0, 'L');
      }
      if ($totConcep00 < $totConcep11) {
         $x_axis = $pdf->getx();
         $Pos = $w[0] + $w[1] + $x_axis;
         $pdf->Cell(25, 0,'FALTANTE DE  ', 0, 0, 'L');
         $pdf->Cell(23, 0, number_format($totConcep11 - $totConcep00, 0), 0, 0, 'L');
      }

      $pdf->Output('I', "PLA-ARQUEO-".$this->repFecPlanilla.'.pdf', true);
      exit;
   }


   //**************************************************************************************
   public function traerHojaCalculo() {
   }
}


$documento = new imprimirDocumento();
$documento->informe   = $informe;
$documento->repFecPlanilla = $repFecPlanilla;
$documento->repValContado = $repValContado;
$documento->token     = $token;
if ($reportData["GenHojCal"] == '1') {
	$documento->traerHojaCalculo();
} else $documento->traerImpresionDocumento();