<?php
// INICIO CORRECCIÓN 1: Iniciamos el buffer para evitar que los warnings rompan el PDF
ob_start();

// require('fpdf/fpdf.php'); // Asumo que esto ya se carga en ClassTicket o similar
require('ClassTicket.php');

$idventa = base64_decode(isset($_GET['venta']) ? $_GET['venta'] : '');
define('EURO',chr(128)); 

try {
    spl_autoload_register(function($className){
        $model = "../model/". $className ."_model.php";
        $controller = "../controller/". $className ."_controller.php";
        require_once($model);
        require_once($controller);
    });

    $objVenta = new Venta();
    $listado = $objVenta->Imprimir_Factura_DetalleVenta($idventa);

    if($idventa == ""){
        $detalle = $objVenta->Imprimir_Factura_DetalleVenta('0');
        $datos = $objVenta->Imprimir_Factura_Venta('0');
    } else {
        $detalle = $objVenta->Imprimir_Factura_DetalleVenta($idventa);
        $datos = $objVenta->Imprimir_Factura_Venta($idventa);
    }

    // Inicializamos variables para evitar warnings de "undefined variable"
    $numero_venta = ''; $fecha_venta = ''; $tipo_pago = ''; $comprobante = '';
    $Nocomprobante = ''; $a_nombre = ''; $subtotal = 0; $isv = 0; $notas = '';
    $descuento = 0; $total = 0; $cambio = 0; $numero_productos = 0;
    $efectivo = 0; $pago_tarjeta = 0; $clienteRTN = ''; $telefonoC = '';
    $direccionC = ''; $totalExento = 0; $empleado = ''; $blanco = 0;
    $fechaT = ''; $serie = '';
    $empresa = ''; $propietario = ''; $numero_nrc = ''; $direccion_empresa = ''; $nit = '';

    foreach ($datos as $row => $column) {
        $numero_venta = $column["numero_venta"];
        $fecha_venta = $column["fecha_venta"];
        $fecha_venta = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_venta)->format('d/m/Y H:i:s');
        $tipo_pago = $column["tipo_pago"];
        $comprobante = $column["tipo_comprobante"];
        $Nocomprobante = $column["numero_comprobante"];
        $a_nombre = $column["cliente"];
        $subtotal = $column["sumas"];
        $isv = $column["iva"];
        $notas = $column["notas"];
        $descuento = $column["total_descuento"];
        $total = $column["total"];
        $cambio = $column["cambio"];
        $numero_productos = $column["cantidad"];
        $tipo_pago = $column["tipo_pago"];
        $efectivo = $column["pago_efectivo"];
        $pago_tarjeta = $column["pago_tarjeta"];
        $clienteRTN = $column["rtnC"];
        $telefonoC = $column["telefonoC"];
        $direccionC = $column["direccionC"];
        $totalExento = $column["total_exento"];
        $empleado = $column["empleado"];
        $blanco = 0;
    }

    $objParametro = new Parametro();
    $filas = $objParametro->Listar_Parametros();

    if (is_array($filas) || is_object($filas)) {
        foreach ($filas as $row => $column) {
            $empresa = $column['nombre_empresa'];
            $propietario = $column['propietario'];
            $numero_nrc = $column['numero_nrc'];
            $direccion_empresa = $column['direccion_empresa'];
            $nit = $column['numero_nit'];
        }
    }
    
    $objTiraje = new Tiraje();
    $filasT = $objTiraje->Listar_Tirajes();

    if (is_array($filasT) || is_object($filasT)) {
        foreach ($filasT as $row => $column) {
            $fechaT = $column['fecha_resolucion'];
            $serie = $column['serie'];
        }
    }

    // Estimar alto del ticket según cantidad de productos
    $cantidad_productos = $detalle->rowCount();
    $alto_base = 250; 
    $alto_por_producto = 8; 
    $alto_total = $alto_base + ($cantidad_productos * $alto_por_producto);
    
    // Crear PDF con altura dinámica
    $pdf = new FPDF('P','mm', array(76, $alto_total));
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(false);

    include('../includes/ticketheader.inc.php');
    $pdf->SetFont('Arial', '', 9.2);

    $pdf->Text(-5, $get_YH + 2 , '
      ------------------------------------------------------------------');

    // DATOS FACTURA        
    if ($comprobante == 1) {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Text(3.8, $get_YH + 5, ''.$propietario);
        $pdf->Text(3.8, $get_YH + 10, 'Fecha limite emision : '.substr($fechaT, 0,10));
        $pdf->Text(3.8, $get_YH + 15, 'Rango autorizado:');
        $pdf->Text(3.8, $get_YH + 19, '000-003-01-00004001 al 000-003-01-00004500');
        $pdf->Text(3.8, $get_YH + 24, 'Orden compra exenta : ');
        $pdf->Text(3.8, $get_YH + 27, 'Constancia Reg. exonerado : ');
        $pdf->Text(3.8, $get_YH + 30, 'Registro SAG : ');
        $pdf->Text(-5, $get_YH + 32, '------------------------------------------------------------------');
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Text(3.8, $get_YH  + 35, 'Factura No 000-003-01-'.str_pad($Nocomprobante, 8, '0', STR_PAD_LEFT));
        
        $pdf->Text(4, $get_YH + 40, 'Fecha : '.$fecha_venta);
        $pdf->Text(46, $get_YH  + 40, 'Cajero : '.substr($empleado, 0,7));
        $pdf->Text(4, $get_YH + 45, 'Cliente : ');
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Text(4, $get_YH + 50, ''.$a_nombre);
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Text(4, $get_YH + 55, 'RTN : '.$clienteRTN);
        // CORRECCIÓN 2: Reemplazo de utf8_decode (obsoleto)
        $direccionLimpia = mb_convert_encoding('Dirección : '.$direccionC, 'ISO-8859-1', 'UTF-8');
        $pdf->Text(4, $get_YH + 60, $direccionLimpia);
        
        $pdf->SetFont('Arial', '', 9.2);
        $pdf->Text(2, $get_YH + 64, '------------------------------------------------------------------'); 
        
        $pdf->SetXY(2,$get_YH + 65);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','B',8.5);
        $pdf->Cell(35,4,'Descripcion',0,0,'C',1);
        $pdf->Cell(10,4,'Cant',0,0,'L',1);
        $pdf->Cell(16,4,'Precio',0,0,'L',1);
        $pdf->Cell(12,4,'Total',0,0,'L',1);
        $pdf->SetFont('Arial','',8.5);
        $pdf->Text(2, $get_YH + 70, '-----------------------------------------------------------------------');
        $pdf->Ln(6);

    } else {
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Text(3.8, $get_YH  + 5, 'Ticket No '.str_pad($Nocomprobante, 8, '0', STR_PAD_LEFT));
        
        $pdf->Text(4, $get_YH + 10, 'Fecha : '.$fecha_venta);
        $pdf->Text(46, $get_YH  + 10, 'Cajero : '.substr($empleado, 0,7));
        $pdf->Text(4, $get_YH + 15, 'Cliente : ');
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Text(4, $get_YH + 20, ''.$a_nombre);
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Text(4, $get_YH + 25, 'RTN : '.$clienteRTN);
        $pdf->Text(4, $get_YH + 30, 'Direccion : '.$direccionC);
        $pdf->SetFont('Arial', '', 9.2);
        $pdf->Text(2, $get_YH + 34, '------------------------------------------------------------------');  
        
        $pdf->SetXY(2,$get_YH + 35);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','B',8.5);
        $pdf->Cell(35,4,'Descripcion',0,0,'C',1);
        $pdf->Cell(10,4,'Cant',0,0,'L',1);
        $pdf->Cell(16,4,'Precio',0,0,'L',1);
        $pdf->Cell(12,4,'Total',0,0,'L',1);
        $pdf->SetFont('Arial','',8.5);
        $pdf->Ln(6);
    }

    $pdf->SetAutoPageBreak(true,1);
    $item = 0;
    $cuenta = 0;
    $get_Y = $pdf->GetY(); // Inicializar por si el while no corre

    while($row = $detalle->fetch(PDO::FETCH_ASSOC)) {
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->setX(3);
        
        // CORRECCIÓN 2: Reemplazo de utf8_decode por mb_convert_encoding
        $descripcion = mb_convert_encoding($row['descripcion'], 'ISO-8859-1', 'UTF-8');
        $pdf->MultiCell(35, 4, $descripcion, 0, 'L');

        $pdf->Cell(35, -5,$row['cantidad'],0,0,'R');
        $pdf->Cell(12, -5,''.number_format($row['precio_unitario'], 2, '.', ','),0,0,'C',1);
        $pdf->Cell(15, -5,$row['importe'],0,0,'R');
        $pdf->Ln(3);
        $get_Y = $pdf->GetY();
    }

    $pdf->SetFont('Arial','',8.5);
    $pdf->Text(2, $get_Y+1, '-----------------------------------------------------------------------');

    /*=============================================
    =           En medio del documento linea      =
    =============================================*/
    $pdf->SetFont('Arial','B',8.5);
    $pdf->Text(4,$get_Y + 5,'G = GRAVADO');
    $pdf->Text(30,$get_Y + 5,'E = EXENTO');

    $pdf->Text(4,$get_Y + 10,'SubTotal :');
    $pdf->Text(57,$get_Y + 10,'L '.number_format($subtotal+$descuento, 2, '.', ','),1,0,'C',1);
    $pdf->Text(4,$get_Y + 15,'Importe exonerado :');
    $pdf->Text(57,$get_Y + 15,'L '.number_format($blanco, 2, '.', ','),1,0,'C',1);
    $pdf->Text(4,$get_Y + 20,'Importe exento :');
    $pdf->Text(57,$get_Y + 20,'L '.number_format($totalExento, 2, '.', ','),1,0,'C',1);
    $pdf->Text(4,$get_Y + 25,'Importe gravado 15%:');
    $pdf->Text(57,$get_Y + 25,'L '.number_format($subtotal, 2, '.', ','),1,0,'C',1);
    $pdf->Text(4,$get_Y + 30,'Importe gravado 18%:');
    $pdf->Text(57,$get_Y + 30,'L '.number_format($blanco, 2, '.', ','),1,0,'C',1);
    $pdf->Text(4,$get_Y + 35,'ISV 15%:');
    $pdf->Text(57,$get_Y + 35,'L '.number_format($isv, 2, '.', ','),1,0,'C',1);
    $pdf->Text(4,$get_Y + 40,'ISV 18%:');
    $pdf->Text(57,$get_Y + 40,'L '.number_format($blanco, 2, '.', ','),1,0,'C',1);
    $pdf->Text(4,$get_Y + 45,'Descuentos y rebajas otorgadas :');
    $pdf->Text(57,$get_Y + 45,'L '.number_format($descuento, 2, '.', ','),1,0,'C',1);
    $pdf->Text(4,$get_Y + 50,'Total a pagar :');
    $pdf->SetFont('Arial','B',8.5);
    $pdf->Text(57,$get_Y + 50,'L '.number_format($total, 2, '.', ','),1,0,'C',1);
    
    $pdf->Text(2, $get_Y+55, '-----------------------------------------------------------------------');
    
    if($tipo_pago == 'EFECTIVO'){
        $pdf->Text(4,$get_Y + 60,'Pago realizado en: Efectivo');
        $pdf->Text(24,$get_Y + 65,'Recibido :');
        $pdf->Text(57,$get_Y + 65,'L '.number_format($efectivo, 2, '.', ','),1,0,'C',1);
        $pdf->Text(24,$get_Y + 69,'Cambio :');
        $pdf->Text(57,$get_Y + 69,'L '.number_format($cambio, 2, '.', ','),1,0,'C',1);
        
        $pdf->Text(19, $get_Y+87, 'GRACIAS POR SU COMPRA');
        $pdf->SetFillColor(0,0,0);
        $pdf->Text(28, $get_Y+94, '*'.str_pad($Nocomprobante, 8, '0', STR_PAD_LEFT).'*');

    } else if ($tipo_pago == 'TARJETA'){
        $pdf->Text(4,$get_Y + 65,'Pago realizado con: Tarjeta');
        $pdf->Text(23,$get_Y + 72,'Debitado :');
        $pdf->Text(57,$get_Y + 72,'L '.number_format($total, 2, '.', ','),1,0,'C',1);

        $pdf->Text(2, $get_Y+77, '-----------------------------------------------------------------------');
        $pdf->SetFont('Arial','BI',8.5);
        $pdf->Text(4, $get_Y+60, 'Precios en : Lempiras');
        $pdf->SetFont('Arial','B',8.5);
        
        $pdf->Text(19, $get_Y+82, 'GRACIAS POR SU COMPRA');
        $pdf->SetFillColor(0,0,0);
        $pdf->Text(28, $get_Y+94, '*'.str_pad($Nocomprobante, 8, '0', STR_PAD_LEFT).'*');

    } else if ($tipo_pago == 'DEPOSITO'){
        $pdf->Text(4,$get_Y + 65,'Pago realizado con: Deposito');
        $pdf->Text(23,$get_Y + 72,'Depositado :');
        $pdf->Text(57,$get_Y + 72,'L '.number_format($total, 2, '.', ','),1,0,'C',1);

        $pdf->Text(2, $get_Y+77,'-----------------------------------------------------------------------');
        $pdf->SetFont('Arial','BI',8.5);
        $pdf->Text(4, $get_Y+60, 'Precios en : Lempiras');
        $pdf->SetFont('Arial','B',8.5);
        
        $pdf->Text(19, $get_Y+82, 'GRACIAS POR SU COMPRA');
        $pdf->SetFillColor(0,0,0);
        $pdf->Text(28, $get_Y+94, '*'.str_pad($Nocomprobante, 8, '0', STR_PAD_LEFT).'*');
    }
  
    $pdf->Text(10, $get_Y+112, '_________________________________');
    $pdf->Text(25, $get_Y+116, 'Firma Cliente');
    
    $pdf->SetFont('Arial','',8);
    $pdf->setXY(4,$get_Y+128);
    $pdf->MultiCell(60, 5, 'NOTAS : '.$notas);
    
    // CORRECCIÓN 1: Limpiamos cualquier warning acumulado antes de enviar el PDF
    ob_end_clean();
    $pdf->Output('','Factura_'.$numero_venta.'.pdf',true);

} catch (Exception $e) {
    // CORRECCIÓN 1: Limpiamos el buffer si hay error
    if (ob_get_length()) ob_end_clean();

    // CORRECCIÓN 3: Creamos un NUEVO PDF para el error. 
    // No podemos usar $pdf porque seguramente está "cerrado" o corrupto por el error anterior.
    $pdfError = new FPDF('P','mm', array(80, 50));
    $pdfError->AddPage();
    $pdfError->SetFont('Arial', '', 8);
    $pdfError->Text(5, 10, 'ERROR AL IMPRIMIR LA FACTURA');
    // Si quieres ver el error real para depurar, descomenta la siguiente línea:
    // $pdfError->Text(5, 15, substr($e->getMessage(), 0, 40)); 
    $pdfError->Output('I','Ticket_ERROR.pdf',true);
}
?>