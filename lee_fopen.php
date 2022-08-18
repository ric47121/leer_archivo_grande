<?php
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Origin: https://taxi-a-ezeiza.com.ar");
// header("Access-Control-Allow-Origin: taxi-a-ezeiza.com.ar");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: token, Content-Type');
header('Access-Control-Max-Age: 1728000');

$buscar = (isset($_REQUEST['code']))? $_REQUEST['code']:'01180'; 
$buscar = intval($buscar);

if ($buscar == 0) {
	die(json_encode(['status'=>'invalido']));
}

$handle = fopen("CPdescarga.txt", "r");

// $lineNumber = 1;
$arr = [];
while (($raw_string = fgets_utf8($handle)) !== false) {
    
	if(stristr($raw_string, "|")){

		$parts = explode("|", $raw_string);
		if(intval($parts[0]) == $buscar ){

			$arr['zip_code'] = $parts[0];
			// die(json_encode($arr));
			$arr['locality'] = procesar_str($parts[5]);
			// $arr['locality'] = utf8_encode("uuñ");
// die(json_encode($arr));			
			$federal_entity = [];
			$federal_entity['key'] = procesar_int($parts[7]);
			$federal_entity['name'] = procesar_str($parts[4]);
			$federal_entity['code'] = procesar_int($parts[9]);

			$arr['federal_entity'] = $federal_entity;

// die(json_encode($arr));

			// $j=$i;
			// $parts_2 = explode("|", $lines[$j]);
			$parts_2 = explode("|", $raw_string);
			$settlements = [];
			// while( $j < count($lines) && trim($parts_2[0]) == $buscar ){
			while (($raw_string_2 = fgets_utf8($handle)) !== false && trim($parts_2[0]) == $buscar) {
					$data = [];
					$data['key'] = procesar_int($parts_2[12]);
					$data['name'] = procesar_str($parts_2[1]);
					$data['zone_type'] = procesar_str($parts_2[13]);


					$settlement_type = [];
					$settlement_type['name'] = procesar_str_sin_uppercase($parts_2[2]);
					$data['settlement_type'] = $settlement_type;

					$settlements[] = $data;
					// $j++;
					// $parts_2 = explode("|", $lines[$j]);
					$parts_2 = explode("|", $raw_string_2);
			}

			$arr['settlements'] = $settlements;


			$municipality = [];
			$municipality['key'] = procesar_int($parts[11]);
			$municipality['name'] = procesar_str($parts[3]);

			$arr['municipality'] = $municipality;

			$encontro = true;
			break;
		}

	}else{
		continue;
	}

    // $lineNumber++;
    // if($lineNumber >= 500) 
		// die(json_encode(['lineNumber'=>$lineNumber]));
}

fclose($handle);


	
echo json_encode($arr);

function fgets_utf8($fn) {
     $content = fgets($fn);
      return mb_convert_encoding($content, 'UTF-8',
          mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}


function procesar_int($s){
		return	($s)? intval($s):null;
}

function procesar_str($cadena){
	$cadena = eliminar_acentos($cadena);
	$cadena = strtoupper($cadena);
	$cadena = utf8_encode($cadena);

	return ($cadena);
}

function procesar_str_sin_uppercase($cadena){
	$cadena = eliminar_acentos($cadena);
	// $cadena = strtoupper($cadena);
	$cadena = utf8_encode($cadena);

	return ($cadena);
}

function eliminar_acentos($cadena){
		
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );

		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}

?>