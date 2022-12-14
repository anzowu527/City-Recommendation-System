<?php
//remember the buttons that been clicked
function recordOperatedType()
{
    $params = $_POST;
    if (empty($_SESSION['operated_types'])) {
        $_SESSION['operated_types'] = array();
    }
    //remember the previous operated type
    $_SESSION['last_operated_type'] = !empty($_SESSION['cur_operate_type']) ? $_SESSION['cur_operate_type'] : 0;

    //record current operated type
    $_SESSION['cur_operate_type'] = intval($params['operate_type']);

    if (intval($params['operate_type']) && empty($_SESSION['operated_types'][intval($params['operate_type'])])) {

        $operateTypes = initOperateType();
        //keeping track of the cliked buttons
        $_SESSION['operated_types'][intval($params['operate_type'])] = $operateTypes[intval($params['operate_type'])];

    }
    return array('code' => '200', 'msg' => 'SUCCESS');

}

//this function will be used when we want an ascending sorting
function getArraySort($data, $index, $sortType = SORT_ASC)
{
    $temp = array();
    foreach ($data as $item) {
        $temp[] = $item[$index];
    }
    array_multisort($temp, $sortType, $data);
    return $data;
}

//sorting for different cases
//setting up the columns that will pop out after each choice correspondingly
function getColumnByOperate($operateId = 0)
{
    $columns = array();
    switch ($operateId) {
        case '1':
            $columns[] = 'AHP';
            break;
        case '2':
            $columns[] = 'Pop.';
            break;
        case '3':
            $columns[] = 'Rest.';
            break;
        case '4':
            $columns[] = 'vCrime';
            $columns[] = 'pCrime';
            break;
        case '7':
            $columns[] = 'YAvgH';
            $columns[] = 'YAvgL';
            break;
        case '8':
            $columns[] = 'Rank';
            break;
        case '9':
            $columns[] = 'S/F Ratio';
            break;
        case '10':
            $columns[] = '%asian';
            $columns[] = '%white';
            $columns[] = '%black';
            $columns[] = '%hispanic';
            break;
    }
    return $columns;
}

//function that used to combine final1 and final2 into one array, so that it becomes easier to work with
function array_multi_merge($arr1 = array(), $arr2 = array())
{
    $result = array();
    if (empty($arr1)) {
        return $result;
    }
    if (empty($arr2)) {
        return $arr1;
    }
    foreach ($arr1 AS $index => $arr) {
        $temp = !empty($arr2[$index]) ? $arr2[$index] : array();
        $result[$index] = array_merge($arr, $temp);
    }
    return $result;
}

//combining the 2 excels
function getTotalData()
{
    $final1 = initFinal1Data();
    $final2 = initFinal2Data();
    return array_multi_merge($final1, $final2);
}