<?php

namespace AsaanShopping\SearchByLocation\Model\Plugin;

class Layer {

    public function afterGetProductCollection($subject, $collection)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cookie = $objectManager->get('AsaanShopping\SearchByLocation\Helper\Cookie');

        $val = $cookie->get();
        $cookieData = explode("|", $val);
        $urlParam = $_GET;
        if(!empty($urlParam['lat']) && !empty($urlParam['lng']))
        {
            $lat = $urlParam['lat'];
            $lng = $urlParam['lng'];
        }
        else
        {
            $lat = (!empty($cookieData[2])?$cookieData[2]:"");
            $lng = (!empty($cookieData[3])?$cookieData[3]:"");
        }
        $areaPinPoint = array($lat,$lng);

        /*echo "<pre>";
        print_r($areaPinPoint);
        echo "</pre>";
        exit;*/
        if(!empty($areaPinPoint[0]) && !empty($areaPinPoint[1]))
        {
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

            $itemsPinpoints = array();
            foreach($productCordinates as $id=>$codinateformat)
            {
                $itemsPinpoints[$id] = array($codinateformat[0][0],$codinateformat[1][0]);
            }

            $collectionCordinates = $this->areaStoreLocations($areaPinPoint, $itemsPinpoints);
            $ids = array();
            foreach($collectionCordinates as $id=>$latLong)
            {
              $ids[] = $id;
            }

            $collection->addAttributeToFilter('entity_id', ['in' => $ids]);

            if(count($ids) > 0)
            {
              $collection->getSelect()->order(new \Zend_Db_Expr('FIELD(e.entity_id, ' . implode(',', $ids).')'));
            }
        }

        return $collection;
    }

    public function areaStoreLocations($areaPinPoint = '', $itemsPinpoints ='')
    {
        $nearestPoint = array();
        foreach ($itemsPinpoints as $i=>$point)
        {
            list($lat1, $lon1) = $point;
            list($lat2, $lon2) = $areaPinPoint;
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            if(round($miles*1.609344) <= 2)
            {
              $nearestPoint[$i] = $miles;
            }
        }
        if(count($nearestPoint) > 0)
        {
          asort($nearestPoint);
          $nearestPoint[key($nearestPoint)];
        }
        return $nearestPoint;
    }
}
