<?php
if (!isset($_SESSION)) {
   session_start();
}

// $_SESSION['reportPath'] = '../../';

// require_once "../../models/presupuesto/mdlcertdisponibilidad.php";
// require_once "../../../vendor/fpdf/fpdf.php";

// Ruta base del proyecto
$projectRoot = dirname(__DIR__, 3);

// Modelos / librerías
require_once $projectRoot . "/app/models/presupuesto/mdlcertdisponibilidad.php";
require_once $projectRoot . "/vendor/tfpdf/tfpdf.php";


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
      alert('Token inválido o expirado');
      window.close();
   </script>";
   exit;
}



$empresa   = $reportData['empresa'];
$periodo   = $reportData['periodo'];
$cdpNumero = $reportData['cdpNumero'];

$informe = CertDisponibilidadModel::getCertDisponibilidadReporte($empresa, $periodo, $cdpNumero);

class PDF extends tFPDF {

    public $empresaNombre = '';
    public $empresaNit = '';
    public $titulo = '';
    public $cdpNumero = '';
    public $fecha = '';
    public $periodoFiscal = '';
    public $dependencia = '';
    public $concepto = '';
    public $documentoSoporte = '';
    public $fechaVigencia = '';
    public $totalCertificado = 0;

   function Header() {
      $this->SetMargins(12, 10, 12);
      $this->SetFont('DejaVu', 'B', 11);

      // Empresa
      $this->Cell(0, 5, $this->empresaNombre, 0, 1, 'L');

      // Nit
      $this->SetFont('DejaVu', '', 10);
      $this->Cell(0, 5, 'Nit: ' . $this->empresaNit, 0, 1, 'L');

      // Título
      $this->SetFont('DejaVu', 'B', 12);
      $this->Cell(0, 7, $this->titulo . ' Nro. ' . $this->cdpNumero, 0, 1, 'C');
      $this->Ln(2);

      // Datos principales
      $this->SetFont('DejaVu', '', 10);

      $this->Cell(35, 6, 'Fecha:', 0, 0, 'L');
      $this->Cell(65, 6, $this->fecha, 0, 0, 'L');

      $this->Cell(35, 6, 'Periodo Fiscal:', 0, 0, 'L');
      $this->Cell(0, 6, $this->periodoFiscal, 0, 1, 'L');

      $this->Cell(35, 6, 'Dependencia:', 0, 0, 'L');
      $this->Cell(0, 6, $this->dependencia, 0, 1, 'L');

      $this->Cell(35, 6, 'Objeto:', 0, 0, 'L');
      $this->MultiCell(0, 6, $this->concepto, 0, 'L');

      $this->Cell(35, 6, 'Docum Soporte:', 0, 0, 'L');
      $this->Cell(0, 6, $this->documentoSoporte, 0, 1, 'L');

      $this->Ln(6);
      $this->Cell(0, 6, 'El suscrito Jefe de la División de Presupuesto', 0, 1, 'L');
      $this->Ln(4);
        
      $this->SetFont('DejaVu', 'B', 12);
      $this->Cell(0, 6, 'C E R T I F I C A', 0, 1, 'C');
      $this->Ln(4);

      $this->SetFont('DejaVu', '', 10);
      $texto = 'Que dentro del presupuesto general de rentas y gastos de "' . $this->empresaNombre . '" del presente periodo fiscal, existe saldo disponible y no comprometido para amparar el compromiso que se pretende asumir asi:';
      $this->MultiCell(0, 6, $texto, 0, 'J');

      $this->Ln(5);

      // Encabezado tabla
      $this->SetFont('DejaVu', 'B', 10);
      $this->Cell(35, 7, 'Código', 1, 0, 'C');
      $this->Cell(120, 7, 'Descripción', 1, 0, 'C');
      $this->Cell(30, 7, 'Valor', 1, 1, 'C');
   }

   function Footer() {
      $this->SetY(-40);
      $this->SetFont('DejaVu', '', 10);

      $this->Cell(55, 6, 'Vigencia del Presente Certificado:', 0, 0, 'L');
      $this->Cell(0, 6, $this->fechaVigencia, 0, 1, 'L');

      $this->Ln(18);
      $this->Cell(0, 6, '______________________________', 0, 1, 'C');
      $this->Cell(0, 6, 'Jefe de Presupuesto', 0, 1, 'C');
   }
}


$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AddFont('DejaVu','','DejaVuSans.ttf', true);
$pdf->AddFont('DejaVu', 'B', 'DejaVuSans-Bold.ttf', true);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 45);

if (empty($informe)) {
   $pdf->AddPage();
   $pdf->SetFont('DejaVu', 'B', 14);
   $pdf->Cell(0, 10, 'No se encontraron datos para el reporte', 0, 1, 'C');
   $pdf->Output('I', 'CDP-' . $cdpNumero . '.pdf', true);
   exit;
}

$primero = $informe[0];

// Encabezado del reporte
$pdf->empresaNombre = $_SESSION['companyname'] ?? 'EMPRESA';
$pdf->empresaNit = $_SESSION['companyid'] ?? '';
$pdf->titulo = 'CERTIFICADO DE DISPONIBILIDAD PRESUPUESTAL';
$pdf->cdpNumero = $primero['CertDispId'];
$pdf->fecha = !empty($primero['Fecha']) ? date('d/m/Y', strtotime($primero['Fecha'])) : '';
$pdf->periodoFiscal = $primero['PeriodoFiscal'];
$pdf->dependencia = $primero['DependenciaNombre'] ?? '';
$pdf->concepto = $primero['Concepto'] ?? '';
$pdf->documentoSoporte = trim(($primero['TipoDocumentoNombre'] ?? '') . ' Nro ' . ($primero['TipoDocumentoNr'] ?? ''));
$pdf->fechaVigencia = !empty($primero['Fecha']) ? date('d/m/Y', strtotime($primero['Fecha'])) : '';

$pdf->AddPage();

// Detalle
$pdf->SetFont('DejaVu', '', 10);
$total = 0;

foreach ($informe as $row) {
   $yInicial = $pdf->GetY();

   $pdf->Cell(35, 6, $row['RubroGastoId'], 1, 0, 'L');

   $xDesc = $pdf->GetX();
   $yDesc = $pdf->GetY();

   $pdf->MultiCell(120, 6, $row['RubroGastoNombre'],  1, 'L');

   $alturaDescripcion = $pdf->GetY() - $yDesc;
   $alturaFila = max(6, $alturaDescripcion);

   $pdf->SetXY($xDesc + 120, $yInicial);
   $pdf->Cell(30, $alturaFila, number_format((float)$row['Valor'], 0, ',', '.'), 1, 1, 'R');

   $total += (float)$row['Valor'];
}

// Total
$pdf->Ln(4);
$pdf->SetFont('DejaVu', 'B', 10);
$pdf->Cell(155, 7, 'Total Certificado', 1, 0, 'R');
$pdf->Cell(30, 7, number_format($total, 0, ',', '.'), 1, 1, 'R');

$pdf->Output('I', 'CDP-' . $cdpNumero . '.pdf', true);
exit;