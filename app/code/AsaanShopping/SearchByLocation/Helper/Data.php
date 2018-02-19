<?php

namespace AsaanShopping\SearchByLocation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Area;
use Zend\Http\ClientFactory;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Customer\Model\Session;


class Data extends AbstractHelper
{

    protected $storeManager;
    protected $objectManager;

    /**
     * @var ClientFactory
     * Factory for creating new zend http client.
     */
    protected $zendClientFactory;

    /**
     * @var _curl \Magento\Framework\HTTP\Client\Curl
     */
    protected $_curl;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $categoryHelper;

    /**
     * @var \Magento\Catalog\Model\CategoryRepository
     */
    protected $categoryRepository;

    /** @var Session */
    protected $session;


    public function __construct(Context $context,
                                ObjectManagerInterface $objectManager,
                                StoreManagerInterface $storeManager,
                                ClientFactory $clientFactory,
                                \Magento\Framework\HTTP\Client\Curl $curl,
                                \Magento\Catalog\Helper\Category $categoryHelper,
                                \Magento\Catalog\Model\CategoryRepository $categoryRepository,
                                Session $session,
                                array $data = []
    ) {
        $this->zendClientFactory = $clientFactory;
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        $this->_curl = $curl;
        $this->categoryHelper = $categoryHelper;
        $this->categoryRepository = $categoryRepository;
        $this->session = $session;
        parent::__construct($context);
    }


    public function searchAjaxRequest($data= '')
    {
        $categoryId         =       '4';//$data['category_id'];
        $address            =       'address';//$data['address'];
        $street             =       'street';//$address['street'];
        $city               =       'city';//$address['city'];
        $country            =       'country';//$address['country'];
        $lat                =       'lat';//$address['lat'];
        $lng                =       'lng';//$address['lng'];

        //Set Data into session
        $this->session->setData("categoryId",$categoryId);
        $this->session->setData("street",$street);
        $this->session->setData("city",$city);
        $this->session->setData("country",$country);
        $this->session->setData("lat",$lat);
        $this->session->setData("lng",$lng);

        $categoryUrl        =       $this->getCatUrlById($categoryId);

        return $categoryUrl;

    }


    public function getCatUrlById($categoryId='')
    {
        $categoryObj        =       $this->categoryRepository->get($categoryId);
        $catUrl             =       $this->categoryHelper->getCategoryUrl($categoryObj);

        return $catUrl;
    }

    function getAddressSession()
    {
        $categoryId     = $this->session->getData("categoryId");
        $street         = $this->session->getData("street");
        $city           = $this->session->getData("city");
        $country        = $this->session->getData("country");
        $lat            = $this->session->getData("lat");
        $lng            = $this->session->getData("lng");

        $AddressArray      =   array(
            'categoryId'        =>      $categoryId,
            'street'            =>      $street,
            'city'              =>      $city,
            'country'           =>      $country,
            'lat'               =>      $lat,
            'lng'               =>      $lng,
        );

        return $AddressArray;
    }




}