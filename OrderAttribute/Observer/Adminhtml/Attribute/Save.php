<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\OrderAttribute\Observer\Adminhtml\Attribute;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magenest\OrderAttribute\Model\OrderOptionFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Filesystem;


/**
 * Class Register
 *
 * @package Magenest\QuickBooksDesktop\Observer\Customer
 */
class Save implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Filesystem
     */
    protected $_filesystem;

    /**
     * @var OrderOptionFactory
     */
    protected $optionFactory;



    /**
     * Save constructor.
     * @param RequestInterface $request
     * @param LoggerInterface $loggerInterface
     * @param OrderOptionFactory $customerOptionFactory
     */
    public function __construct(
        RequestInterface $request,
        LoggerInterface $loggerInterface,
        OrderOptionFactory $orderOptionFactory

    ) {
        $this->optionFactory = $orderOptionFactory;
        $this->logger = $loggerInterface;
        $this->_request = $request;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        $option = $event->getOption();
        $model = $event->getModel();
        $modelOption = $this->optionFactory->create();
        $attributeId = $model->getAttributeId();
        if ($attributeId && !empty($attributeId)) {
            /** @var \Magenest\OrderAttribute\Model\OrderOption $modelOption */
            $modelOption->load($attributeId, 'attribute_id');
            $array = [
                'attribute_id' => $attributeId,
                'attribute_code' => $model->getAttributeCode(),
//                'validate_rules'=> $option['validate_rules'],
                'type' => $option['type'],
//                'is_checked' => $option['is_checked'],
            ];
            $array['value'] = ' ';
            if ($option['type'] == 'select' || $option['type'] == 'multiselect') {
                $array['value'] = $option['value'];
            }
//            $array['county_code'] = ' ';
//            if ($option['is_checked'] == 1) {
//                $array['country_code'] = $option['country_code'];
//            }
            $modelOption->addData($array);
            $modelOption->save();
        }

        return $this;
    }
}
