<?php

namespace Magenest\OrderAttribute\Plugin;

class SubmitOrderPlugin
{
    public function beforeExecute(\Magento\Sales\Controller\Adminhtml\Order\Create\Save $subject)
    {
        // logging to test override

//        $data = $this->getRequest()->getPostValue();
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        $logger->debug('test before save 123');
        $data = $subject->getRequest()->getPostValue();
        // call the core observed function
//        $returnValue = $proceed();

//        $postValue =  $returnValue->getRequest();
//        $data = $postValue['additional'];
//        $logger->debug($data['custom']['date_162']);
        return null;
    }
}
?>