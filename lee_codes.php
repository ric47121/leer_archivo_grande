<?php

$arch = file_get_contents('CPdescarga.txt');

$lines = explode("\n", $arch);

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

// $arr = array_unique($arr);

// for ($i=0; $i < count($arr) ; $i++) { 
// 	// code...
// 	echo $arr[$i];
// 	echo ",";

// 	if($i % 1000 == 0){
// 		echo "<br>";
// 	}

// }

// echo implode($arr, ',');
// print_r($arr);

$buscar = 9090;
if($pos = get_position_element($arr, count($arr), $buscar) ){
	echo "elemento ".$buscar." se encuentra en la linea ".$arr_lines[$pos]." del archivo";
}

// var_dump(get_position_element($arr, count($arr), 9090));

function get_position_element($sorted_arr, $n, $element){

	$i = 0;
	$start = 0;
	$end = $n - 1;

	while ($i < $n){
		// $middle = ($start + $end) / 2;
		$middle = intdiv($start + $end, 2);

		if ($sorted_arr[$middle] == $element){
			return $middle;
		}else if ($sorted_arr[$middle] < $element){
			$start = $middle + 1;
		}else{ 
			$end = $middle - 1;
		}
		$i += 1;
	}
	return false;
}