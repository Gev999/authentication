<?php

function transposition($data) {
    $key_sz = 3;
    $encryptedData = '';
    for ($i = 0; $i < $key_sz; $i++) {
        for ($j = $i; $j < strlen($data); $j+=$key_sz) {
            $encryptedData .= $data[$j];
        }
    }
    return $encryptedData;
}

function addCrc($data){
    $c=0;
    for($i=0;$i<strlen($data);++$i){
        if($data[$i]==='1')
            ++$c;
    }
    return changeTo16("$data".decbin($c));
}

//--------------------------------------------------------------

function changeTo16($data) {
    $dataLength = strlen($data);
    $residual = $dataLength % 4;
    $substring = '';
    if ($residual != 0) {
        for ($i = 0; $i < 4 - $residual; $i++) {
            $substring .= '0';
        }
    }
    $data = $substring . $data;
    $newData = '';
    for ($i = 0; $i < strlen($data); $i+= 4) {
        $str = substr($data, $i, 4);
        switch($str) {
            case '0000': $newData.= '0'; break;
            case '0001': $newData.= '1'; break;
            case '0010': $newData.= '2'; break;
            case '0011': $newData.= '3'; break;
            case '0100': $newData.= '4'; break;
            case '0101': $newData.= '5'; break;
            case '0110': $newData.= '6'; break;
            case '0111': $newData.= '7'; break;
            case '1000': $newData.= '8'; break;
            case '1001': $newData.= '9'; break;
            case '1010': $newData.= 'A'; break;
            case '1011': $newData.= 'B'; break;
            case '1100': $newData.= 'C'; break;
            case '1101': $newData.= 'D'; break;
            case '1110': $newData.= 'E'; break;
            case '1111': $newData.= 'F'; break;
        }
    }
    return $newData;
}

function connectDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "authentication";
    
    return new mysqli($servername, $username, $password, $database);
}