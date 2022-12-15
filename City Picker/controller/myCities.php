<?php
//this php controlls the myCities page
class MyCities{

    //get banner pictures
    public function getMyCitiesBanner()
    {
        $banners = array(
            './images/banners/banner01.jpg',
            './images/banners/banner02.jpg',
            './images/banners/banner03.jpg',
            './images/banners/banner04.jpg',
            './images/banners/banner05.jpg',
            './images/banners/banner06.jpg',
        );
        return array('code' => 200, 'msg' => 'Success', 'data' => $banners);
    }

    public function getMyCities()
    {
        if (empty($_SESSION['my_cities'])) {
            $_SESSION['my_cities'] = array();
        }
        $myCities = array();
        if (!empty($_SESSION['my_cities'])) {
            $cityList = getTotalData();
            foreach ($cityList AS $city) {
                if (!in_array($city['City'], $_SESSION['my_cities'])) continue;
                //the added cities are stored in this array
                $myCities[] = $city;

            }
        }
        return array('code' => 0, 'msg' => 'Success', 'data' => $myCities);
    }
} 