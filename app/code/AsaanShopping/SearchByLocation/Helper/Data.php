<?php

namespace AsaanShopping\SearchByLocation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{

    protected $storeManager;
    protected $objectManager;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $categoryHelper;

    /**
     * @var \Magento\Catalog\Model\CategoryRepository
     */
    protected $categoryRepository;

    /** @var Cookie */
    protected $cookie;


    public function __construct(Context $context,
                                ObjectManagerInterface $objectManager,
                                StoreManagerInterface $storeManager,
                                \Magento\Catalog\Helper\Category $categoryHelper,
                                \Magento\Catalog\Model\CategoryRepository $categoryRepository,
                                array $data = []
    ) {
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        $this->categoryHelper = $categoryHelper;
        $this->categoryRepository = $categoryRepository;
        $this->cookie = $this->objectManager->get('AsaanShopping\SearchByLocation\Helper\Cookie');
        parent::__construct($context);
    }

    public function searchAjaxRequest($data= '')
    {
        $categoryId = $data['categoryId'];
        $address = $data['address'];
        $lat = $data['lat'];
        $lng = $data['lng'];

        //  Set Data into cookie
        $this->cookie->set($categoryId."|".$address."|".$lat."|".$lng);
        $categoryUrl = $this->getCatUrlById($categoryId);

        return $categoryUrl;
    }


    public function getCatUrlById($categoryId='')
    {
        $categoryObj = $this->categoryRepository->get($categoryId);
        $catUrl = $this->categoryHelper->getCategoryUrl($categoryObj);

        return $catUrl;
    }

    public function getAddress()
    {
        $val = $this->cookie->get();
        return explode("|", $val);
    }
}
