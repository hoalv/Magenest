<?php

namespace Magenest\OrderAttribute\Controller\Adminhtml\Attribute;

use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context)
    {
        parent::__construct($context);
    }

    /**
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {

                $modelEav = $this->_objectManager->create('Magenest\OrderAttribute\Model\EavAttribute');
                $modelOrderEav = $this->_objectManager->create('Magenest\OrderAttribute\Model\OrderEavAttribute');
                $modelAttributeValue = $this->_objectManager->create('Magenest\OrderAttribute\Model\OrderAttributeValue')->getCollection()->addFieldToFilter('attribute_id', $id);

                $modelEav->load($id);
                $modelOrderEav->load($id, 'attribute_id');

                $modelEav->delete();
                $modelOrderEav->delete();

                foreach ($modelAttributeValue as $item){
                    $item->delete();
                }
                if($modelOrderEav->getFrontendInput()=='select' || $modelOrderEav->getFrontendInput()=='multiselect'){
                    $modelOption = $this->_objectManager->create('Magenest\OrderAttribute\Model\OrderOption');
                    $modelOption->load($id, 'attribute_id');
                    $modelOption->delete();
                }
                $this->_redirect('*/*/');
                $this->messageManager->addSuccess(__('Delete attribute successfull.'));
                return;
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete this attribute right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a rule to delete.'));
        $this->_redirect('*/*/');
    }
}