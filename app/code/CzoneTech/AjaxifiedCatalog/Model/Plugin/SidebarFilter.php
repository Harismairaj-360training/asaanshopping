<?php namespace CzoneTech\AjaxifiedCatalog\Model\Plugin;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

class SidebarFilter
{
    /**
     * aroundAddFieldToFilter method
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Closure                                                $proceed
     * @param                                                         $fields
     * @param null                                                    $condition
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function aroundAddFieldToFilter(ProductCollection $collection, \Closure $proceed, $fields, $condition = null)
    {
        // Here we can modify the collection
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');


        $getSession     =   $customerSession->getData();
        $Urlparam       =   $_GET;

        if(!empty($Urlparam['lat']) && !empty($Urlparam['lng']))
        {
            $lat            =   $Urlparam['lat'];
            $lng            =   $Urlparam['lng'];
            $areaPinPoint   =   array($lat,$lng);

            // set lat,lng in session if url is empty
            $customerSession->setData("lat",$lat);
            $customerSession->setData("lng",$lng);
        }else{
                $lat            =   $customerSession->getData("lat");
                $lng            =   $customerSession->getData("lng");
                $areaPinPoint   =   array($lat,$lng);
        }

        $productsLatitude           =   $collection->getAllAttributeValues("latitude");
        $productsLongitude          =   $collection->getAllAttributeValues("longitude");
        $productCordinates          =   array_chunk($this->array_interlace($productsLatitude,$productsLongitude), 2);

        foreach($productCordinates as $codinateformat) {

            $itemsPinpoints[]       =   array($codinateformat[0][0],$codinateformat[1][0]);

            }

        $collectionCordinates           =       $this->areaStoreLocations($areaPinPoint, $itemsPinpoints);
        $findProducts                   =       $this->sortArrayByArray($itemsPinpoints,$collectionCordinates);

        //create lat and long array for filter
        $latvalue = [];
        $lngvalue = [];
        foreach ($findProducts as $latLong){
            if(!empty($latLong[0]) && !empty($latLong[1]))
            {
                $latvalue[]       =   $latLong[0];
                $lngvalue[]       =   $latLong[1];
            }
        }

        /*echo "<pre>";
        print_r($collectionCordinates);
        print_r($itemsPinpoints);
        print_r($latvalue);
        print_r($lngvalue);
        echo "</pre>";
        exit;


        $areaPinPoint = array(24.914380, 67.031566);//Nazimabad

        $itemsPinpoints = array(
            '0' => array('24.933621','67.023393'),//Board office
            '1' => array('24.920733','67.088162'),// Gulshan
            '2' => array('24.914501','67.024212')//nazimabad Gole Market
        );

        $collection     =   $this->areaStoreLocations($areaPinPoint, $itemsPinpoints);

        print_r($collection);
        print_r($itemsPinpoints);


        print_r($this->sortArrayByArray($itemsPinpoints,$collection));

 */
        $collection->addAttributeToFilter('latitude', ['in' => $latvalue],'longitude', ['in' => $lngvalue]);

        //$collection->addAttributeToFilter('latitude', array('like' => '%24%'));
        //$collection->addAttributeToFilter('longitude', array('like' => '%67%'));

        //$collection->addAttributeToFilter('longitude', ['in' => ['67.088162']]);

        //echo $collection->getSelect()->__toString();

        return $fields ? $proceed($fields, $condition) : $collection;
    }

    public function areaStoreLocations($areaPinPoint = '', $itemsPinpoints ='')
    {
        foreach ($itemsPinpoints as $i=>$driver){

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
    public function array_interlace() {
        $args = func_get_args();
        $total = count($args);

        if($total < 2) {
            return FALSE;
        }

        $i = 0;
        $j = 0;
        $arr = array();

        foreach($args as $arg) {
            foreach($arg as $v) {
                $arr[$j] = $v;
                $j += $total;
            }

            $i++;
            $j = $i;
        }

        ksort($arr);
        return array_values($arr);
    }

    public function sortArrayByArray($array,$orderArray) {
        $ordered = array();
        foreach($orderArray as $key => $value) {
            if(array_key_exists($key,$array)) {
                $ordered[$key] = $array[$key];
                unset($array[$key]);
            }
        }
        return $ordered + $array;
    }

}
