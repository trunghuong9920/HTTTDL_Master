<?php
    $paPDO = initDB();
    $paSRID = '4326';
    if(isset($_POST['functionname']))
    {
        $paPoint = $_POST['paPoint'];
        $functionname = $_POST['functionname'];
        
        $aResult = "null";
        if ($functionname == 'getGeoProvinceToAjax')
            $aResult = getGeoProvinceToAjax($paPDO, $paSRID, $paPoint);
        if ($functionname == 'getGeoDistricToAjax')
            $aResult = getGeoDistricToAjax($paPDO, $paSRID, $paPoint);
        else if ($functionname == 'getInfoRailToAjax')
            $aResult = getInfoRailToAjax($paPDO , $paSRID , $paPoint);
        else if ($functionname == 'getInfoProvinceToAjax')
            $aResult = getInfoProvinceToAjax($paPDO , $paSRID , $paPoint);
        else if ($functionname == 'getInfoDistrictToAjax')
            $aResult = getInfoDistrictToAjax($paPDO , $paSRID , $paPoint);
        else if ($functionname == 'getRailToAjax')
            $aResult = getRailToAjax($paPDO , $paSRID , $paPoint) ;
        else if ($functionname == 'getInfoWaterWayToAjax')
            $aResult = getInfoWaterWayToAjax($paPDO , $paSRID , $paPoint);
        else if ($functionname == 'getWaterWayToAjax')
            $aResult = getWaterWayToAjax($paPDO , $paSRID , $paPoint) ;
        else if ($functionname == 'getInfoWaterToAjax')
            $aResult = getInfoWaterToAjax($paPDO , $paSRID , $paPoint);
        else if ($functionname == 'getWaterToAjax')
            $aResult = getWaterToAjax($paPDO , $paSRID , $paPoint) ;
        else if ($functionname == 'getInfoLandUseToAjax')
            $aResult = getInfoLandUseToAjax($paPDO , $paSRID , $paPoint);
        else if ($functionname == 'getLandUseToAjax')
            $aResult = getLandUseToAjax($paPDO , $paSRID , $paPoint) ;
        else if ($functionname == 'getInfoPlaceToAjax')
            $aResult = getInfoPlaceToAjax($paPDO , $paSRID , $paPoint);
        else if ($functionname == 'getPlaceToAjax')
            $aResult = getPlaceToAjax($paPDO , $paSRID , $paPoint) ;
        else if ($functionname == 'getInfoNaturalToAjax')
            $aResult = getInfoNaturalToAjax($paPDO , $paSRID , $paPoint);
        else if ($functionname == 'getNaturalToAjax')
            $aResult = getNaturalToAjax($paPDO , $paSRID , $paPoint) ;
        echo $aResult;
    
        closeDB($paPDO);
    }

    // search-------------
    if (isset($_POST['name_search'])) {
        $name = $_POST['values_search'];
        $name_search = $_POST['name_search'];
        $aResult = "null";

        if ($name_search == 'getGeoProvinceToAjax') {
            $aResult = seachCityProvince($paPDO, $paSRID, $name);
        }
        if ($name_search == 'getGeoDistricToAjax') {
            $aResult = seachCityDistrict($paPDO, $paSRID, $name);
        }
        if ($name_search == 'getInforProvinceToAjax') {
            $aResult = searchInforProvinceToAjax($paPDO, $paSRID, $name);
        }
        if ($name_search == 'getInforDistrictToAjax') {
            $aResult = searchInforDistrictToAjax($paPDO, $paSRID, $name);
        }
        if ($name_search == 'getInforRailToAjax') {
            $aResult = searchInforRailToAjax($paPDO, $paSRID, $name);
        }
        if ($name_search == 'getSearchRailToAjax') {
            $aResult = getSearchRailToAjax($paPDO, $paSRID, $name);
        }
        if ($name_search == 'getSearchInfoPlaceToAjax') {
            $aResult = getSearchInfoPlaceToAjax($paPDO, $paSRID, $name);
        }
        if ($name_search == 'getSearchGeoPlaceToAjax') {
            $aResult = getSearchGeoPlaceToAjax($paPDO, $paSRID, $name);
        }
        
        echo $aResult;
        closeDB($paPDO);

    }
   
    function initDB()
    {
        // K???t n???i CSDL
        $paPDO = new PDO('pgsql:host=localhost;dbname=THAILAND_MAP_DB;port=5433', 'postgres', '1');
        return $paPDO;

    }
    function closeDB($paPDO)
    {
        // Ng???t k???t n???i
        $paPDO = null;
        // if ($paPDO == null) {
        //     print("Ng???t k???t n???i");
        // }
    }
    function query($paPDO, $paSQLStr)
    {
        try
        {
            // Khai b??o exception
            $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // S??? ?????ng Prepare 
            $stmt = $paPDO->prepare($paSQLStr);
            // Th???c thi c??u truy v???n
            $stmt->execute();
            
            // Khai b??o fetch ki???u m???ng k???t h???p
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            
            // L???y danh s??ch k???t qu???
            $paResult = $stmt->fetchAll();   
            return $paResult;                 
        }
        catch(PDOException $e) {
            echo "Th???t b???i, L???i: " . $e->getMessage();
            return null;
        }       
    }
    function getGeoProvinceToAjax($paPDO,$paSRID,$paPoint)
    {
        
        $paPoint = str_replace(',', ' ', $paPoint);
       
        $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_tha_1\" where ST_Within('SRID=".$paSRID.";".$paPoint."'::geometry,geom)";
      
        $result = query($paPDO, $mySQLStr);
        
        if ($result != null)
        {
            // L???p k???t qu???
            foreach ($result as $item){
                return $item['geo'];
            }
        }
        else
            return "null";
    }
    function getGeoDistricToAjax($paPDO,$paSRID,$paPoint)
    {
        
        $paPoint = str_replace(',', ' ', $paPoint);
       
        $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_tha_2\" where ST_Within('SRID=".$paSRID.";".$paPoint."'::geometry,geom)";
      
        $result = query($paPDO, $mySQLStr);
        
        if ($result != null)
        {
            // L???p k???t qu???
            foreach ($result as $item){
                return $item['geo'];
            }
        }
        else
            return "null";
    }
    function seachCityProvince($paPDO,$paSRID,$name)
    {
        
        $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_tha_1\" where name_1 like '$name'";
        $result = query($paPDO, $mySQLStr);

        if ($result != null) {
            // L???p k???t qu???
            foreach ($result as $item) {
                return $item['geo'];
            }
        } else
            return "null";
    }
    function seachCityDistrict($paPDO,$paSRID,$name)
    {
        
        $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gadm36_tha_2\" where name_2 like '$name'";
        $result = query($paPDO, $mySQLStr);

        if ($result != null) {
            // L???p k???t qu???
            foreach ($result as $item) {
                return $item['geo'];
            }
        } else
            return "null";
    }
    function searchInforProvinceToAjax($paPDO, $paSRID, $name)
    {

        $name = str_replace(',', ' ', $name);
        
        $mySQLStr = "SELECT gid ,name_1,ST_Perimeter(gadm36_tha_1.geom), (ST_Area(gadm36_tha_1.geom)) from \"gadm36_tha_1\" where name_1 like '$name'";
       
        $result = query($paPDO, $mySQLStr);

        if ($result != null)
        {
            $resFin = '<table>';
            // L???p k???t qu???
            foreach ($result as $item){
                $resFin = $resFin.'<tr><td>Gid: '.$item['gid'].'</td></tr>';
                $resFin = $resFin.'<tr><td>T??n th??nh ph??? : '.$item['name_1'].'</td></tr>';
                $resFin = $resFin.'<tr><td>Chu vi: '.$item['st_perimeter'].'</td></tr>';
                $resFin = $resFin.'<tr><td>Di???n t??ch: '.$item['st_area'].'</td></tr>';
                break;
            }
            $resFin = $resFin.'</table>';
            return $resFin;
        }
        else
            return "null";
    }

    function searchInforDistrictToAjax($paPDO, $paSRID, $name)
    {

        $name = str_replace(',', ' ', $name);
        
        $mySQLStr = "SELECT gid ,name_2,ST_Perimeter(gadm36_tha_2.geom), (ST_Area(gadm36_tha_2.geom)) from \"gadm36_tha_2\" where name_2 like '$name'";
       
        $result = query($paPDO, $mySQLStr);

        if ($result != null)
        {
            $resFin = '<table>';
            // L???p k???t qu???
            foreach ($result as $item){
                $resFin = $resFin.'<tr><td>Gid: '.$item['gid'].'</td></tr>';
                $resFin = $resFin.'<tr><td>T??n th??nh ph??? : '.$item['name_2'].'</td></tr>';
                $resFin = $resFin.'<tr><td>Chu vi: '.$item['st_perimeter'].'</td></tr>';
                $resFin = $resFin.'<tr><td>Di???n t??ch: '.$item['st_area'].'</td></tr>';
                break;
            }
            $resFin = $resFin.'</table>';
            return $resFin;
        }
        else
            return "null";
    }
    function searchInforRailToAjax($paPDO, $paSRID, $name)
    {

        $name = str_replace(',', ' ', $name);
        
        $mySQLStr = "SELECT gid ,name, ST_Length(geom::geometry) as length  from  \"gis_osm_railways_free_1\" where name like '$name'";
       
        $result = query($paPDO, $mySQLStr);

        if ($result != null)
        {
            $resFin = '<table>';
            // L???p k???t qu???
            foreach ($result as $item){
                if($item['name'] == "") {
                    $item['name'] = "Kh??ng c?? t??n";
                }
                $resFin = $resFin . '<tr><td>ID: ' . $item['gid'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Name: ' . $item['name'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Chi???u d??i: ' . $item['length'] . '</td></tr>';

                break;
            }
            $resFin = $resFin.'</table>';
            return $resFin;
        }
        else
            return "null";
    }


    function getInfoProvinceToAjax($paPDO,$paSRID,$paPoint)
    {
        
        $paPoint = str_replace(',', ' ', $paPoint);
        $mySQLStr = "SELECT name_1,ST_Perimeter(gadm36_tha_1.geom), (ST_Area(gadm36_tha_1.geom)) from \"gadm36_tha_1\" where ST_Within('SRID=".$paSRID.";".$paPoint."'::geometry,geom)";
        $result = query($paPDO, $mySQLStr);
        
        if ($result != null)
        {
            $resFin = '<table>';
            // L???p k???t qu???
            foreach ($result as $item){
                $resFin = $resFin.'<tr><td>T??n Th??nh ph???: '.$item['name_1'].'</td></tr>';
                $resFin = $resFin.'<tr><td>Chu vi: '.$item['st_perimeter'].'</td></tr>';
                $resFin = $resFin.'<tr><td>Di???n t??ch: '.$item['st_area'].'</td></tr>';
                break;
            }
            $resFin = $resFin.'</table>';
            return $resFin;
        }
        else
            return "null";
    }

    function getInfoDistrictToAjax($paPDO,$paSRID,$paPoint)
    {
        
        $paPoint = str_replace(',', ' ', $paPoint);
        $mySQLStr = "SELECT name_2,ST_Perimeter(gadm36_tha_2.geom), (ST_Area(gadm36_tha_2.geom)) from \"gadm36_tha_2\" where ST_Within('SRID=".$paSRID.";".$paPoint."'::geometry,geom)";
        $result = query($paPDO, $mySQLStr);
        
        if ($result != null)
        {
            $resFin = '<table>';
            // L???p k???t qu???
            foreach ($result as $item){
                $resFin = $resFin.'<tr><td>T??n Th??nh ph???: '.$item['name_2'].'</td></tr>';
                $resFin = $resFin.'<tr><td>Chu vi: '.$item['st_perimeter'].'</td></tr>';
                $resFin = $resFin.'<tr><td>Di???n t??ch: '.$item['st_area'].'</td></tr>';
                break;
            }
            $resFin = $resFin.'</table>';
            return $resFin;
        }
        else
            return "null";
    }
    // -------------------- Rail-----------------------------
    function getInfoRailToAjax($paPDO, $paSRID, $paPoint)
    {
        $paPoint = str_replace(',', ' ', $paPoint);
        $strDistance = "ST_Distance('" . $paPoint . "',ST_AsText(geom))";
        $strMinDistance = "SELECT min(ST_Distance('" . $paPoint . "',ST_AsText(geom))) from \"gis_osm_railways_free_1\"  ";
        $mySQLStr = "SELECT gid ,name, ST_Length(geom::geometry) as length  from  \"gis_osm_railways_free_1\" where " . $strDistance . " = (" . $strMinDistance . ") and " . $strDistance . " < 0.5";

        $result = query($paPDO, $mySQLStr);

        if ($result != null) {
            $resFin = '<table>';
            // L???p k???t qu???
            foreach ($result as $item) {
                if($item['name'] == "") {
                    $item['name'] = "Kh??ng c?? t??n";
                }
                $resFin = $resFin . '<tr><td>ID: ' . $item['gid'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Name: ' . $item['name'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Chi???u d??i: ' . $item['length'] . '</td></tr>';

                break;
            }
            $resFin = $resFin . '</table>';
            return $resFin;
        } else
            return "B???n b???m qu?? xa!!";
    }
    function getRailToAjax($paPDO, $paSRID, $paPoint)
    {

    $paPoint = str_replace(',', ' ', $paPoint);

    $strDistance = "ST_Distance('" . $paPoint . "',ST_AsText(geom))";
    $strMinDistance = "SELECT min(ST_Distance('" . $paPoint . "',ST_AsText(geom))) from \"gis_osm_railways_free_1\" ";
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gis_osm_railways_free_1\" where " . $strDistance . " = (" . $strMinDistance . ") and " . $strDistance . " < 0.5";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        // L???p k???t qu???
        foreach ($result as $item) {
            return $item['geo'];
        }
    } else
        return "null";
    }
    function getSearchRailToAjax($paPDO, $paSRID, $name)
    {

        $name = str_replace(',', ' ', $name);

        $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gis_osm_railways_free_1\" where name like '$name'";
        $result = query($paPDO, $mySQLStr);

        if ($result != null) {
            // L???p k???t qu???
            foreach ($result as $item) {
                return $item['geo'];
            }
        } else
            return "null";
    }

    // ----------------------Waterway----------------------
    
    function getInfoWaterWayToAjax($paPDO, $paSRID, $paPoint)
    {
    $paPoint = str_replace(',', ' ', $paPoint);
    $strDistance = "ST_Distance('" . $paPoint . "',ST_AsText(geom))";
    $strMinDistance = "SELECT min(ST_Distance('" . $paPoint . "',ST_AsText(geom))) from \"gis_osm_waterways_free_1\" ";
    $mySQLStr = "SELECT gid,fclass,name , st_length(geom::geometry) as length  from \"gis_osm_waterways_free_1\" where " . $strDistance . " = (" . $strMinDistance . ") and " . $strDistance . " < 0.5";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        $resFin = '<table>';
        // L???p k???t qu???
        foreach ($result as $item) {
            if($item['name'] == "") {
                $item['name'] = "Kh??ng c?? t??n";
            }
            $resFin = $resFin . '<tr><td>ID: ' . $item['gid'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Lo???i D??ng: ' . $item['fclass'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Name: ' . $item['name'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Chi???u d??i: ' . $item['length'] . '</td></tr>';

            break;
        }
        $resFin = $resFin . '</table>';
        return $resFin;
    } else
        return "B???n b???m qu?? xa!!!";
    }
    function getWaterWayToAjax($paPDO, $paSRID, $paPoint)
    {

    $paPoint = str_replace(',', ' ', $paPoint);

    $strDistance = "ST_Distance('" . $paPoint . "',ST_AsText(geom))";
    $strMinDistance = "SELECT min(ST_Distance('" . $paPoint . "',ST_AsText(geom))) from \"gis_osm_waterways_free_1\" ";
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gis_osm_waterways_free_1\" where " . $strDistance . " = (" . $strMinDistance . ") and " . $strDistance . " < 0.5";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        // L???p k???t qu???
        foreach ($result as $item) {
            return $item['geo'];
        }
    } else
        return "null";
    }

    // ---------------------------Water --------------------------------
        
    function getInfoWaterToAjax($paPDO, $paSRID, $paPoint)
    {
        $paPoint = str_replace(',', ' ', $paPoint);
        $strDistance = "ST_Distance('" . $paPoint . "',ST_AsText(geom))";
        $strMinDistance = "SELECT min(ST_Distance('" . $paPoint . "',ST_AsText(geom))) from \"gis_osm_water_a_free_1\" ";
        $mySQLStr = "SELECT name,fclass, st_area(geom::geometry) as area , ST_Perimeter(geom::geometry) as perimeter from  \"gis_osm_water_a_free_1\" where " . $strDistance . " = (" . $strMinDistance . ") and " . $strDistance . " < 0.5";
        $result = query($paPDO, $mySQLStr);

        if ($result != null) {
            $resFin = '<table>';
            // L???p k???t qu???
            foreach ($result as $item) {
                if ($item['name'] == ""){
                    $item['name'] = "Kh??ng c?? t??n";
                }
                $resFin = $resFin . '<tr><td>T??n: ' . $item['name'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Lo???i: ' . $item['fclass'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Di???n t??ch: ' . $item['area'] . '</td></tr>';
                    $resFin = $resFin . '<tr><td>Chu vi: ' . $item['perimeter'] . '</td></tr>';

                break;
            }
            $resFin = $resFin . '</table>';
            return $resFin;
        } else
            return "null";
    }
    
    function getWaterToAjax($paPDO, $paSRID, $paPoint)
    {

    $paPoint = str_replace(',', ' ', $paPoint);

    $strDistance = "ST_Distance('" . $paPoint . "',ST_AsText(geom))";
    $strMinDistance = "SELECT min(ST_Distance('" . $paPoint . "',ST_AsText(geom))) from \"gis_osm_water_a_free_1\"";
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gis_osm_water_a_free_1\" where " . $strDistance . " = (" . $strMinDistance . ") and " . $strDistance . " < 0.5";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        // L???p k???t qu???
        foreach ($result as $item) {
            return $item['geo'];
        }
    } else
        return "null";
    }

// ---------------------------LandUse --------------------------------
        
    function getInfoLandUseToAjax($paPDO, $paSRID, $paPoint)
    {
        $paPoint = str_replace(',', ' ', $paPoint);
        $strDistance = "ST_Distance('" . $paPoint . "',ST_AsText(geom))";
        $strMinDistance = "SELECT min(ST_Distance('" . $paPoint . "',ST_AsText(geom))) from \"gis_osm_landuse_a_free_1\" ";
        $mySQLStr = "SELECT name,fclass, st_area(geom::geometry) as area , ST_Perimeter(geom::geometry) as perimeter from  \"gis_osm_landuse_a_free_1\" where " . $strDistance . " = (" . $strMinDistance . ") and " . $strDistance . " < 0.5";
        $result = query($paPDO, $mySQLStr);

        if ($result != null) {
            $resFin = '<table>';
            // L???p k???t qu???
            foreach ($result as $item) {
                if ($item['name'] == ""){
                    $item['name'] = "Kh??ng c?? t??n";
                }
                $resFin = $resFin . '<tr><td>T??n: ' . $item['name'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Lo???i: ' . $item['fclass'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Di???n t??ch: ' . $item['area'] . '</td></tr>';
                    $resFin = $resFin . '<tr><td>Chu vi: ' . $item['perimeter'] . '</td></tr>';

                break;
            }
            $resFin = $resFin . '</table>';
            return $resFin;
        } else
            return "null";
    }

    function getLandUseToAjax($paPDO, $paSRID, $paPoint)
    {

    $paPoint = str_replace(',', ' ', $paPoint);

    $strDistance = "ST_Distance('" . $paPoint . "',ST_AsText(geom))";
    $strMinDistance = "SELECT min(ST_Distance('" . $paPoint . "',ST_AsText(geom))) from \"gis_osm_landuse_a_free_1\"";
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"gis_osm_landuse_a_free_1\" where " . $strDistance . " = (" . $strMinDistance . ") and " . $strDistance . " < 0.5";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        // L???p k???t qu???
        foreach ($result as $item) {
            return $item['geo'];
        }
    } else
        return "null";
    }


    //--------------------??i???m--------------------------------
    function getInfoPlaceToAjax($paPDO, $paSRID, $paPoint)
    {
        $paPoint = str_replace(',', ' ', $paPoint);
        $strDistance = "ST_Distance('" . $paPoint . "',ST_AsText(geom))";
        $strMinDistance = "SELECT min(ST_Distance('" . $paPoint . "',ST_AsText(geom))) from \"place_point\" ";
        $mySQLStr = "SELECT fclass,name, xcoord , ycoord from  \"place_point\" where " . $strDistance . " = (" . $strMinDistance . ") and " . $strDistance . " < 0.5";
        $result = query($paPDO, $mySQLStr);

        if ($result != null) {
            $resFin = '<table>';
            // L???p k???t qu???
            foreach ($result as $item) {
                if ($item['name'] == ""){
                    $item['name'] = "Kh??ng c?? t??n";
                }
                $resFin = $resFin . '<tr><td>T??n: ' . $item['name'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Lo???i: ' . $item['fclass'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Kinh ?????: ' . $item['xcoord'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>V?? ?????: ' . $item['ycoord'] . '</td></tr>';

                break;
            }
            $resFin = $resFin . '</table>';
            return $resFin;
        } else
            return "nul????sl";
    }
    function getSearchInfoPlaceToAjax($paPDO, $paSRID, $name)
    {
        $name = str_replace(',', ' ', $name);
      
        $mySQLStr = "SELECT fclass,name, xcoord , ycoord from  \"place_point\" where name like '$name'" ;
        $result = query($paPDO, $mySQLStr);

        if ($result != null) {
            $resFin = '<table>';
            // L???p k???t qu???
            foreach ($result as $item) {
                if ($item['name'] == ""){
                    $item['name'] = "Kh??ng c?? t??n";
                }
                $resFin = $resFin . '<tr><td>T??n: ' . $item['name'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Lo???i: ' . $item['fclass'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Kinh ?????: ' . $item['xcoord'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>V?? ?????: ' . $item['ycoord'] . '</td></tr>';

                break;
            }
            $resFin = $resFin . '</table>';
            return $resFin;
        } else
            return "nul????sl";
    }

    function getPlaceToAjax($paPDO, $paSRID, $paPoint)
    {

        $paPoint = str_replace(',', ' ', $paPoint);

        $strDistance = "ST_Distance('" . $paPoint . "',ST_AsText(geom))";
        $strMinDistance = "SELECT min(ST_Distance('" . $paPoint . "',ST_AsText(geom))) from \"place_point\"";
        $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"place_point\" where " . $strDistance . " = (" . $strMinDistance . ") and " . $strDistance . " < 0.5";
        $result = query($paPDO, $mySQLStr);

        if ($result != null) {
            // L???p k???t qu???
            foreach ($result as $item) {
                return $item['geo'];
            }
        } else
            return "null";
    }
    function getSearchGeoPlaceToAjax($paPDO, $paSRID, $name)
    {

        $name = str_replace(',', ' ', $name);

        $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"place_point\" where name like '$name'";
        $result = query($paPDO, $mySQLStr);

        if ($result != null) {
            // L???p k???t qu???
            foreach ($result as $item) {
                return $item['geo'];
            }
        } else
            return "null";
    }

    function getInfoNaturalToAjax($paPDO, $paSRID, $paPoint)
    {
        $paPoint = str_replace(',', ' ', $paPoint);
        $strDistance = "ST_Distance('" . $paPoint . "',ST_AsText(geom))";
        $strMinDistance = "SELECT min(ST_Distance('" . $paPoint . "',ST_AsText(geom))) from \"natural_point\" ";
        $mySQLStr = "SELECT fclass,name, xcoord , ycoord from  \"natural_point\" where " . $strDistance . " = (" . $strMinDistance . ") and " . $strDistance . " < 0.5";
        $result = query($paPDO, $mySQLStr);

        if ($result != null) {
            $resFin = '<table>';
            // L???p k???t qu???
            foreach ($result as $item) {
                if ($item['name'] == ""){
                    $item['name'] = "Kh??ng c?? t??n";
                }
                $resFin = $resFin . '<tr><td>T??n: ' . $item['name'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Lo???i: ' . $item['fclass'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>Kinh ?????: ' . $item['xcoord'] . '</td></tr>';
                $resFin = $resFin . '<tr><td>V?? ?????: ' . $item['ycoord'] . '</td></tr>';

                break;
            }
            $resFin = $resFin . '</table>';
            return $resFin;
        } else
            return "nul????sl";
    }

    function getNaturalToAjax($paPDO, $paSRID, $paPoint)
    {

    $paPoint = str_replace(',', ' ', $paPoint);

    $strDistance = "ST_Distance('" . $paPoint . "',ST_AsText(geom))";
    $strMinDistance = "SELECT min(ST_Distance('" . $paPoint . "',ST_AsText(geom))) from \"natural_point\"";
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"natural_point\" where " . $strDistance . " = (" . $strMinDistance . ") and " . $strDistance . " < 0.5";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        // L???p k???t qu???
        foreach ($result as $item) {
            return $item['geo'];
        }
    } else
        return "null";
    }

?>