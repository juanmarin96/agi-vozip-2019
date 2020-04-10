#!/usr/bin/php -q
<?php
require("variables.inc");
set_time_limit(120);
$param_error_log = '/tmp/notas.log';
$param_debug_on = 1;
require('phpagi.php');
$agi = new AGI();
$agi->answer();
$option = $agi->get_variable('IVR_DIGIT_PRESSED');
$agi->verbose($option);

if($option['result'] == "1" && $option['data'] == "1"){
	$agi->exec_agi("googletts.agi,\"Por favor digite su número de identificación después del tono\",es");
	$id = $agi->get_data('beep', 6000, 10)['result'];
	$agi->verbose($id);
	$link = mysql_connect(HOST, USUARIO,CLAVE); 
	mysql_select_db(DB, $link); 
	$result = mysql_query("SELECT cedula FROM solicitudes WHERE cedula='$id'", $link); 
	while ($row = mysql_fetch_array($result)){ 
		$estado = ($row['estado']== 0)? 'Pendiente':'Aprobado';
		$agi->exec_agi("googletts.agi,\"La solicitud para la cédula " .$row['cedula']." esta en estado ". $estado." \",es");
		sleep(1);
		$agi->exec_agi("googletts.agi,\"Pronto nos comunicaremos con usted\",es");
	}

}else{
	$agi->exec_agi("googletts.agi,\"Registro en proceso\",es");
	sleep(1);

	$agi->exec_agi("googletts.agi,\"Por favor digite su número de identificación después del tono\",es");
	$id = $agi->get_data('beep', 3000, 10)['result'];


	$agi->exec_agi("googletts.agi,\"Por favor digite su edad después del tono\",es");
	$age =  $agi->get_data('beep', 3000, 2)['result'];;


	$hoods = array(
		'1'=>'*Presione 1 si vive en guayabal',
		'2'=>'*Presione 2 si vive en el poblado',
		'3'=>'*Presione 3 si vive en castilla',
	);

	$hood = $agi->menu($hoods, 3000);

	switch ($hood) {
		case "1":
			$hood = "Guayabal";
			break;
		case "2":
			$hood = "El poblado";
			break;
		case "3":
			$hood = "Castilla";
			break;
	}

	$agi->verbose($hood);
	sleep(1);

	$sql = "INSERT INTO solicitudes (cedula, edad, barrio) VALUES ('$id', '$age', '$hood')";
	$agi->verbose($sql);
	$link = mysql_connect(HOST, USUARIO,CLAVE); 
	mysql_select_db(DB, $link); 
	if(mysql_query("INSERT INTO solicitudes (cedula, edad, barrio) VALUES ('$id', '$age', '$hood')", $link)){
		$agi->exec_agi("googletts.agi,\"Su solicitud ha sido registrada con éxito\",es");
		$agi->exec_agi("googletts.agi,\"Pronto nos comunicaremos con usted\",es");
	}
}

$agi->exec_agi("googletts.agi,\"Gracias por utilizar el sitema de ayuda por Covid 19\",es");
$agi->exec_agi("googletts.agi,\"Hasta pronto\",es");

$agi->hangup();