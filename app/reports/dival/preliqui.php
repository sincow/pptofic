
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

if (time() - $reportData['timestamp'] > 5850) {
   unset($_SESSION['report_temp_' . $token]);
   echo "<script>
      alert('Token inválido');
      window.close();
   </script>";
   exit;
}


// Obtener datos para el informe
$id_dvcliente = $reportData['id_dvcliente'];
$repFecPreliq = $reportData['repFecPreliq'];
$diasHabiles = $reportData['diasHabiles'];
$mostrarEstCta = $reportData['mostrarEstCta'];
$obserPreliq = $reportData['obserPreliq'];
$documPreliqList = $reportData['documPreliqList'];
$diasFestivos = $reportData['diasFestivos'];
$GenHojCal = $reportData['GenHojCal'];
$informe = ChequesModel::reppreliqui($reportData);


//**************************************************************************************
class PDF extends FPDF{
	protected $B = 0;
	protected $I = 0;
	protected $U = 0;
	protected $HREF = '';
	protected $angle = 0;

   public $TerDocId = '';
   public $TerNombr = '';
   public $repFecPreliq = '';
   public $mostrarEstCta = '';
   public $titulo = '';
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
      // $this->Cell(160, 0, isset($_SESSION["companyname"]) ? $_SESSION["companyname"] : 'DEMOSTRACION' ,0, 0, 'L');
		// $this->SetFont('Arial', '', 9);
      // $this->Cell(10);
		// $this->Cell(20, 0,'Fecha: ' . date('Y-m-d'), 0, 0, 'L');
      // $this->Ln(4);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(145, 0, 'PRELIQUIDACIÓN DE DOCUMENTOS', 0, 0, 'L', false);
		$this->SetFont('Arial', '', 9);
      $this->Cell(10);
		$this->Cell(20, 0,'Fecha: ' . date('Y-m-d'), 0, 0, 'L');
      $this->Ln(4);
      $this->Cell(155);
		$this->Cell(20, 0, 'Página: ' . $this->PageNo() . '/{nb}', 0, 0, 'L');
      $this->Ln(6);
		$this->SetFont('Arial', '', 10);
      $this->Cell(36, 0, 'Cliente: '.$this->TerDocId.'88', 0, 0, 'L');
		$this->SetFont('Arial', 'B', 10);
      $this->Cell(140, 0, $this->TerNombr, 0, 0, 'L');
		$this->SetFont('Arial', '', 9);
      $this->Ln(5);
      if ($this->mostrarEstCta == 1 && $this->titulo == '1') {
         $this->SetFillColor(43, 114, 171);
         $this->SetTextColor(255, 255, 255);
         $this->SetFont('Arial', '', 9);
         $this->Cell($this->w[0], 5, "Bco", 0, 0, 'L', true);
         $this->Cell($this->w[1], 5, "Cheque", 0, 0, 'L', true);
         $this->Cell($this->w[2], 5, "Fec Cambio", 0, 0, 'L', true);
         $this->Cell($this->w[3], 5, "Vencimiento", 0, 0, 'L', true);
         $this->Cell($this->w[4], 5, "Vlr Cheque", 0, 0, 'R', true);
         $this->Cell($this->w[5], 5, "Cap Pagado", 0, 0, 'R', true);
         $this->Cell($this->w[6], 5, "Saldo Pte", 0, 0, 'R', true);
         $this->Cell($this->w[7], 5, "Comis Pte", 0, 0, 'R', true);
         $this->SetTextColor(0, 0, 0);
         $this->Ln(6);
         $this->SetFont('Arial', '', 9);
      }
   }


   //**********************************************************************************
	function Footer() {
		$this->SetY(-25);
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
	public $informe, $id_dvcliente, $repFecPreliq, $diasHabiles, $mostrarEstCta, $obserPreliq, $documPreliqList, $diasFestivos, $token;
   public function traerImpresionDocumento() {
      function dibujarFlecha($pdf, $x, $y, $ancho = 40, $alto = 12) {
         $pdf->SetLineWidth(0.3);
         // Cuerpo de la flecha
         $cuerpoAncho = $ancho * 0.7;
         $pdf->Rect($x, $y + $alto/2.5, $cuerpoAncho, $alto/5);
         // Dibujar punta triangular
         $pdf->Line($x + $cuerpoAncho, $y + $alto/3, $x + $ancho, $y + $alto/2);
         $pdf->Line($x + $ancho, $y + $alto/2, $x + $cuerpoAncho, $y + 2*$alto/3);
         $pdf->Line($x + $cuerpoAncho, $y + $alto/3, $x + $cuerpoAncho, $y + 2*$alto/3);
      }

      $pdf = new PDF('P', 'mm', 'letter');
      $pdf->TerDocId = $this->id_dvcliente;
      $w = array(15, 20, 20, 20, 26, 26, 26, 26);
      $pdf->w = $w;
      $title = 'Preliquidación de Documentos';
		$pdf->SetTitle($title,true);
		// $icon = "../views/img/favicons/favicon-32x32.png";
		$icon = "../../../assets/img/favicons/favicon.ico";
		$pdf->SetIcon($icon);
		$pdf->SetAuthor("Tincolsas", true);
		$pdf->SetAutoPageBreak(true, 0);
		$pdf->SetMargins(15, 14, 12);
		$pdf->AliasNbPages();
      if ($this->informe == null) {
         $pdf->AddPage();
			$pdf->SetFont('Arial', 'B', 35);
			$pdf->SetTextColor(203, 203, 203);
			$pdf->Rotate(45, 55, 230);
			$pdf->Text(100, 190, 'Registro no encontrado');
			$pdf->Rotate(0);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial', '', 8);
         $pdf->Output('I', "PRELIQUIDACION-".$this->id_dvcliente.'.pdf', true);
         return;
      }

      $pdf->titulo = "1";
      $pdf->mostrarEstCta = $this->mostrarEstCta;
      $pdf->TerDocId = $this->informe[0]["TerDocId"];
      $pdf->TerNombr = $this->informe[0]["TerNombr"];
      $pdf->repFecPreliq = $this->repFecPreliq;
      $pdf->AddPage();
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
      $valMaximo = 0;
      $totInteres = 0;
      $totSdoPend = 0;
      foreach ($this->informe as $key => $item) {
         $vlrImptoBaco = $item["valor_cheque"] * $item["impuesto_banco"] / 1000;
         $intPendiente = $item['intereses_cobrados'] - $item['intereses_pagados'];
         $totSdoPend = $totSdoPend + $item['valor_cheque'] - $item['capital_pagado'];
         $totInteres = $totInteres + $intPendiente;
         if ($item['valor_cheque'] > $valMaximo ) {
            $valMaximo = $item['valor_cheque'];
            $datetime1 = new DateTime($item['UltVenci']);
            $datetime2 = new DateTime($item['fecha']);
            $diferencia = $datetime1->diff($datetime2);
            $diaMaximo = $diferencia->days;
         }
         if ($this->mostrarEstCta == 1) {
            $x_axis = $pdf->getx();
            $pdf->vcell($w[0], $w[0], $c_height, $x_axis, $item["banco_codigo"], 'L');
            $x_axis = $pdf->getx();
            $pdf->vcell($w[1], $w[1], $c_height, $x_axis, $item["numero"], 'L');
            $x_axis = $pdf->getx();
            $pdf->vcell($w[2], $w[2], $c_height, $x_axis, $item["fecha"], 'L');
            $x_axis = $pdf->getx();
            $pdf->vcell($w[3], $w[3], $c_height, $x_axis, $item["UltVenci"], 'L');
            $x_axis = $pdf->getx();
            $pdf->vcell($w[4], $w[4], $c_height, $x_axis, number_format($item["valor_cheque"], 0), 'R');
            $x_axis = $pdf->getx();
            $pdf->vcell($w[5], $w[5], $c_height, $x_axis, number_format($item["capital_pagado"], 0), 'R');
            $x_axis = $pdf->getx();
            $pdf->vcell($w[6], $w[6], $c_height, $x_axis, number_format($item["valor_cheque"] - $item["capital_pagado"], 0), 'R');
            $x_axis = $pdf->getx();
            $pdf->vcell($w[7], $w[7], $c_height, $x_axis, number_format($intPendiente, 0), 'R');
            $len = 3;
            $pdf->Ln($len);
            $ValCheques += $item["valor_cheque"];
            $valComisio += $item["comision"];
            $valImpBcos += $vlrImptoBaco;
            $ValEntrega += $item["valor_cheque"] - $item["comision"] - $vlrImptoBaco;
         }
      }
      if ($this->mostrarEstCta == 1) {
         $pdf->Ln(1);
         $pdf->Cell(180, 0, "", 1, 0, 'L', true);
         $pdf->SetFont('Arial', 'B', 9);
         $pdf->Ln(5);
         $pdf->Cell(35);
         $pdf->Cell(16, 0, 'Total Cliente:', 0, 0, 'L');
         $pdf->Cell(20, 0, number_format($canCheques, 0), 0, 0, 'R');
         $pdf->Cell(24, 0,"Documentos", 0, 0, 'L');
         $pdf->Cell(34);
         $pdf->Cell(24, 0, number_format($totSdoPend, 0), 0, 0, 'R');
         $pdf->Cell(2);
         $pdf->Cell(24, 0, number_format($totInteres, 0), 0, 0, 'R');
         $pdf->Ln(3);
         $pdf->Cell(180, 0, "", 1, 0, 'L', true);
         $pdf->Ln(3);
      }
      $pdf->Ln(9);
      $pdf->SetFont('Arial', 'B', 10);
      $pdf->Cell(140, 0, '*** PRELIQUIDACION ***', 0, 0, 'C');
      $pdf->SetFont('Arial', '', 10);
      $pdf->Ln(6);

      $documentsList = json_decode($this->documPreliqList, true);
      // $soloFechas = array_column($this->diasFestivos, 1); // Segunda columna
      $soloFechas = array_column($this->diasFestivos, 'FecFesti'); // Por nombre de columna

      $tipDias = 'DC';
      $totaPagar = 0;
      $pdf->titulo = "0";
      foreach ($documentsList as $key => $element) {
         foreach ($this->informe as $key => $item) {
            if ($element["id_cheque"] == $item["id_cheque"]) {
               $UltVenci = new DateTime($item['UltVenci']);
               $fecha = new DateTime($this->repFecPreliq);
               $diferencia = $fecha->diff($UltVenci);
               $dias = $diferencia->days;
               $finesDeSemana = 0;
               $festivosEnRango = 0;
               $fechaTemp = $UltVenci;
               for ($i = 0; $i < $dias; $i++) {
                  // Verificar fin de semana (0 = domingo, 6 = sábado)
                  $diaSemana = (int)$fechaTemp->format('w');
                  if ($diaSemana === 0 || $diaSemana === 6) {
                     $finesDeSemana++;
                  } else {
                     // Verificar si es festivo (solo días laborables)
                     $fechaStr = $fechaTemp->format('Y-m-d');
                     if (in_array($fechaStr, $soloFechas)) {
                        $festivosEnRango++;
                     }
                  }
                  $fechaTemp->modify('+1 day');
               }
               if ($this->diasHabiles == 0) {
                  $tipDias = 'DH';
                  $dias = $dias - $finesDeSemana - $festivosEnRango;
               }
               $valNuevoInt = ($item["valor_cheque"] - $item["capital_pagado"]) * $item["porcentaje_comision"] / 100 * $dias;
               $valPagar = ($item["valor_cheque"] - $item["capital_pagado"]) + ($item['intereses_cobrados'] - $item['intereses_pagados']) + $valNuevoInt;
               $pdf->Cell(28, 0, 'Liquidado desde:', 0, 0, 'L');
               $pdf->Cell(18, 0, $item["UltVenci"], 0, 0, 'L');
               $pdf->Cell(10);
               $pdf->Cell(12, 0, 'Hasta:', 0, 0, 'L');
               $pdf->Cell(24, 0, $this->repFecPreliq, 0, 0, 'L');
               $pdf->Cell(24, 0, $tipDias.$dias, 0, 0, 'L');
               $pdf->Ln(4);
               $pdf->SetFont('Arial', 'B', 10);
               $pdf->Cell(23, 0, 'Documento:', 0, 0, 'L');
               $pdf->Cell(24, 0, $item["numero"], 0, 0, 'L');
               $pdf->SetFont('Arial', '', 10);
               $pdf->Ln(4);
               $pdf->Cell(110, 0, 'Comisión Liquidación:', 0, 0, 'L');
               $pdf->Cell(24, 0, "$".number_format($valNuevoInt, 0), 0, 0, 'R' , false);
               $pdf->Ln(4);
               $pdf->Cell(110, 0, 'Valor Nómina:', 0, 0, 'L');
               $pdf->Cell(24, 0, "$".number_format($item["valor_cheque"] - $item["capital_pagado"], 0), 0, 0, 'R');
               $pdf->Ln(3);
               $pdf->SetFont('Arial', '', 10);
               $pdf->SetTextColor(255, 255, 255);
               $pdf->Cell(110, 5, 'TOTAL A PAGAR DOCUMENTO '.$item["numero"]."-".str_replace('.', '', $item["porcentaje_comision"]), 0, 0, 'L', true);
               $pdf->Cell(24, 5, "$".number_format($valPagar, 0), 0, 0, 'R' , true);
               $pdf->SetFont('Arial', '', 10);
               $pdf->SetTextColor(0, 0, 0);
               $pdf->Ln(14);
               $totaPagar = $totaPagar + $valPagar;
            }
         }
      }
      $pdf->Ln(0);
      $pdf->SetFont('Arial', 'B', 11);
      $pdf->Cell(10);
		$pdf->Cell(75, 10, "", 1, 0, 'L', false);
      $pdf->Cell(25);
		$pdf->Cell(40, 10, "", 1, 0, 'L', false);
      $y_axis = $pdf->gety()-2;
      $pdf->Ln(3);
      $pdf->Cell(12);
      $pdf->Cell(75, 5, 'TOTAL A CORTE DE: '.$this->repFecPreliq, 0, 0, 'L', false);
      $x_axis = $pdf->getx();
      $pdf->Cell(35);
      $pdf->Cell(24, 5, "$".number_format($totaPagar, 0), 0, 0, 'R' , false);
      $pdf->Ln(12);
      $pdf->SetFont('Arial', '', 9);
      // $pdf->Cell(110, 5, 'OBSERVACIONES', 0, 0, 'L');
      // $pdf->Ln(6);
      $pdf->MultiCell(170, 4, $this->obserPreliq, 0, 'L');
      dibujarFlecha($pdf, $x_axis, $y_axis, 20, 15);

      $pdf->Output('I', "PRELIQUIDACION-".$this->TerDocId.'.pdf', true);
      exit;
   }


   //**************************************************************************************
   public function traerHojaCalculo() {
   }
}


$documento = new imprimirDocumento();
$documento->informe      = $informe;
$documento->id_dvcliente = $id_dvcliente;
$documento->repFecPreliq = $repFecPreliq;
$documento->diasHabiles  = $diasHabiles;
$documento->mostrarEstCta = $mostrarEstCta;
$documento->obserPreliq   = $obserPreliq;
$documento->documPreliqList = $documPreliqList;
$documento->diasFestivos = $diasFestivos;
$documento->token     = $token;
if ($reportData["GenHojCal"] == '1') {
	$documento->traerHojaCalculo();
} else $documento->traerImpresionDocumento();