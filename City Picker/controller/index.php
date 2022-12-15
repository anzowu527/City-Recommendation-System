<?php
//this php controls the reset button

require 'data.php';//getting the data
require 'common.php';//getting the logic behind buttons

session_start();
require 'home.php';//calling home php
require 'operateType.php';//call the button logic
require 'myCities.php';//myCities page controller

//start over and empty everything
if (!empty($_GET['is_clear'])) {

    //reset the filtered data, and the buttons
    if (!empty($_SESSION['operated_types'])) unset($_SESSION['operated_types']);
    if (!empty($_SESSION['last_operated_type'])) {
        if (!empty($_SESSION['result_city_' . intval($_SESSION['last_operated_type'])])) unset($_SESSION['result_city_' . intval($_SESSION['last_operated_type'])]);
        unset($_SESSION['last_operated_type']);
    }
    if (!empty($_SESSION['cur_operate_type'])) unset($_SESSION['cur_operate_type']);

    //reset added cities
    if (!empty($_SESSION['my_cities'])) unset($_SESSION['my_cities']);

    echo json_encode(array('code' => 200, 'msg' => 'SUCCESS'));
    exit;
}


$param = $_POST;
$className = trim($param['className']);
$actionName = trim($param['actionName']);
$obj = new  $className();
$result = $obj->$actionName();
echo json_encode($result);
exit;

