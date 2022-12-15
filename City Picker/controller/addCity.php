<?php
//this php is used to control adding or removing cities to/from the mycities

session_start();
$type = intval($_POST['type']);
$city = trim($_POST['city']);//getting city ID
if (empty($_SESSION['my_cities'])) {
    $_SESSION['my_cities'] = array();
}
//add to mycities
if (!$type) {
    if (!$city) {
        echo json_encode(array('code' => 0, 'msg' => 'Error'));
        exit;
    }
    if (in_array($city, $_SESSION['my_cities'])) {
        echo json_encode(array('code' => 0, 'msg' => 'City already been added'));
        exit;
    }
    array_push($_SESSION['my_cities'], $city);
    echo json_encode(array('code' => 200, 'msg' => 'Successfully added'));

} 
//removed from mycites
else {
    if (empty($_SESSION['my_cities']) || !in_array($city, $_SESSION['my_cities'])) {
        echo json_encode(array('code' => 0, 'msg' => 'city has not been added'));
        exit;
    }
    $index = array_search($city, $_SESSION['my_cities']);
    unset($_SESSION['my_cities'][$index]);
    echo json_encode(array('code' => 200, 'msg' => 'Successfully removed'));
}
exit;