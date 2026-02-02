<?php

	class Cotizacion {

		public static function Autocomplete_Producto($search){

			$filas = CotizacionModel::Autocomplete_Producto($search);
			return $filas;

		}

		public static function Ver_Moneda_Reporte(){

			$filas = CotizacionModel::Ver_Moneda_Reporte();
			return $filas;

		}

		public static function Listar_Cotizaciones($date,$date2){

			$filas = CotizacionModel::Listar_Cotizaciones($date,$date2);
			return $filas;

		}
		
		public static function Listar_All_Cotizaciones(){

			$filas = CotizacionModel::Listar_All_Cotizaciones();
			return $filas;

		}

		public static function Listar_Detalle($idCotizacion){

			$filas = CotizacionModel::Listar_Detalle($idCotizacion);
			return $filas;

		}

		public static function Listar_Info($idCotizacion){

			$filas = CotizacionModel::Listar_Info($idCotizacion);
			return $filas;

		}

		public static function Count_Cotizaciones($date,$date2){

			$filas = CotizacionModel::Count_Cotizaciones($date,$date2);
			return $filas;

		}

		public static function Insertar_Cotizacion($a_nombre, $tipo_pago, $entrega,
		$sumas, $iva, $exento, $retenido, $descuento, $total, $sonletras, $idusuario, $idcliente){

		$cmd = CotizacionModel::Insertar_Cotizacion($a_nombre, $tipo_pago, $entrega,
		$sumas, $iva, $exento, $retenido, $descuento, $total, $sonletras, $idusuario, $idcliente);

		}

		public static function Insertar_DetalleCotizacion($idproducto, $cantidad, $disponible, $precio_unitario, $exento, $descuento, $importe){

		$cmd = CotizacionModel::Insertar_DetalleCotizacion($idproducto, $cantidad, $disponible, $precio_unitario, $exento, $descuento, $importe);

		}


		public static function Borrar_Cotizacion($idCotizacion){

		$cmd = CotizacionModel::Borrar_Cotizacion($idCotizacion);

		}

	}


 ?>
