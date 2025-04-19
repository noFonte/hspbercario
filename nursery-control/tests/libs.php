<?php



function dd($obj){
    print_r(["<pre>",$obj,"<pre>"]);die;
}

function generateRandomDateTime($startYear = 2000, $endYear = 2030) {
    // Generate random timestamps between January 1st of the start year and December 31st of the end year
    $startTimestamp = strtotime("{$startYear}-01-01 00:00:00");
    $endTimestamp = strtotime("{$endYear}-12-31 23:59:59");

    // Generate a random timestamp within the range
    $randomTimestamp = rand($startTimestamp, $endTimestamp);

    // Format the date as: dd/mm/yyyy hh:mm:ss
    return date("d/m/Y H:i:s", $randomTimestamp);
}

