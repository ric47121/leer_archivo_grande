<?php
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Origin: https://taxi-a-ezeiza.com.ar");
// header("Access-Control-Allow-Origin: taxi-a-ezeiza.com.ar");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: token, Content-Type');
header('Access-Control-Max-Age: 1728000');

// die(json_encode(['status'=>'paso 1']));

$arch = file_get_contents_utf8('CPdescarga.txt');

die(json_encode(['status'=>'paso 2']));


$lines = explode("\n", $arch);

$buscar = (isset($_REQUEST['code']))? $_REQUEST['code']:'01180'; 
// $buscar = $_REQUEST['code'];
// die($buscar);

// {"zip_code":"01050","locality":"CIUDAD DE MEXICO","federal_entity":{"key":9,"name":"CIUDAD DE MEXICO","code":null},"settlements":[{"key":16,"name":"EX-HACIENDA DE GUADALUPE CHIMALISTAC","zone_type":"URBANO","settlement_type":{"name":"Colonia"}}],"municipality":{"key":10,"name":"ALVARO OBREGON"}}

// d_codigo	|d_asenta	|d_tipo_asenta	|D_mnpio			|d_estado			|d_ciudad			|d_CP		|c_estado	|c_oficina	|c_CP	|c_tipo_asenta	|c_mnpio	|id_asenta_cpcons	|d_zona		|c_cve_ciudad
// 0 		| 1 		| 2 			| 3 				| 4 				| 5					| 6 		| 7 		| 8 		| 9 	| 10 			| 11 		| 12				| 13 		| 14
// 01050	|Ex-Haci..	|Colonia		|Álvaro Obregón		|Ciudad de México	|Ciudad de México	|01001		|09			|01001		|		|09				|010 		|0016 				|Urbano 	|01

// 01180	|Carola		|Colonia		|Álvaro Obregón		|Ciudad de México	|Ciudad de México	|01131		|09			|01131		|		|09				|010 		|0076 				|Urbano 	|01
// 01180	|8 de Agosto|Colonia		|Álvaro Obregón		|Ciudad de México	|Ciudad de México	|01131		|09			|01131		|		|09				|010 		|0077 				|Urbano 	|01
// 01180	|San Pedro..|Colonia		|Álvaro Obregón		|Ciudad de México	|Ciudad de México	|01131		|09			|01131		|		|09				|010 		|0078 				|Urbano 	|01


// if (!is_numeric($buscar)) {
	die(json_encode(['status'=>'invalido']));
// }


$arr = [];
$arr_lines = [];
$ultimo = 0;

for ($i=0; $i < count($lines); $i++) { 
	// code...
	$parts = explode("|", $lines[$i]);
	// $viendo = 0;
	if( strlen($parts[0]) > 0 && strlen($parts[0]) < 10 &&  is_numeric($parts[0]) && intval($parts[0]) != $ultimo ){

		$arr[] = intval($parts[0]);		
		$arr_lines[] = $i;		
		$ultimo = intval($parts[0]);
	}
}

if($pos = get_position_element($arr, count($arr), $buscar) ){
	echo "elemento ".$buscar." se encuentra en la linea ".$arr_lines[$pos]." del archivo";
}else{

}

$encontro = false;
for ($i=0; $i < count($lines); $i++) { 
	$parts = explode("|", $lines[$i]);
	if(trim($parts[0]) == $buscar ){



		$arr['zip_code'] = $buscar;
		$arr['locality'] = procesar_str($parts[5]);
		// $arr['locality'] = utf8_encode("uuñ");
		
		$federal_entity = [];
		$federal_entity['key'] = procesar_int($parts[7]);
		$federal_entity['name'] = procesar_str($parts[4]);
		$federal_entity['code'] = procesar_int($parts[9]);

		$arr['federal_entity'] = $federal_entity;



		$j=$i;
		$parts_2 = explode("|", $lines[$j]);
		$settlements = [];
		while( $j < count($lines) && trim($parts_2[0]) == $buscar ){

				$data = [];
				$data['key'] = procesar_int($parts_2[12]);
				$data['name'] = procesar_str($parts_2[1]);
				$data['zone_type'] = procesar_str($parts_2[13]);


				$settlement_type = [];
				$settlement_type['name'] = procesar_str_sin_uppercase($parts_2[2]);
				$data['settlement_type'] = $settlement_type;

				$settlements[] = $data;
				$j++;
				$parts_2 = explode("|", $lines[$j]);

		}

		$arr['settlements'] = $settlements;


		$municipality = [];
		$municipality['key'] = procesar_int($parts[11]);
		$municipality['name'] = procesar_str($parts[3]);

		$arr['municipality'] = $municipality;

		$encontro = true;
		break;
	}
}



	echo json_encode($arr);

function file_get_contents_utf8($fn) {

     $content = file_get_contents($fn);

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