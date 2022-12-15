<?php
//this php is to load all the data from my excel files to the website

//using the PHPExcel to enable the usage of excel files
require_once './Excel/PHPExcel/IOFactory.php';

//initialize the factor buttons
function initOperateType()
{
    return array(
        '1' => array('id' => 1, 'name' => 'House Price'),
        '2' => array('id' => 2, 'name' => 'Population'),
        '3' => array('id' => 3, 'name' => 'Restaurant'),
        '4' => array('id' => 4, 'name' => 'Safety'),
        '7' => array('id' => 7, 'name' => 'Temperature'),
        '8' => array('id' => 8, 'name' => 'School Rank'),
        '9' => array('id' => 9, 'name' => 'Class Size'),
        '10' => array('id' => 10, 'name' => 'Student Diversity'),
    );
}

#read data from final1.excel
function initFinal1Data()
{
    $dataKeys = array(
        'A' => 'City',
        'B' => 'AHP',
        'C' => 'Rest.',
        'D' => 'Pop.',
        'E' => 'YAvgH',
        'F' => 'YAvgL',
        'G' => 'vCrime',
        'H' => 'pCrime',
        'I' => 'School',
        'J' => 'Rank',
        'K' => 'S/F Ratio',
        'L' => '%asian',
        'M' => '%white',
        'N' => '%black',
        'O' => '%hispanic',
    );
    //load final1
    $fileName = '../files/final1.xlsx';
    $objPHPExcel = PHPExcel_IOFactory::load($fileName);
    $sheetCount = $objPHPExcel->getSheetCount();
    //only use the data from sheet0
    $sheetSelected = 0;
    $objPHPExcel->setActiveSheetIndex($sheetSelected);
    //get total number of rows
    $rowCount = $objPHPExcel->getActiveSheet()->getHighestRow();
    //get total number of columns
    $columnCount = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $final1Data = array();

    //loop thtough all the datas
    $index = 0;
    for ($row = 2; $row <= $rowCount; $row++) {
        $cityName = $objPHPExcel->getActiveSheet()->getCell('A' . $row)->getValue();
        $final1Data[$cityName]['id'] = $index + 1;
        
        for ($column = 'A'; $column <= $columnCount; $column++) {
            $value = $objPHPExcel->getActiveSheet()->getCell($column . $row)->getValue();
            if (strstr($value, ',')) $value =  str_replace(',', '', $value);
            $final1Data[$cityName][$dataKeys[$column]] = $value;
        }
        ++$index;
    }
    return $final1Data;
}

//loading data from final2.xlsx
function initFinal2Data()
{
    $dataKeys = array(
        'A' => 'City',
        'B' => 'Rest.',
        'C' => 'Pop.',
        'D' => 'Rank',
        'E' => 'avgHousePrice',
        'F' => 'YAvgH',
        'G' => 'YAvgL',
        'H' => 'pCrime:pop',
        'I' => 'vCrime:pop',
        'J' => 'SFRatio',
        'K' => 'race std',
        'L' => 'ClassSize',
        'M' => 'school info',
        'N' => 'city info',
    );
    $fileName = '../files/final2.xlsx';
    $objPHPExcel = PHPExcel_IOFactory::load($fileName);
    $sheetCount = $objPHPExcel->getSheetCount();

    $sheetSelected = 0;
    $objPHPExcel->setActiveSheetIndex($sheetSelected);

    $rowCount = $objPHPExcel->getActiveSheet()->getHighestRow();

    $columnCount = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $final2Data = array();

    //loop thtough all the datas
    $index = 0;
    for ($row = 2; $row <= $rowCount; $row++) {
        $cityName = $objPHPExcel->getActiveSheet()->getCell('A' . $row)->getValue();
        $final2Data[$cityName]['id'] = $index + 1;
       
        for ($column = 'A'; $column <= $columnCount; $column++) {
            $value = $objPHPExcel->getActiveSheet()->getCell($column . $row)->getValue();
            if (strstr($value, ',')) $value =  str_replace(',', '', $value);
            $final2Data[$cityName][$dataKeys[$column]] = $value;
        }
        ++$index;
    }
    return $final2Data;
}

//loading data from index.xlsx
function initIndexData()
{
    $dataKeys = array(
        'A' => 'Population',
        'B' => 'avgHousePrice',
        'C' => 'yearlyAvgHigh',
        'D' => 'yearlyAvgLow',
        'E' => 'p:p',
        'F' => 'v:p',
        'G' => 'Race std',
        'H' => 'classSize',
    );
    $fileName = '../files/index.xlsx';
    
    $objPHPExcel = PHPExcel_IOFactory::load($fileName);
    
    $sheetCount = $objPHPExcel->getSheetCount();
   
    $sheetSelected = 0;
    $objPHPExcel->setActiveSheetIndex($sheetSelected);
    
    $rowCount = $objPHPExcel->getActiveSheet()->getHighestRow();
    
    $columnCount = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $indexData = array();
    
    $index = 0;
    for ($row = 2; $row <= $rowCount; $row++) {
        $indexData[$index]['id'] = $index + 1;
        for ($column = 'A'; $column <= $columnCount; $column++) {
            $indexData[$index][$dataKeys[$column]] = $objPHPExcel->getActiveSheet()->getCell($column . $row)->getValue();
        }
        ++$index;
    }
    return $indexData;
}