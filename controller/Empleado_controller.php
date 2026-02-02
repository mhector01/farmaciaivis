<?php 

	class Empleado {

		public static function Listar_Empleados(){

			$filas = EmpleadoModel::Listar_Empleados();
			return $filas;
		
		}

		public static function Insertar_Empleado($nombre_empleado, $apellido_empleado, $telefono_empleado, $email_empleado){

			$cmd = EmpleadoModel::Insertar_Empleado($nombre_empleado, $apellido_empleado, $telefono_empleado, $email_empleado);
			
		}

		public static function Editar_Empleado($idempleado, $nombre_empleado, $apellido_empleado, $telefono_empleado, $email_empleado, $estado){

			$cmd = EmpleadoModel::Editar_Empleado($idempleado, $nombre_empleado, $apellido_empleado, $telefono_empleado, $email_empleado, $estado);
			
		}

	}


 ?>