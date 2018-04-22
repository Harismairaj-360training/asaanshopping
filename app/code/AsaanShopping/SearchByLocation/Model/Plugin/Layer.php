<?php

namespace AsaanShopping\SearchByLocation\Model\Plugin;

class Layer {

    public function afterGetProductCollection($subject, $collection)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        $getSession = $customerSession->getData();
        $Urlparam = $_GET;
        if(!empty($Urlparam['lat']) && !empty($Urlparam['lng']))
        {
            $lat = $Urlparam['lat'];
            $lng = $Urlparam['lng'];
            $areaPinPoint = array($lat,$lng);
            $customerSession->setData("lat",$lat);
            $customerSession->setData("lng",$lng);
        }
        else
        {
            $lat = $customerSession->getData("lat");
            $lng = $customerSession->getData("lng");
            $areaPinPoint = array($lat,$lng);
        }

        /*echo "<pre>";
        print_r($areaPinPoint);
        echo "</pre>";
        exit;*/

        $productsLatitude = $collection->getAllAttributeValues("latitude");
        $productsLongitude = $collection->getAllAttributeValues("longitude");

        $productCordinates = [];
        foreach($productsLatitude as $id=>$pl)
        {
          $productCordinates[$id] = array($pl,$productsLongitude[$id]);
        }

        /*echo "<pre>";
        print_r($productsLatitude);
        print_r($productsLongitude);
        print_r($productCordinates);
        echo "</pre>";
        exit;*/

        $itemsPinpoints = [];
        foreach($productCordinates as $id=>$codinateformat)
        {
            $itemsPinpoints[$id] = array($codinateformat[0][0],$codinateformat[1][0]);
        }

        $collectionCordinates = $this->areaStoreLocations($areaPinPoint, $itemsPinpoints);
        $ids = [];
        foreach($collectionCordinates as $id=>$latLong)
        {
          $ids[] = $id;
        }

        $collection->addAttributeToFilter('entity_id', ['in' => $ids]);
        $collection->getSelect()->order(new \Zend_Db_Expr('FIELD(e.entity_id, ' . implode(',', $ids).')'));

        return $collection;
    }

    public function areaStoreLocations($areaPinPoint = '', $itemsPinpoints ='')
    {
        foreach ($itemsPinpoints as $i=>$driver)
        {
            list($lat1, $lon1) = $driver;
            list($lat2, $lon2) = $areaPinPoint;
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $nearestDrivers[$i] = $miles;
        }
        asort($nearestDrivers);
        $nearestDrivers[key($nearestDrivers)];
        return $nearestDrivers;
    }
}
