#!/usr/bin/php -q
<?php
set_time_limit(30);
$param_error_log = '/tmp/notas.log';
$param_debug_on = 1;

$agi_mode = $argv[0]

require('phpagi.php');
$agi = new AGI();
$agi->answer();
sleep(1);
$agi->exec_agi("googletts.agi,\"Bienvenido al sistema de ayuda. \",es");
sleep(1);
require("definiciones.inc");
$link = mysql_connect(HOST, USUARIO,CLAVE); 
mysql_select_db(DB, $link); 
$result = mysql_query("SELECT nombre FROM libros", $link); 

while ($row = mysql_fetch_array($result)){ 
	$agi->exec_agi("googletts.agi,\"".$row['cedula']."\",es");
	sleep(1);
} 

$agi->exec_agi("googletts.agi,\"Gracias por utilizar el sitema de audiorespuesta\",es");
sleep(1);
$agi->exec_agi("googletts.agi,\"Hasta pronto\",es");

$agi->hangup();