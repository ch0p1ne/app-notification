<?php
$db_host = "localhost";
$db_name = "sensei5y_afro_feewi";
$db_user = "sensei5y_root";
$db_pass = "Pass*2018";
$db  = new PDO( "mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass );
  
$db->exec('SET NAMES utf8');
?>