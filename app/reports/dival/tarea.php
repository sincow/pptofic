<?php
if (!isset($_SESSION)) {
	session_start();
}
$_SESSION['reportPath'] = '../../';
require_once "../../models/numaletras.php";
require_once "../../models/dival/mdltareas.php";
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
if (time() - $reportData['timestamp'] > 9585) {
   unset($_SESSION['report_temp_' . $token]);
   echo "<script>
      alert('Token inválido');
      window.close();
   </script>";
   exit;
}

// Obtener datos para el informe
$id_notifi = $reportData['id_notifi'];
$informe = TareasModel::getOne($reportData);


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
	public $informe, $id_notifi, $token;
	public function traerImpresionDocumento() {
      $pdf = new PDF('P', 'mm', 'medcar');
      $title = 'Tarea';
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
         $pdf->Output('I', "TAREA-".$this->id_notifi.'.pdf', true);
         return;
      }

      $pdf->SetDrawColor(190, 190, 190);
		$pdf->SetFont('Arial', 'B', 12);
      $pdf->SetFillColor(43, 114, 171);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Cell(190, 5, $_SESSION['companyname'], 0, 0, 'L', false);
      $pdf->Ln(5);
		$pdf->SetFont('Arial', '', 9);
      $pdf->Cell(40, 5, 'ASIGNACION DE TAREA', 0, 0, 'L', false);
      $pdf->Ln(0);
      $pdf->Cell(135);
		$pdf->Cell(55, 6, "", 1, 0, 'L', false);
      $pdf->Ln(0);
      $pdf->Cell(135);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 10);
      $pdf->Cell(33, 6, 'NRO ASIGNACION', 0, 0, 'L', true);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(0);
      $pdf->Cell(175);
		$pdf->Cell(12, 6, str_pad($this->informe['numero'], 6, "0", STR_PAD_LEFT), 0, 0, 'R', false);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);
      // $pdf->Ln(6);
      // $pdf->Cell(155);
		// $pdf->Cell(35, 5, "", 1, 0, 'L', false);
      // $pdf->Ln(0);
      // $pdf->Cell(188, 5, 'Fecha: '.$this->informe['fecha'], 0, 0, 'R', false);

      $pdf->Ln(10);
		$pdf->Cell(110, 12, "", 1, 0, 'L', false);
      $pdf->Cell(1);
		$pdf->Cell(79, 12, "", 1, 0, 'L', false);
      $pdf->Ln(0);
		$pdf->SetTextColor(255, 255, 255);
      $pdf->Cell(110, 5, 'ASIGNADO A:', 0, 0, 'L', true);
      $pdf->Cell(1);
      $pdf->Cell(30, 5, 'FEC ASIGNACION', 0, 0, 'L', true);
      // $pdf->Cell(5);
      $pdf->Cell(29, 5, 'FEC ENTREGA', 0, 0, 'L', true);
      // $pdf->Cell(5);
      $pdf->Cell(20, 5, 'PRIORIDAD', 0, 0, 'L', true);


		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(8);
      $pdf->Cell(110, 0, $this->informe['name'], 0, 0, 'L', false);
      $pdf->Cell(1);
      $pdf->Cell(30, 0, $this->informe['fecha'], 0, 0, 'L', false);
      // $pdf->Cell(5);
      $pdf->Cell(29, 0, $this->informe['fecha_entrega'], 0, 0, 'L', false);

      switch ($this->informe['prioridad']) {
         case '1':
            $pdf->Cell(20, 0, 'BAJA', 0, 0, 'L', false);
            break;
         case '2':
            $pdf->Cell(20, 0, 'MEDIA', 0, 0, 'L', false);
            break;
         case '3':
            $pdf->Cell(20, 0, 'ALTA', 0, 0, 'L', false);
            break;
         default:
            # code...
            break;
      }
      $pdf->Ln(6);

      $pdf->Cell(190, 12, "", 1, 0, 'L', false);
      $pdf->Ln(0);
		$pdf->SetTextColor(255, 255, 255);
      $pdf->Cell(190, 5, 'TITULO', 0, 0, 'L', true);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(8);
      $pdf->Cell(125, 0, $this->informe['titulo'], 0, 0, 'L', false);
      $pdf->Ln(6);

      $lineas = ceil(strlen($this->informe['detalle']) / 140);
      if ($lineas < 10) {
         $alto = 40;
      } else {
         $alto = $lineas * 4;
      }
      $pdf->Cell(190, $alto, "", 1, 0, 'L', false);
      $pdf->Ln(0);
		$pdf->SetTextColor(255, 255, 255);
      $pdf->Cell(190, 5, 'CONCEPTO', 0, 0, 'L', true);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(6);
      $pdf->Write(4, $this->informe['detalle']);
      // if (strlen($this->informe['detalle']) < 140 ) {
      //    $pdf->Ln(14);
      // } else {
      //    $pdf->Ln(10);
      // }
      if ($lineas < 10) {
         $pdf->Ln(40);
      } else {
         $pdf->Ln($alto);
      }
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetTextColor(170, 170, 170);
      $pdf->Cell(80, 0, '______________________________________', 0, 0, 'L', false);
      $pdf->Cell(30);
      $pdf->Cell(80, 0, '______________________________________', 0, 0, 'L', false);
		$pdf->SetTextColor(0, 0, 0);
      $pdf->Ln(5);
      $pdf->Cell(80, 0, 'Elaboró: ', 0, 0, 'L', false);
      $pdf->Cell(30);
      $pdf->Cell(80, 0, 'Recibí: ', 0, 0, 'L', false);
      $pdf->Ln(4);
		$pdf->SetFont('Arial', '', 7);
      $pdf->Cell(100, 0, $this->informe['user_name'], 0, 0, 'L', false);

      $pdf->Output('I', "TAREA-".$this->informe['consecutivo'].'.pdf', true);
   }


   //**************************************************************************************
   public function traerHojaCalculo() {
   }
}


$documento = new imprimirDocumento();
$documento->informe   = $informe;
$documento->id_notifi = $id_notifi;
$documento->token     = $token;
if ($reportData["GenHojCal"] == '1') {
	$documento->traerHojaCalculo();
} else $documento->traerImpresionDocumento();