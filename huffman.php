<?php
function fillDictionary(&$dictionary, $data, $value = '') {
    if (!is_array($data[0][1])) {
        $dictionary[$data[0][1]] = $value.'0';
    } else {
        fillDictionary($dictionary, $data[0][1], $value.'0');
    }
    if (isset($data[1])) {
        if (!is_array($data[1][1])) {
            $dictionary[$data[1][1]] = $value.'1';
        } else {
            fillDictionary($dictionary, $data[1][1], $value.'1');
        }
    }
}
function huffmannEncode($string) {
    $originalString = $string;
    $occurences = array();

    while (isset($string[0])) {
        $occurences[] = array(substr_count($string, $string[0]), $string[0]);
        $string = str_replace($string[0], '', $string);
    }

    sort($occurences);
    while (count($occurences) > 1) {
        $row1 = array_shift($occurences);
        $row2 = array_shift($occurences);
        $occurences[] = array($row1[0] + $row2[0], array($row1, $row2));
        sort($occurences);
    }

    // $dictionary is an array that gets filled with the values with a recursive method
    $dictionary = [];
    fillDictionary($dictionary, is_array($occurences[0][1]) ? $occurences[0][1] : $occurences);

    // Generate the final encoded message
    $encoded = '';
    for($i = 0; $i < strlen($originalString); $i++) {
        $encoded .= $dictionary[$originalString[$i]];
    }
    return $encoded;
}
//echo huffmannEncode('hello world');
?>