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
$repFecPlanilla = $reportData['fecCambioSearchFrom'];
$informe = ChequesModel::placomisi($reportData);


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
		$this->Cell(160, 0, 'PLANILLA DE COMISIONES', 0, 0, 'L', false);
      $this->Cell(10);
		$this->Cell(20, 0, 'Página: ' . $this->PageNo() . '/{nb}', 0, 0, 'L');
      $this->Ln(4);
      $this->Cell(100, 0, 'Fecha Planilla: '.$this->repFecPlanilla, 0, 0, 'L');
      $this->Ln(5);
      $this->SetFillColor(43, 114, 171);
		$this->SetTextColor(255, 255, 255);
		$this->SetFont('Arial', '', 8);
		$this->Cell($this->w[0], 5, "Doc Ident", 0, 0, 'R', true);
		$this->Cell($this->w[1], 5, "Nombre Cliente", 0, 0, 'L', true);
		$this->Cell($this->w[2], 5, "Bco", 0, 0, 'L', true);
		$this->Cell($this->w[3], 5, "Cheque", 0, 0, 'L', true);
		$this->Cell($this->w[4], 5, "Dias", 0, 0, 'R', true);
		$this->Cell($this->w[5], 5, "%Comisión", 0, 0, 'R', true);
		$this->Cell($this->w[6], 5, "Vlr Cheque", 0, 0, 'R', true);
		$this->Cell($this->w[7], 5, "Comisión", 0, 0, 'R', true);
		$this->Cell($this->w[8], 5, "Vlr Entergado", 0, 0, 'R', true);
		$this->Cell($this->w[9], 5, "Compte", 0, 0, 'L', true);
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
	public $informe, $repFecPlanilla, $numero, $token, $tiempoRestante;
	public function traerImpresionDocumento() {
      $pdf = new PDF('P', 'mm', 'letter');
      $pdf->repFecPlanilla = $this->repFecPlanilla;
      $w = array(18, 55, 8, 13, 10, 17, 23, 21, 23, 15);
      $pdf->w = $w;
      $title = 'Planilla diaria de Comisiones';
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
         $pdf->Output('I', "PLA-COMISIONES".$this->repFecPlanilla.'.pdf', true);
         return;
      }
      $c_height = 3;
      $pdf->SetDrawColor(190, 190, 190);
      $pdf->SetFillColor(43, 114, 171);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetTextColor(0, 0, 0);
      $canCheques = count($this->informe);
      $ValCheques = 0;
      $valComisio = 0;
      $valImpBcos = 0;
      $ValEntrega = 0;
      foreach ($this->informe as $key => $item) {
         $vlrImptoBaco = $item["valor_cheque"] * $item["impuesto_banco"] / 1000;
         $x_axis = $pdf->getx();
         $pdf->vcell($w[0], $w[0], $c_height, $x_axis, $item["TerDocId"], 'R');
         $x_axis = $pdf->getx();
         $start_y = $pdf->GetY();
         $pdf->MultiCell($w[1], $c_height, $item["TerNombr"], 0, 'L');
         // $pdf->vcell(24, $w[1], $c_height, $x_axis, $item["pet_name"]." ".$item["first_name"]." ".$item["last_name"], 'L');
         $pdf->SetXY($x_axis + $w[1], $start_y);
         $x_axis = $pdf->getx();
         $pdf->vcell($w[2], $w[2], $c_height, $x_axis, $item["banco_codigo"], 'L');
         $x_axis = $pdf->getx();
         $pdf->vcell($w[3], $w[3], $c_height, $x_axis, $item["numero"], 'L');
         $x_axis = $pdf->getx();
         $pdf->vcell($w[4], $w[4], $c_height, $x_axis, $item["dias_cobrados"], 'R');
         $x_axis = $pdf->getx();
         $pdf->vcell($w[5], $w[5], $c_height, $x_axis, $item["porcentaje_comision"], 'R');
         $x_axis = $pdf->getx();
         $pdf->vcell($w[6], $w[6], $c_height, $x_axis, number_format($item["valor_cheque"], 0), 'R');
         $x_axis = $pdf->getx();
         $pdf->vcell($w[7], $w[7], $c_height, $x_axis, number_format($item["comision"], 0), 'R');
         $x_axis = $pdf->getx();
         $pdf->vcell($w[8], $w[8], $c_height, $x_axis, number_format($item["valor_cheque"] - $item["comision"] - $vlrImptoBaco, 0), 'R');
         $x_axis = $pdf->getx();
         $pdf->vcell($w[9], $w[9], $c_height, $x_axis, $item["id_cheque"], 'L');
         if (strlen(trim($item["TerNombr"])) > 0) {
            $len = ceil(strlen(trim($item["TerNombr"])) / 41) * 3;
         } else $len = 3;
         if ($len < 3) $len = 3;
         $pdf->Ln($len);
         $ValCheques += $item["valor_cheque"];
         $valComisio += $item["comision"];
         $valImpBcos += $vlrImptoBaco;
         $ValEntrega += $item["valor_cheque"] - $item["comision"] - $vlrImptoBaco;
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
      $pdf->Ln(3);
      $pdf->Cell(23, 0, 'Impto Banco:', 0, 0, 'L');
      $pdf->Cell(24, 0, number_format($valImpBcos, 0), 0, 0, 'R');
      $pdf->Ln(3);
      $pdf->Cell(23, 0, 'Valor Entregado:', 0, 0, 'L');
      $pdf->Cell(24, 0, number_format($ValEntrega, 0), 0, 0, 'R');

      $pdf->Output('I', "PLA-COMISIONES-".$this->repFecPlanilla.'.pdf', true);
      // $pdfData = $pdf->Output('S'); // 'S' para devolver como string
      // $pdfContent = ob_get_clean();

      // header('Content-Type: application/pdf');
      // header('Content-Disposition: inline; filename="PLA-COMISIONES'.$this->repFecPlanilla.'.pdf"');
      // // header('Content-Length: ' . strlen($pdfContent));
      
      // // Enviar el PDF
      // // echo $pdfContent;
      // echo $pdfData;

      // echo "<script>
      //    setTimeout(() => {
      //       window.close();
      //    }, " . ($this->tiempoRestante * 1) . ");
      // </script>";
      exit;

      /*
      echo "
      <html><body>
      <script>
         console.log('Programando cierre en " . ($this->tiempoRestante * 1000) . " ms');
         
         // Cerrar después del tiempo restante
         setTimeout(function() {
               console.log('Cerrando ventana...');
               if (window.opener && !window.opener.closed) {
                  window.close();
               } else {
                  // Fallback: intentar cerrar de todas formas
                  window.close();
               }
         }, " . ($this->tiempoRestante * 1000) . ");
         
         // Contador en consola
         let tiempo = " . $this->tiempoRestante . ";
         setInterval(function() {
               tiempo--;
               console.log('Tiempo restante: ' + tiempo + 's');
               if (tiempo <= 0) {
                  window.close();
               }
         }, 1000);
      </script>
      </body></html>";
      */

      /*
      $tiempoRestanteMs = $this->tiempoRestante * 1000;
      echo "<script>
         // Cerrar automáticamente
         const timerCierre = setTimeout(() => {
            console.log('Cerrando ventana por expiración');
            window.close();
         }, $tiempoRestanteMs);
         
         // Opcional: Prevenir que el usuario evite el cierre
         window.addEventListener('beforeunload', (e) => {
            // Si se intenta recargar/navegar, forzar cierre
            if (timerCierre) {
                  window.close();
            }
         });
         const contador = setInterval(() => {
            tiempoRestante--;
            document.title = 'Informe - Expira en: ' + tiempoRestante + 's';
            if (tiempoRestante <= 0) {
                  clearInterval(contador);
                  window.close();
            }
         }, 1000);
      </script>";
      */

   }


   //**************************************************************************************
   public function traerHojaCalculo() {
   }
}


$documento = new imprimirDocumento();
$documento->informe   = $informe;
$documento->repFecPlanilla = $repFecPlanilla;
$documento->token     = $token;
$documento->tiempoRestante = $tiempoRestante;
if ($reportData["GenHojCal"] == '1') {
	$documento->traerHojaCalculo();
} else $documento->traerImpresionDocumento();