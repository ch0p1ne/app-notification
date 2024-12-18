<?php
// $db_host = "localhost";
// $db_name = "hotel_zeina";
// $db_user = "root";
// $db_pass = "password";

// try{
	
	// $connect = new PDO ("mysql:host={$db_host}; dbname={$db_name}", $db_user, $db_pass);
	// $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// }

// catch(PDOException $e) {
	// echo $e->getMessage();
// }

// $connect->exec('SET NAMES utf8');

try {
     // first connect to database with the PDO object. 
     $connect = new \PDO("mysql:Host=localhost;dbname=sensei5y_afro_feewi;charset=utf8", "root", "password*#21", [
       PDO::ATTR_EMULATE_PREPARES => false, 
       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
     ]); 
 } catch(\PDOException $e){
     // if connection fails, show PDO error. 
   echo "Error connecting to mysql: " . $e->getMessage();
 }
 $connect->exec('SET NAMES utf8');
?>
 
 
 