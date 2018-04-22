<?php

namespace AsaanShopping\SearchByLocation\Controller\Nearest;

class Products extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $jsonHelper;
    protected $helper;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \AsaanShopping\SearchByLocation\Helper\Data $helper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $parms = $this->getRequest()->getPost();
        $result = $this->helper->searchAjaxRequest($parms);
        try {
            return $this->jsonResponse(array(
              "status"=>true,
              "message"=>"",
              "response"=>$result
            ));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->jsonResponse(array(
              "status"=>true,
              "message"=>$e->getMessage(),
              "response"=>""
            ));
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse(array(
              "status"=>true,
              "message"=>$e->getMessage(),
              "response"=>""
            ));
        }
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response)
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}
