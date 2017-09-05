<?php
namespace Magenest\OrderAttribute\Controller\Adminhtml\Attribute;

use    Magento\Backend\App\Action\Context;
use    Magento\Framework\View\Result\PageFactory;

class    Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magenest_OrderAttribute::attributes_list');
        $resultPage->addBreadcrumb(__('Order Attribute'), __('Order Attribute'));
        $resultPage->addBreadcrumb(__('Manage Order Attribute'),
            __('Manage	Order Attribute'));
        $resultPage->getConfig()->getTitle()->prepend(__('Order Attribute'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_OrderAttribute::attributes_list');
    }
}