<?php

namespace Magenest\OrderAttribute\Observer\Adminhtml\OrderView;

use Psr\Log\LoggerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class LoadCustomer
 * @package Magenest\AdminLogger\Observer\Adminhtml\Customers
 */
class InsertLayout implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;


    /**
     * LoadCustomer constructor.
     * @param LoggerInterface $logger
     * @param \Magenest\AdminLogger\Model\CustomerCompareFactory $compareFactory
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magenest\AdminLogger\Model\Connector $connector
     */
    public function __construct(
        LoggerInterface $logger,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    )
    {
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $_block = $observer->getBlock();
        $_type = $_block->getType();

        if ($_type == 'adminhtml/template') {
            if ($_block->getChild('order_giftmessage')) {
                $_child = clone $_block;
                $_child->setType('adminhtml/sales_order_view_info');
                $_block->setChild('giftmessage', $_child);
                $_block->setTemplate('Magenest_OrderAttribute::attributes_form.phtml');

            }
        }
    }
}