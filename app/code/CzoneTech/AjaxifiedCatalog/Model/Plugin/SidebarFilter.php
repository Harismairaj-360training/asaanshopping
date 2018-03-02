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

        //echo "m here";
        //exit;
        $areaPinPoint = array(24.914380, 67.031566);//Nazimabad

        $itemsPinpoints = array(
            '0' => array('24.933621','67.023393'),//Board office
            '1' => array('24.920733','67.088162'),// Gulshan
            '2' => array('24.914501','67.024212')//nazimabad Gole Market
        );

        //$collection     =   $this->areaStoreLocations($areaPinPoint, $itemsPinpoints);

        //$collection->addAttributeToFilter('latitude', ['in' => ['24.920733']],'longitude', ['in' => ['67.023393']]);

        $collection->addAttributeToFilter('latitude', array('like' => '%24%'));
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
}