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

    /** @var Cookie */
    protected $cookie;


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
        $this->cookie = $this->objectManager->get('AsaanShopping\SearchByLocation\Helper\Cookie');
        parent::__construct($context);
    }

    public function searchAjaxRequest($data= '')
    {
        $categoryId         =       $data['categoryId'];
        $address            =       $data['address'];
        $lat                =       $data['lat'];
        $lng                =       $data['lng'];

        //  Set Data into session
        $this->cookie->set($categoryId."|".$address."|".$lat."|".$lng);
        $categoryUrl        =       $this->getCatUrlById($categoryId);

        return $categoryUrl;
    }


    public function getCatUrlById($categoryId='')
    {
        $categoryObj        =       $this->categoryRepository->get($categoryId);
        $catUrl             =       $this->categoryHelper->getCategoryUrl($categoryObj);

        return $catUrl;
    }

    public function getAddress()
    {
        $val = $this->cookie->get("search_by_location");
        return explode("|", $val);
    }
}
