<?php
//this php controls the logic behind clicking the factor buttons 
//also the logic behind each filter buttons that under the factor buttons

class operateType{

    //keeping track of what buttons have been clicked
    public function addOperateTypeToRecord()
    {
        $result = recordOperatedType();
        return $result;
    }


    //this function displays the columes that will come out after each factor buttons were clicked
    public function getDataByOperate()
    {
        if (empty($_SESSION['operated_types'])) {
            $operateTypeArray = initOperateType();
            $_SESSION['operated_types'] = array(intval($_POST['operateType']) => $operateTypeArray[intval($_POST['operateType'])]['name']);

        }
        $keyNames = array();
        foreach ($_SESSION['operated_types'] AS $operateType => $operateName) {
            $columns = getColumnByOperate(intval($operateType));
            $keyNames = array_merge($keyNames, $columns);
        }
        if (empty($keyNames)) {
            return array('code' => '0', 'msg' => 'SUCCESS', 'data' => array());
        }

        $lastOperateType = !empty($_SESSION['last_operated_type']) ? $_SESSION['last_operated_type'] : 0;
        $totalData = getTotalData();
        //only filter the data that are leftover from the previous filtering 
        $cacheName = 'result_city_' . $lastOperateType;
        if (!empty($_SESSION[$cacheName])) {
            foreach ($totalData AS $index => $info) {
                if (!in_array($info['City'], $_SESSION[$cacheName])) {
                    unset($totalData[$index]);
                }
            }

        }

        //keeping track of the data that came out after clicking a button 
        if (intval($_POST['operateType'])) {
            //data that came out after clicking a factor button
            $totalData = $this->getSelectData(intval($_POST['operateType']), intval($_POST['selectType']), $totalData);
        }

        $_SESSION['result_city_' . intval($_POST['operateType'])] = !empty($totalData) ? array_column($totalData, 'City') : array();
        $tmpArr = $result = array();
        
        foreach ($keyNames AS $keyName) {
            foreach ($totalData AS $cityName => $datum) {
                $tmpArr[$cityName][$keyName] = $datum[$keyName];
            }
        }

        foreach ($tmpArr AS $cityName => $tmp) {
            $result[] = array_merge(array('City' => $cityName), $tmp);
        }
        $myCities = !empty($_SESSION['my_cities']) ? $_SESSION['my_cities'] : array();
        return array('code' => '200', 'msg' => 'SUCCESS', 'data' => array('cities' => $result, 'myCities' => $myCities));
    }


    //returning the data that been filtered out after clicking the specific factor button
    public function getSelectData($operateType = 0, $selectType = 0, $data = array())
    {
        $result = array();
        switch ($operateType) {
            case '1'://house price
                $result = $this->selectHousePriceData($selectType, $data);
                break;
            case '2'://population
                $result = $this->selectPopulationData($selectType, $data);
                break;
            case '3'://Restaurant
                $result = $this->selectRestaurantData($selectType, $data);
                break;
            case '4'://Safety
                $result = $this->selectSaferData($selectType, $data);
                break;
            case '7'://temperature
                $result = $this->selectTemperatureData($selectType, $data);
                break;
            case '8'://school rank
                $result = $this->selectSchoolRankData($selectType, $data);
                break;
            case '9'://class size
                $result = $this->selectClassSizeData($selectType, $data);
                break;
            case '10'://student diversity
                $result = $this->selectStudentDiversityData($selectType, $data);
                break;
        }
        return $result;
    }


    //Temperature factor filtering logic
    public function selectTemperatureData($selectType, $data)
    {
        if (empty($data)) {
            return array();
        }
        $indexData = initIndexData();
        $result = array();

        //colder
        if (!$selectType) { 
            foreach ($data AS $item) {
                if ($item['YAvgH'] < $indexData[0]['yearlyAvgHigh'] && $item['YAvgL'] < $indexData[0]['yearlyAvgLow']) {
                    $result[$item['City']] = $item;
                }
            }
        } 
        //average te,p
        elseif ($selectType == 1) {
            foreach ($data AS $item) {
                if ($indexData[0]['yearlyAvgHigh'] <= $item['YAvgH'] &&
                    $item['YAvgH'] <= $indexData[1]['yearlyAvgHigh'] &&
                    $indexData[0]['yearlyAvgLow'] <= $item['YAvgL'] &&
                    $item['YAvgL'] <= $indexData[1]['yearlyAvgLow']
                ) {
                    $result[$item['City']] = $item;
                }
            }
        } 
        //warmer
        else { 
            foreach ($data AS $item) {
                if ($item['YAvgH'] >= $indexData[1]['yearlyAvgHigh'] && $item['YAvgL'] >= $indexData[1]['yearlyAvgLow']) {
                    $result[$item['City']] = $item;
                }
            }
        }
        if ($_POST['sortColumn']) {
            $sortType = !empty($_POST['sortType']) ? SORT_DESC : SORT_ASC;
            $result = getArraySort($result, $_POST['sortColumn'], $sortType);
        }
        return $result;
    }

    //safer factor filtering logic
    public function selectSaferData($selectType, $data)
    {
        if (empty($data)) {
            return array();
        }
        $indexData = initIndexData();//call data from index.xlsx
        $result = array();

        //relatively safer
        if (!$selectType) { 
            foreach ($data AS $item) {
                if ($item['pCrime:pop'] < $indexData[0]['p:p'] && $item['vCrime:pop'] < $indexData[0]['v:p']) {
                    $result[$item['City']] = $item;
                }
            }
        } 
        //relatively challenging
        else { 
            foreach ($data AS $item) {
                if ($item['pCrime:pop'] >= $indexData[0]['p:p'] && $item['vCrime:pop'] >= $indexData[0]['v:p']) {
                    $result[$item['City']] = $item;
                }
            }
        }
        if ($_POST['sortColumn']) {
            $sortType = !empty($_POST['sortType']) ? SORT_DESC : SORT_ASC;
            $result = getArraySort($result, $_POST['sortColumn'], $sortType);
        }
        return $result;
    }

