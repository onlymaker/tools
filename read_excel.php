<?php

ini_set('memory_limit', '1024M');;

require_once __DIR__ . '/index.php';

$excel = \PHPExcel_IOFactory::load('/tmp/excel.xlsx');
$sheet = $excel->getSheet(0);
$rows = $sheet->toArray();
foreach ($rows as $i => $data) {
    echo "UPDATE prototype SET cost=$data[1] WHERE model='$data[0]';", PHP_EOL;
}