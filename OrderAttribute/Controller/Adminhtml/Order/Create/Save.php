<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\OrderAttribute\Controller\Adminhtml\Order\Create;

use Magento\Framework\Exception\PaymentException;

class Save extends \Magento\Sales\Controller\Adminhtml\Order\Create\Save
{
    /**
     * Saving quote and create order
     *
     * @return \Magento\Backend\Model\View\Result\Forward|\Magento\Backend\Model\View\Result\Redirect
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        $this->_objectManager->create('Psr\Log\LoggerInterface')->debug('abcd');
        parent::execute();
    }
}
