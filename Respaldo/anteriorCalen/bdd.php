<?php
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=ditechon_sistema;charset=utf8', 'ditechon_hector', 'Rangermiperro1');
}
catch(Exception $e)
{
        die('Error : '.$e->getMessage());
}
