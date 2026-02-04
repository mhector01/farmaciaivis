<?php



	class Conexion {
	    
	    

		public static function Conectar(){
		    
		   
			$driver = 'mysql'; //mysql no cambiar
			$host = 'q00w40ssg40k04s84gkokkkk'; //localhost
			$dbname = 'farmaciasistem'; //bdd
			$username ='root'; //usuario
			$passwd = 'Rangermiperro2.'; //contra




			$server=$driver.':host='.$host.';dbname='.$dbname;

			try {

				$conexion = new PDO($server,$username,$passwd);
				//$conexion = exec("SET CHARACTER SET utf8");
				$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$conexion->exec("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_general_ci'");
				$conexion->exec("SET SESSION sql_mode=''");
				
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
