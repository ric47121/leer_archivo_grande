<?php

// $arch = file_get_contents('CPdescarga.txt');
$handle_ori = fopen("CPdescarga.txt", "r");
$handle = fopen("CPdescarga_indices.txt", "w") or die("Error creando archivo");

$lines = explode("\n", $arch);

$ultimo = 0;
// $search = '01080';
$position_init_line = ftell($handle_ori);
// var_dump($position_init);
// die();
// for ($i=0; $i < count($lines); $i++) { 
while (($raw_string = fgets($handle_ori)) !== false) {
	$position_end_line = ftell($handle_ori);

// 	var_dump($position_end_line);
// die();

	if (stristr($raw_string,'|')) {
		$parts = explode("|", $raw_string);
		if( strlen($parts[0]) > 0 && strlen($parts[0]) < 10 &&  is_numeric($parts[0]) && intval($parts[0]) != $ultimo ){
		// if( $parts[0] == $search ){

			// fwrite($handle, $parts[0].'|'.ftell($handle_ori)-strlen($raw_string)."\n");
			fwrite($handle, $parts[0].'|'.$position_init_line."\n");
			// die();
			// $arr[] = ($parts[0]);		
			$ultimo = intval($parts[0]);
		}	
	}

	$position_init_line = $position_end_line;
	
}

fclose($handle_ori);
fclose($handle);
die(json_encode(['status'=>'generado']));
// 1653
