<?php

namespace Magenest\OrderAttribute\Controller\Adminhtml\Attribute;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @var \Magento\Backend\Helper\Js
     */
    protected $jsHelper;

    protected $logger;

    private $eavSetupFactory;
    /**
     * @var OrderOptionFactory
     */
    protected $optionFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Backend\Helper\Js $jsHelper
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Backend\Helper\Js $jsHelper,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magenest\OrderAttribute\Model\OrderOptionFactory $orderOptionFactory
    )
    {
        $this->cacheTypeList = $cacheTypeList;
        parent::__construct($context);
        $this->jsHelper = $jsHelper;
        $this->logger = $logger;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->optionFactory = $orderOptionFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_OrderAttribute::attributes_list');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $data['entity_type_id'] = 5;
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            //$model = $this->eavSetupFactory->create();
            $modelEav = $this->_objectManager->create('Magenest\OrderAttribute\Model\EavAttribute');
            $modelOrderEav = $this->_objectManager->create('Magenest\OrderAttribute\Model\OrderEavAttribute');
            $modelOption = $this->_objectManager->create('Magenest\OrderAttribute\Model\OrderOption');
            $id = $this->getRequest()->getParam('id');

            if ($id) {
                $modelEav->load($id);
                $modelOrderEav->load($id, 'attribute_id');
                $modelOption->load($id, 'attribute_id');
            }
            $data = $this->setSourceModel($data);
            $data = $this->getDefaulValue($data);
            if (!empty($data['store_ids'])) {
                $data['store_ids'] =
                    implode(',', $data['store_ids']);
            } else {
                $data['store_ids'] = '';
            }
            if (!empty($data['customer_groups'])) {
                $data['customer_groups'] =
                    implode(',', $data['customer_groups']);
            } else {
                $data['customer_groups'] = '';
            }

            $modelEav->addData($data);
//            $model->addAttribute(
//                \Magento\Sales\Model\Order::ENTITY,
//                $data['attribute_code'],
//                $data
//            );
            //$modelOrderEav->setParentId($modelEav->getAttributeId());



            try {

                $modelEav->save();

                $data['attribute_id'] = $modelEav->getAttributeId();
                unset($data['id']);
                $modelOrderEav->addData($data);
                $modelOrderEav->save();

                if ($data['frontend_input'] == 'multiselect' || $data['frontend_input'] == 'select') {
                    $option = $this->saveCustomOption($data);
                    $arr = [
                        'attribute_id' => $modelEav->getAttributeId(),
                        'entity_id' => 5
                    ];
                    $modelOption->addData($arr);
                    $modelOption->save();
                    $this->_eventManager->dispatch('order_custom_attribute_save_after', ['option' => $option, 'model' => $modelEav]);
                }

                $this->cacheTypeList->invalidate('full_page');
                $this->messageManager->addSuccess(__('You saved this attribute.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $modelEav->getAttributeId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the attribute.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    protected function setSourceModel($data)
    {
        switch ($data['frontend_input']) {
            case 'boolean':
                $data['source_model'] = 'Magento\Eav\Model\Entity\Attribute\Source\Boolean';
                break;
            case 'multiselect':
            case 'select':
                $data['backend_model'] = 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend';
                break;
        }

        return $data;

    }

    /**
     * @param $type
     * @return array
     */
    public function getDefaulValue($data)
    {
        switch ($data['frontend_input']) {
            case 'text':
                $data['default_value'] = $data['value_text_field'];
                break;

            case 'textarea':
                $data['default_value'] = $data['value_text_area'];
                break;

            case 'date':
                $data['default_value'] = $data['value_date'];
                break;

            case 'multiselect':
                $data['default_value'] = '';
                break;

            case 'select':
                $data['default_value'] = '';
                break;

            case 'boolean':
                $data['default_value'] = $data['value_yes_no'];
                break;

            default:
                break;
        }

        return $data;
    }

    /**
     * @param $data
     * @return array
     */
    public function saveCustomOption($data)
    {
        $option = [
            'type' => $data['frontend_input'],
//            'validate_rules'=> $data['validate_rules_config'],
//            'is_checked' => $data['is_checked'],
        ];

        $optionArrayTemp = [];
        foreach ($data['option'] as $optionItem) {
            if (strlen($optionItem['title']) > 0) {
                $optionArrayTemp[] = $optionItem;
            }
        }
        $infoOption = serialize($optionArrayTemp);
        $option['value'] = $infoOption;


//        if ($data['is_checked'] == 1) {
//            $option['country_code'] = serialize($data['country']);
//        } else {
//            $option['country_code'] = '';
//        }

        return $option;
    }

}

