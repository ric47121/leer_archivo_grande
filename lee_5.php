<?php 



$handle = fopen("CPdescarga.txt", "r");


$lineNumber = 1;

while (($raw_string = fgets($handle)) !== false) {

    $row = str_getcsv($raw_string);


    var_dump($row);
    

    $lineNumber++;
}

fclose($handle);


?>