<?php
//this php is for the home page 
class Home{

    #function getting the banner pictures
    public function getHomeBanner()
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

    #function getting the County image
    function getHomeImages()
    {
        $images = array(
            './images/home_images/banner01.jpg',
            './images/home_images/banner02.jpg',
            './images/home_images/banner03.jpg',
            './images/home_images/banner04.jpg',
            './images/home_images/banner05.jpg',
            './images/home_images/banner06.jpg',
        );
        return $images;
    }

    #function getting the data for the factor buttons
    public function getOperateData()
    {
        $data = array(
            'typeArray' => initOperateType(),
            'operated_types' => !empty($_SESSION['operated_types']) ? $_SESSION['operated_types'] : array()
        );
        return array('code' => 200, 'msg' => 'Success', 'data' => $data);
    }


    public function initHomeData()
    {
        $data = array(
            'typeArray' => initOperateType(),
            'images' => $this->getHomeImages(),
            'operated_types' => !empty($_SESSION['operated_types']) ? $_SESSION['operated_types'] : array()
        );

        return array('code' => 200, 'msg' => 'Success', 'data' => $data);
    }

}