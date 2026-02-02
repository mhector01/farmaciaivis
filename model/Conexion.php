<?php



	class Conexion {
	    
	    

		public static function Conectar(){
		    
		   
			$driver = 'mysql'; //mysql no cambiar
			$host = 'localhost'; //localhost
			$dbname = 'farmaciaivis_sistema'; //bdd
			$username ='farmaciaivis_hector'; //usuario
			$passwd = 'Rangermiperro1'; //contra




			$server=$driver.':host='.$host.';dbname='.$dbname;

			try {

				$conexion = new PDO($server,$username,$passwd);
				//$conexion = exec("SET CHARACTER SET utf8");
				$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$mitz="America/Tegucigalpa";
				$tz = (new DateTime('now', new DateTimeZone($mitz)))->format('P');
				$conexion->exec("SET time_zone='$tz';");
				$k= $conexion->query("select now() as mifecha") ;
                $q= $k->fetch();
			

			} catch (Exception $e) {

				$conexion = null;
            	echo '<span class="label label-danger label-block">ERROR AL CONECTARSE A LA BASE DE DATOS, PRESIONE F5</span>';
            	exit();
			}


			return $conexion;

		}

	}
