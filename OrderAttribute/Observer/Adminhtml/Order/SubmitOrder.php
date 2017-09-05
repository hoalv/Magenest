<?php

namespace Magenest\OrderAttribute\Observer\Adminhtml\Order;

use Magenest\OrderAttribute\Model\ResourceModel\OrderAttributeValue;
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
class SubmitOrder implements ObserverInterface
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
     * @var OrderAttributeValueFactory
     */
    protected $attributeValueFactory;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlInterface;

    protected $eavConfig;

    protected $orderAttrEav;
    /**
     * Save constructor.
     * @param RequestInterface $request
     * @param LoggerInterface $loggerInterface
     * @param OrderOptionFactory $customerOptionFactory
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Psr\Log\LoggerInterface $loggerInterface,
        \Magenest\OrderAttribute\Model\OrderOptionFactory $orderOptionFactory,
        \Magenest\OrderAttribute\Model\OrderAttributeValueFactory $orderAttributeValueFactory,
        \Magenest\OrderAttribute\Model\OrderEavAttributeFactory $orderAttrEav,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\UrlInterface $urlInterface

    ) {
        $this->optionFactory = $orderOptionFactory;
        $this->attributeValueFactory = $orderAttributeValueFactory;
        $this->eavConfig = $eavConfig;
        $this->orderAttrEav = $orderAttrEav;
        $this->logger = $loggerInterface;
        $this->_request = $request;
        $this->_objectManager = $objectManager;
        $this->_urlInterface = $urlInterface;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent();
        $order = $event->getOrder();
        $data = $this->_request->getParams();

        $customAttr = $this->orderAttrEav->create()->getCollection();
        $orderId = $order->getEntityId();
        $arrAttr = [];
        foreach ($customAttr as $item){
            $arrAttr[] = $this->eavConfig->getAttribute('order', $item->getAttributeId().'');
        }
        foreach ($arrAttr as $item){
            $modelValue = $this->_objectManager->create('Magenest\OrderAttribute\Model\OrderAttributeValue');
            $valueRecord = $this->attributeValueFactory->create()->getCollection()->addFieldToFilter('order_id', $orderId)
                ->addFieldToFilter('attribute_id', $item->getAttributeId());
            if($valueRecord->count() == 0){
                $array = [
                    'order_id' =>   $orderId,
                    'attribute_id' => $item->getAttributeId(),
                    'customer_id'=>$order->getCustomerId(),
                    'attribute_value'=> $this->setDataToSaveValue($data,$item),
                ];
                $modelValue->addData($array);
                $modelValue->save();
            }

        }

        return $this;
    }

    public function setDataToSaveValue($data,$item)
    {
        $arrayValue = $data['addition'];
        $id = $item->getAttributeId();
        $typeInput= $item->getFrontendInput();
        if($typeInput=='select' || $typeInput=='multiselect'){
            $value = serialize($arrayValue['option'][$typeInput.'_'.$id]);
        }else{
            $value = $arrayValue['custom'][$typeInput.'_'.$id];
        }
        return $value;
    }
}
