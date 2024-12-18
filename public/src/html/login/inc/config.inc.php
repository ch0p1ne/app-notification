<?php
$db_host = "localhost";
$db_name = "sensei5y_senshop_ps";
$db_user = "root";
$db_pass = "password*#21";
$db  = new PDO( "mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass );
  
$db->exec('SET NAMES utf8');
?>