    //population factor filtering logic
    public function selectPopulationData($selectType, $data)
    {
        if (empty($data)) {
            return array();
        }
        $indexData = initIndexData();
        $result = array();

        //small city
        if (!$selectType) { 
            foreach ($data AS $item) {
                if ($item['Pop.'] <= $indexData[0]['Population']) {
                    $result[$item['City']] = $item;
                }
            }
        } 

        //medium city
        elseif ($selectType == 1) {
            foreach ($data AS $item) {
                if ($item['Pop.'] > $indexData[0]['Population'] && $item['Pop.'] < $indexData[1]['Population']) {
                    $result[$item['City']] = $item;
                }
            }
        } 
        //large city
        else { 
            foreach ($data AS $item) {
                if ($item['Pop.'] >= $indexData[1]['Population']) {
                    $result[$item['City']] = $item;
                }
            }
        }
        if ($_POST['sortColumn']) {
            $sortType = !empty($_POST['sortType']) ? SORT_DESC : SORT_ASC;
            $result = getArraySort($result, $_POST['sortColumn'], $sortType);
        }
        return $result;
    }

    //house price factor filtering logic
    public function selectHousePriceData($selectType, $data)
    {
        if (empty($data)) {
            return array();
        }
        $indexData = initIndexData();
        $result = array();
        //small city
        if (!$selectType) { 
            foreach ($data AS $item) {
                if ($item['AHP'] <= $indexData[0]['avgHousePrice']) {
                    $result[$item['City']] = $item;
                }
            }
        } 
        //medium city
        elseif ($selectType == 1) {
            foreach ($data AS $item) {
                if ($item['AHP'] > $indexData[0]['avgHousePrice'] && $item['AHP'] < $indexData[1]['avgHousePrice']) {
                    $result[$item['City']] = $item;
                }
            }
        } 
        //large city
        else {
            foreach ($data AS $item) {
                if ($item['AHP'] >= $indexData[1]['avgHousePrice']) {
                    $result[$item['City']] = $item;
                }
            }
        }
        $sortColumn = trim($_POST['sortColumn']) ? $_POST['sortColumn'] : 'AHP';
        $sortType = (intval($_POST['sortType'])) ? SORT_DESC : SORT_ASC;
        $result = getArraySort($result, $sortColumn, $sortType);
        return $result;
    }

    //restaurant factor filtering logic
    public function selectRestaurantData($selectType, $data)
    {
        if (empty($data)) {
            return array();
        }
//        $indexData = initIndexData();
        $result = $data;
        $sortColumn = trim($_POST['sortColumn']) ? $_POST['sortColumn'] : 'Rest.';
        $sortType = (intval($_POST['sortType']) || ($sortColumn == 'Rest.' && $selectType)) ? SORT_DESC : SORT_ASC;
        $result = getArraySort($result, $sortColumn, $sortType);
        return $result;
    }

    //school rank factor filtering logic
    public function selectSchoolRankData($selectType, $data)
    {
        if (empty($data)) {
            return array();
        }
        
        $result = $data;
        $sortColumn = trim($_POST['sortColumn']) ? $_POST['sortColumn'] : 'Rank';
        $sortType = (intval($_POST['sortType']) || ($sortColumn == 'Rank' && $selectType)) ? SORT_DESC : SORT_ASC;
        $result = getArraySort($result, $sortColumn, $sortType);
        return $result;
    }

    //class size factor filtering logic
    public function selectClassSizeData($selectType, $data)
    {
        if (empty($data)) {
            return array();
        }
        $indexData = initIndexData();
        $result = array();
        //small
        if (!$selectType) { 
            foreach ($data AS $item) {
                if ($item['ClassSize'] == 'Small Class Size') {
                    $result[$item['City']] = $item;
                }
            }
        } 
        //medium
        elseif ($selectType == 1) {
            foreach ($data AS $item) {
                if ($item['ClassSize'] == 'Medium Class Size') {
                    $result[$item['City']] = $item;
                }
            }
        } 
        //large
        else { 
            foreach ($data AS $item) {
                if ($item['ClassSize'] == 'Large Class Size') {
                    $result[$item['City']] = $item;
                }
            }
        }
        $sortColumn = trim($_POST['sortColumn']) ? $_POST['sortColumn'] : 'S/F Ratio';
        $sortType = (intval($_POST['sortType'])) ? SORT_DESC : SORT_ASC;
        $result = getArraySort($result, $sortColumn, $sortType);
        return $result;
    }

    //diversity factor filtering logic
    public function selectStudentDiversityData($selectType, $data)
    {
        if (empty($data)) {
            return array();
        }
        $indexData = initIndexData();
        $result = array();
        //less diverse
        if (!$selectType) { 
            foreach ($data AS $item) {
                if ($item['race std'] > $indexData[0]['Race std']) {
                    $result[$item['City']] = $item;
                }
            }
        } 
        //more diverse
        else { 
            foreach ($data AS $item) {
                if ($item['race std'] <= $indexData[0]['Race std']) {
                    $result[$item['City']] = $item;
                }
            }
        }
        if ($_POST['sortColumn']) {
            $sortType = !empty($_POST['sortType']) ? SORT_DESC : SORT_ASC;
            $result = getArraySort($result, $_POST['sortColumn'], $sortType);
        }
        return $result;
    }

}