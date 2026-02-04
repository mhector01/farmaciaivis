<?php
    $pdf->setXY(2,1.5);
     $pdf->SetFont('Arial', 'B', 12);
    $pdf->MultiCell(73, 4.2, $empresa, 0,'C',0 ,1);


    $pdf->setXY(15,9);
    $pdf->SetFont('Arial', '', 6.9);
    $pdf->MultiCell(53, 6.2, '7ma Ave., Contiguo  Farmacia Leonan.', 0,'C',0 ,1);
    //$pdf->MultiCell(43, 4.2, $direccion, 0,'C',0 ,1);

    $get_YD = $pdf->GetY();
    
     $pdf->setXY(2,$get_YD + 2);
    $pdf->MultiCell(73, 4.2, 'Telefono : +504 9259-3305', 0,'C',0 ,1);

    //$pdf->Image('https://ditechonduras.com/wp-content/uploads/2020/10/cropped-Logo.png',3,4,20,0,'PNG');

    //$pdf->setXY(2,6);
    //$pdf->SetFont('Arial', '', 8);
    //$pdf->MultiCell(73, 4.2, $propietario, 0,'C',0 ,1);


    //$pdf->setXY(2,$get_YD);
    //$pdf->MultiCell(58, 4.2, 'RTN : 06051972009498'.$nrc, 0,'C',0 ,1);

    /*INGRESAR EN ESTA LINEA EL TELEFONO DEL TICKET*/

   


    //$pdf->setXY(2,$get_YD + 8);
    //$pdf->MultiCell(73, 4.2, 'No. Resolucion : '.$numero_resolucion, 0,'C',0 ,1);

   // $pdf->setXY(2,$get_YD + 12);
//    $pdf->MultiCell(73, 4.2, 'Fecha Resolucion : '.$fecha_resolucion, 0,'C',0 ,1);

  //  $pdf->setXY(2,$get_YD + 16);
//    $pdf->MultiCell(73, 4.2, 'Rango autorizado : '.$serie, 0,'C',0 ,1);


    $get_YH = $pdf->GetY();