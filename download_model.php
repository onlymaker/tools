<?php

ini_set('memory_limit', '1024M');;

require_once __DIR__ . '/index.php';

$sql = "SELECT DISTINCT model,images FROM order_item o, prototype p WHERE o.prototype_id = p.ID AND p.manufactory != '现货鞋' LIMIT 10";

$results = \data\OMS::instance()->exec($sql);

$excel = new \PHPExcel();

$excel->getProperties()
    ->setCreator('jibo')
    ->setTitle('Product Model')
    ->setSubject('Product Model')
    ->setDescription('Product Model in Sale @ ' . date('Y-m-d'));

$excel->setActiveSheetIndex(0);

$sheet = $excel->getActiveSheet();

$iterator = $sheet->getRowIterator(1);

$cell = $iterator->current()->getCellIterator();
$cell->current()->setValue('产品型号');
$cell->next();
$cell->current()->setValue('图片');

$iterator->next();

foreach ($results as $result) {
    $cell = $iterator->current()->getCellIterator();
    $cell->current()->setValue($result['model'])->setDataType(\PHPExcel_Cell_DataType::TYPE_STRING);
    $cell->next();
    try {
        if ($result['images']) {
            $image = explode(',', $result['images'])[0];
            echo 'process image ', $image, PHP_EOL;
            $local = '/tmp/' . basename($image);
            if (!file_exists($local)) {
                @file_put_contents($local, file_get_contents($image));
            }
            if (file_exists($local)) {
                $drawing = new \PHPExcel_Worksheet_Drawing();
                $drawing->setPath($local);
                $drawing->setHeight(100);
                $drawing->setCoordinates($cell->current()->getCoordinate());
                $drawing->getShadow()->setVisible(true);
                $drawing->setWorksheet($sheet);
                $sheet->getRowDimension($iterator->current()->getRowIndex())->setRowHeight(100);
                $drawing = null;
            }
        }
    } catch (\Exception $e) {
        echo "Handle $image error ", $e->getTraceAsString();
    }
    $iterator->next();
}

$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setWidth(25);

$writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$dir = __DIR__ . '/downloads';

if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$writer->save($dir . '/model.xlsx');
