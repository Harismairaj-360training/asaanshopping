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


    public function __construct(Context $context,
                                ObjectManagerInterface $objectManager,
                                StoreManagerInterface $storeManager,
                                ClientFactory $clientFactory,
                                \Magento\Framework\HTTP\Client\Curl $curl,
                                array $data = []
    ) {
        $this->zendClientFactory = $clientFactory;
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        $this->_curl = $curl;
        parent::__construct($context);
    }




}