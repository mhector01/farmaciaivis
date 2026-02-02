<?php
	session_start();
	require_once("../../config/money_string.php");

	spl_autoload_register(function($className){
		$model = "../../model/". $className ."_model.php";
		$controller = "../../controller/". $className ."_controller.php";

		require_once($model);
		require_once($controller);
	});

	$funcion = new Venta();
	$caja_funcion = new Caja();

	date_default_timezone_set("America/El_Salvador");

	// --- BLOQUE 1: GET (Vencimientos) ---
	$idproducto = isset($_GET['idproducto']) ? $_GET['idproducto'] : '';
	if(!$idproducto==""){
		$funcion->Fechas_Vencimiento($idproducto);
		exit; 
	}

	// --- BLOQUE 2: AJAX (Cargar datos para editar) ---
	if(isset($_GET['action']) && $_GET['action'] == 'buscar_para_editar') {
		if(isset($_POST['idventa'])){
			$datos = Venta::Obtener_Datos_Edicion($_POST['idventa']);
			echo json_encode($datos);
		}
		exit;
	}
	
	// --- BLOQUE NUEVO: AUTOCOMPLETE DE PRODUCTOS ---
    if(isset($_GET['action']) && $_GET['action'] == 'autocomplete') {
        $search = $_GET['term']; // jQuery UI envía lo que escribes en la variable 'term'
        $datos = Venta::Autocomplete_Producto($search);
        // Venta::Autocomplete_Producto ya hace el json_encode internamente según tu modelo
        exit;
    }

	// --- BLOQUE 3: POST (Guardar) ---
	if (!empty($_POST))
	{
		// ====================================================================
		// CASO A: EDICIÓN (Lógica Nueva)
		// ====================================================================
		if(isset($_GET['action']) && $_GET['action'] == 'editar') 
		{
			try {
				$idventa = $_POST['txtIdVentaEditar']; 
				$total = trim($_POST['total']);
				$tipo_pago_raw = trim($_POST['cbMPago']); 
				$notas = trim($_POST['txtNotas']);
				$son_letras = num2letras($total);

				// Mapeo de Tipo de Pago
				$tipo_pago = '';
				if($tipo_pago_raw == '1'){ $tipo_pago = 'EFECTIVO'; } 
				else if ($tipo_pago_raw =='2'){ $tipo_pago = 'TARJETA'; } 
				else if ($tipo_pago_raw =='3'){ $tipo_pago = 'EFECTIVO Y TARJETA'; } 
				else if ($tipo_pago_raw =='4'){ $tipo_pago = 'DEPOSITO'; }

				$cabecera = [
					'idcliente' 	=> trim($_POST['cbCliente']),
					'tipo_pago' 	=> $tipo_pago,
					'sumas' 		=> trim($_POST['sumas']), 
					'iva' 			=> trim($_POST['iva']),
					'total' 		=> $total,
					'pago_efectivo' => trim($_POST['txtMonto']),
					'cambio' 		=> trim($_POST['txtCambio']),
					'notas' 		=> $notas,
					'exento'        => trim($_POST['exento']), 
					'retenido'      => trim($_POST['retenido']),
					'descuento'     => trim($_POST['descuento']),
					'sonletras'     => $son_letras
				];

				$productos = [];
				if(isset($_POST['idproducto'])){
					$count = count($_POST['idproducto']);
					for($i=0; $i < $count; $i++){
						$fecha_vence = isset($_POST['fecha_vence'][$i]) ? $_POST['fecha_vence'][$i] : null;
						if(empty($fecha_vence) || $fecha_vence == '-' || $fecha_vence == 'null') {
							$fecha_vence = null;
						}

						$productos[] = [
							'idproducto' 	=> $_POST['idproducto'][$i],
							'cantidad' 		=> $_POST['cantidad'][$i],
							'precio' 		=> $_POST['precio'][$i],
							'exento' 		=> $_POST['exento'][$i],
							'descuento' 	=> $_POST['descuento'][$i],
							'importe' 		=> $_POST['importe'][$i],
							'fecha_vence' 	=> $fecha_vence
						];
					}
				}

				$resultado = Venta::Editar_Venta_Completa($idventa, $cabecera, $productos);
				
				if($resultado == "success"){
					echo json_encode("Validado"); 
				} else {
					echo json_encode("Error");
				}

			} catch (Exception $e) {
				echo json_encode("Error");
			}
		} 
		
		// ====================================================================
		// CASO B: CREAR NUEVA VENTA (TU LÓGICA ORIGINAL)
		// ====================================================================
		else 
		{
			try {

				$cuantos = $_POST['cuantos'];
				$stringdatos = $_POST['stringdatos'];
				$listadatos=explode('#',$stringdatos);
				$pagado = trim($_POST['pagado']);
				$comprobante = trim($_POST['comprobante']);
				$tipo_pago = trim($_POST['tipo_pago']);
				$idcliente = trim($_POST['idcliente']);
				$sumas = trim($_POST['sumas']);
				$iva = trim($_POST['iva']);
				$exento = trim($_POST['exento']);
				$retenido = trim($_POST['retenido']);
				$descuento = trim($_POST['descuento']);
				$total = trim($_POST['total']);
				$cambio = trim($_POST['cambio']);
				$efectivo = trim($_POST['efectivo']);
				$pago_tarjeta = trim($_POST['pago_tarjeta']);
				$numero_tarjeta = trim($_POST['numero_tarjeta']);
				$tarjeta_habiente = trim($_POST['tarjeta_habiente']);
				$fecha= date("Y-m-d");
				$son_letras = num2letras($total);
				$numero_tarjeta =  str_replace ( "-", "", $numero_tarjeta);
				$notas = trim($_POST['notas']);

				if($tipo_pago == '1'){
					$tipo_pago = 'EFECTIVO';
				} else if ($tipo_pago =='2'){
					$tipo_pago = 'TARJETA';
				} else if ($tipo_pago =='3'){
					$tipo_pago = 'EFECTIVO Y TARJETA';
				} else if ($tipo_pago =='4'){
					$tipo_pago = 'DEPOSITO';
				}

				if($idcliente==''){
					if($pagado == '1'){
							$funcion->Insertar_Venta($tipo_pago,$comprobante,$sumas,$iva,$exento,$retenido,$descuento,$total,$son_letras,$efectivo,
							$pago_tarjeta,$numero_tarjeta,$tarjeta_habiente,$cambio,1,0,$_SESSION['user_id'],$notas);
					} else if ($pagado == '0') {
							$funcion->Insertar_Venta($tipo_pago,$comprobante,$sumas,$iva,$exento,$retenido,$descuento,$total,$son_letras,$efectivo,
							$pago_tarjeta,$numero_tarjeta,$tarjeta_habiente,$cambio,2,0,$_SESSION['user_id'],$notas);
					}
				} else if ($idcliente!='') {
					if($pagado == '1'){
						$funcion->Insertar_Venta($tipo_pago,$comprobante,$sumas,$iva,$exento,$retenido,$descuento,$total,$son_letras,$efectivo,
						$pago_tarjeta,$numero_tarjeta,$tarjeta_habiente,$cambio,1,$idcliente,$_SESSION['user_id'],$notas);
					} else if ($pagado == '0') {
						$funcion->Insertar_Venta($tipo_pago,$comprobante,$sumas,$iva,$exento,$retenido,$descuento,$total,$son_letras,$efectivo,
						$pago_tarjeta,$numero_tarjeta,$tarjeta_habiente,$cambio,2,$idcliente,$_SESSION['user_id'],$notas);
					}
				}

				for ($i=0;$i<$cuantos ;$i++){

					list($idproducto,$cantidad,$precio_unitario,$exentos,$descuento,$fecha_vence,$importe)=explode('|',$listadatos[$i]);

					if($fecha_vence=='')
					{
						$fecha_vence = '2000-01-01';

					} else {
						$fecha_vence = DateTime::createFromFormat('d/m/Y', $fecha_vence)->format('Y-m-d');
					}

					$funcion->Insertar_DetalleVenta($idproducto,$cantidad,$precio_unitario,$exentos,$descuento,$fecha_vence,$importe);
				}
				
				// ERROR CORREGIDO: SE ELIMINÓ EL ECHO QUE YO HABÍA PUESTO AQUÍ.
				// El echo lo hace $funcion->Insertar_Venta internamente.

			} catch (Exception $e) {
				$data = "Error";
				echo json_encode($data);
			}
		}
	}
?>