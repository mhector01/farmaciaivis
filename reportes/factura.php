<?php
	require('fpdf/fpdf.php');
    //require('ClassTicket.php');
	$idventa =  base64_decode(isset($_GET['venta']) ? $_GET['venta'] : '');
	try
	{

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

    foreach ($datos as $row => $column) {

    	$numero_venta = $column["numero_venta"];
        $fecha_venta = $column["fecha_venta"];
        $fecha_venta = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_venta)->format('d/m/Y H:i:s');
        $tipo_pago = $column["tipo_pago"];
        $a_nombre = $column["cliente"];
        $notas = $column["notas"];
        $isv2 = $column["iva"];
        $subtotal = $column["total"]/1.15;
        $descuento = $column["total_descuento"];
        $total = $column["total"];
        $isv = $subtotal*0.15;
        //$subtotal = $total-$descuento;
        
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

    $objParametro =  new Parametro();
    $filas = $objParametro->Listar_Parametros();

    if (is_array($filas) || is_object($filas))
    {
        foreach ($filas as $row => $column)
        {
          $empresa = $column['nombre_empresa'];
          $propietario = $column['propietario'];
          $numero_nrc = $column['numero_nrc'];
          $direccion_empresa = $column['direccion_empresa'];
          $nit = $column['numero_nit'];
          

        }
    }

    
		//$numero_tarjeta = substr($numero_tarjeta,0,4).'-XXXX-XXXX-'.substr($numero_tarjeta,12,16);

	$pdf = new FPDF('P','mm','Letter');
    $pdf->AddPage();
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',16);    
    $pdf->setXY(10,6);
    $pdf->Cell(40,10,$empresa);
    $pdf->Image('https://ditechonduras.com/wp-content/uploads/2020/10/cropped-Logo.png',180,1,30,0,'PNG');

	$pdf->setXY(129,36);
    $pdf->SetFont('Arial','B',14);
    $pdf->SetTextColor(236, 32, 12);
    $pdf->Cell(30,8,'Factura No.');
    $pdf->SetFont('Arial','B',11);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(22,8,'000-003-01-');
    $pdf->Cell(37,8,$numero_venta);
    
    $pdf->SetFont('Arial','',10);    
    $pdf->setXY(10,7);
    $pdf->Cell(50,40,$propietario);
    
    $pdf->setX(10);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(2,20,'RTN : ');
    
    
    $pdf->setX(20);
    $pdf->Cell(50,20,$nit);
    $pdf->setX(71);
    
    $pdf->setX(10);
    $pdf->SetFont('Arial','',9); 
    $pdf->Cell(2,31,$direccion_empresa);

    $pdf->SetFont('Arial','',14);


    $pdf->SetFont('Arial','',11);
    $pdf->setXY(10,32);
    $pdf->Cell(36,7,'Fecha de creacion : ');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(38,7,$fecha_venta);

    $pdf->SetFont('Arial','',11);
    $pdf->setXY(10,38);
    $pdf->Cell(28,6,'Facturado por : ');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(82,6,$empleado);
    $pdf->Line(210,44,10,44);


    $pdf->SetFont('Arial','',13); 
    $pdf->setXY(10,43);
    $pdf->Cell(25,10,'CLIENTE : ');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(5,10,utf8_decode($a_nombre));
    
    $pdf->setXY(10,50);
    $pdf->SetFont('Arial','',8.5);
    
    $pdf->setXY(10,52);
    $pdf->SetFont('Arial','B',8.5);
    $pdf->Cell(2,10,'RTN : ');
    $pdf->setXY(20,52);
    $pdf->SetFont('Arial','',8.5);
    $pdf->Cell(2,10,$clienteRTN);
    $pdf->setXY(47,52);
    $pdf->SetFont('Arial','B',8.5);
    $pdf->Cell(2,10,'Telefono : ');
    $pdf->SetFont('Arial','',8.5);
    $pdf->setXY(63,52);
    $pdf->Cell(2,10,$telefonoC);
    $pdf->SetFont('Arial','B',8.5);
    $pdf->setXY(80,52);
    $pdf->Cell(2,10,'Direccion : ');
    $pdf->SetFont('Arial','',8.5);
    $pdf->setXY(96,52);
    $pdf->Cell(2,10,utf8_decode($direccionC));

    $pdf->Line(210,60,10,60);
    $pdf->Ln(10);

    $pdf->SetFillColor(189,228,247);
    $pdf->Cell(23,5,'Cant.',0,0,'L',1);
    $pdf->Cell(108,5,'Producto',0,0,'C',1);
    $pdf->Cell(23,5,'Precio unitario',0,0,'C',1);
    $pdf->Cell(23,5,'Exento',0,0,'C',1);
    //$pdf->Cell(23,5,'Descuento',1,0,'C',1);
    $pdf->Cell(23,5,'SubTotal',0,0,'C',1);
    $pdf->SetFillColor(255,255,255);
    $pdf->Ln(5);

   
   if (is_array($listado) || is_object($listado))
    {
        foreach ($listado as $row => $column) {

        $pdf->setX(10);
        $pdf->Cell(23,6,$column["cantidad"],0,0,'L',1);
        $pdf->MultiCell(97,6,$column["descripcion"],0,0,'R',1);
        $pdf->setX(141);
        //$pdf->Cell(108,6,utf8_decode($column["descripcion"]),1,0,'L',1);
        $punitario = $column["precio_unitario"] / 1.15;
        //$pdf->Cell(23,6,number_format($column["precio_unitario"], 2, '.', ','),1,0,'C',1);
        $pdf->Cell(23,-6,number_format($punitario, 2, '.', ','),0,0,'C',1);
        $pdf->Cell(23,-6,number_format($column["exento"], 2, '.', ','),0,0,'C',1);
        //$pdf->Cell(23,8,number_format($column["descuento"], 2, '.', ','),1,0,'C',1);
        //$pdf->Cell(23,5,$column["precio_unitario"],1,0,'C',1);
        //$pdf->Cell(23,5,$column["exento"],1,0,'C',1);
        //$pdf->Cell(23,5,$column["descuento"],1,0,'C',1);
        //$pdf->Cell(23,8,number_format($column["importe"], 2, '.', ','),1,0,'C',1);
        $subtot = $column["importe"] / 1.15;
        $pdf->Cell(23,-6,number_format($subtot, 2, '.', ','),0,0,'C',1);
        $pdf->Ln(6);
        $get_Y = $pdf->GetY();

      }


      $pdf->SetFont('Arial','B',8.5);
      $pdf->Cell(144,40,'',1,0,'C',1);
      $pdf->Text(60,$get_Y + 5,'NOTAS IMPORTANTES');
      $pdf->SetFont('Arial','',8.5);
      $pdf->Text(15,$get_Y + 12,'1 - Revise sus productos antes de salir de la tienda');
      $pdf->Text(19.5,$get_Y + 16,'Los articulos son entregados al cliente en perfectas condiciones.');
      $pdf->Text(15,$get_Y + 23,'2 - La mercaderia viaja por costo y riesgo de cliente');
      $pdf->Text(15,$get_Y + 28,'3 - NO se aceptan cambios ni devoluciones');
      $pdf->Text(15,$get_Y + 33,'4 - Consumibles no llevan garantia');
      $pdf->Text(15,$get_Y + 38,'5 - SU FORMA DE PAGO FUE EN: '.$tipo_pago.'.');

      

      $pdf->setX(154);
      $pdf->SetFillColor(189,228,247);
      $pdf->SetFont('Arial','B',8.5);
      $pdf->Cell(33,5,'Descuentos y rebajas',1,0,'R',1);
      $pdf->SetFont('Arial','',8.5);
      $pdf->SetFillColor(255,255,255);
      $pdf->Cell(23,5,number_format($descuento, 2, '.', ','),1,0,'C',1);
      
      //$pdf->Cell(23,5,$sumas,1,0,'C',1);
      $pdf->Ln(5);
      $pdf->setX(154);
      $pdf->SetFillColor(189,228,247);
      $pdf->SetFont('Arial','B',8.5);
      $pdf->Cell(33,5,'Importe exonerado',1,0,'R',1);
      $pdf->SetFillColor(255,255,255);
      $pdf->SetFont('Arial','',8.5);
      $pdf->Cell(23,5,number_format($blanco, 2, '.', ','),1,0,'C',1);

      //$pdf->Cell(23,5,$iva,1,0,'C',1);
      $pdf->Ln(5);
      $pdf->setX(154);
      $pdf->SetFillColor(189,228,247);
      $pdf->SetFont('Arial','B',8.5);
      $pdf->Cell(33,5,'Importe exento',1,0,'R',1);
      $pdf->SetFillColor(255,255,255);
      $pdf->SetFont('Arial','',8.5);
      $pdf->Cell(23,5,number_format($totalExento, 2, '.', ','),1,0,'C',1);     
      //$pdf->Cell(23,5,$subtotal,1,0,'C',1);


      $pdf->Ln(5);
      $pdf->setX(154);
      $pdf->SetFillColor(189,228,247);
      $pdf->SetFont('Arial','B',8.5);
      $pdf->Cell(33,5,'Importe gravado 15%',1,0,'R',1);
      $pdf->SetFillColor(255,255,255);
      $pdf->SetFont('Arial','',8.5);
      $pdf->Cell(23,5,number_format($subtotal, 2, '.', ','),1,0,'C',1);
      
      $pdf->Ln(5);
      $pdf->setX(154);
      $pdf->SetFillColor(189,228,247);
      $pdf->SetFont('Arial','B',8.5);
      $pdf->Cell(33,5,'Importe gravado 18%',1,0,'R',1);
      $pdf->SetFont('Arial','',8.5);
      $pdf->SetFillColor(255,255,255);
      $pdf->Cell(23,5,number_format($blanco, 2, '.', ','),1,0,'C',1);
      //$pdf->Cell(23,5,$total_exento,1,0,'C',1);
      $pdf->Ln(5);
      $pdf->setX(154);
      $pdf->SetFillColor(189,228,247);
      $pdf->SetFont('Arial','B',8.5);
      $pdf->Cell(33,5,'ISV 15%',1,0,'R',1);
      $pdf->SetFillColor(255,255,255);
      $pdf->SetFont('Arial','',8.5);
      $pdf->Cell(23,5,number_format($isv, 2, '.', ','),1,0,'C',1);
      //$pdf->Cell(23,5,$total_descuento,1,0,'C',1);
      $pdf->Ln(5);
      $pdf->setX(154);
      $pdf->SetFillColor(189,228,247);
      $pdf->SetFont('Arial','B',8.5);
      $pdf->Cell(33,5,'ISV 18%',1,0,'R',1);
      $pdf->SetFillColor(255,255,255);
      $pdf->SetFont('Arial','',8.5);
      $pdf->Cell(23,5,number_format($blanco, 2, '.', ','),1,0,'C',1);
      //$pdf->Cell(23,5,$total,1,0,'C',1);

      $pdf->Ln(5);
      $pdf->setX(154);
      $pdf->SetFillColor(189,228,247);
      $pdf->SetFont('Arial','B',8.5);
      $pdf->Cell(33,5,'TOTAL',1,0,'R',1);
      $pdf->SetFillColor(255,255,255);
      $pdf->SetFont('Arial','',8.5);
      $pdf->Cell(23,5,number_format($total, 2, '.', ','),1,0,'C',1);
      //$pdf->Cell(23,5,$total,1,0,'C',1);


      }

        
    /*=============================================
    =           En medio del documento linea      =
    =============================================*/
    
    $pdf->SetFont('Arial','',10);
    $pdf->setXY(10,160);
    $pdf->Cell(56,6,'Notas adicionales: ');
    $pdf->setY(165);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(58,6, $notas);
    
    
    $pdf->SetFont('Arial','',8);
    $pdf->setXY(10,200);
    $pdf->Cell(56,6,'No. Correlativo de orden de compra exenta: ');
    

    $pdf->SetFont('Arial','',8);
    $pdf->setXY(10,204);
    $pdf->Cell(65,6,'No. Correlativo de contancia de registro exonerado: ');

    $pdf->SetFont('Arial','',8);
    $pdf->setXY(10,208);
    $pdf->Cell(65,6,'No. identificativo del registro de la SAG: ');
    


    $pdf->SetFont('Arial','',10);
    $pdf->setXY(70,230);
    $pdf->Cell(12,6,'La factura es beneficio de todos "Exijala" ');
    




    $pdf->SetFont('Arial','',10);
    $pdf->setXY(10,250);
    $pdf->Cell(130,6,'Rango autorizado: 000-003-01-00004501 al 000-003-01-00005500');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(90,6,'Fecha limite de emision: 05/03/2025');
    



  




      $pdf->Output('','Factura_'.$numero_venta.'.pdf',true);
	} catch (Exception $e) {

		$pdf->Text(22.8, 5, 'ERROR AL IMPRIMIR LA FACTURA');
		$pdf->Output('I','Ticket_ERROR.pdf',true);

	}



 ?>
