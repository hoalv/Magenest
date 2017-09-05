<?php

namespace Magenest\OrderAttribute\Controller\Adminhtml\Attribute;

use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action
{

    protected $_coreRegistry = null;
    protected $resultPageFactory;
    /**
     * @var \Magenest\OrderAttribute\Model\OrderOptionFactory
     */
    protected $optionFactory;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\OrderAttribute\Model\OrderOptionFactory $optionFactory,
        \Magento\Framework\Registry $registry
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->optionFactory = $optionFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_OrderAttribute::attributes_list');
    }

    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magenest_OrderAttribute::attributes_list')
            ->addBreadcrumb(__('Order Attribute'), __('Order Attribute'))
            ->addBreadcrumb(__('Order Attribute'), __('Order Attribute'));
        return $resultPage;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Magenest\OrderAttribute\Model\EavAttribute');
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This attribute no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        // 3. Set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        $this->_coreRegistry->register('attr_edit', $model);
        if ($id) {
            $modelOption = $this->optionFactory->create()->load($id, 'attribute_id');
            $this->_coreRegistry->register('option_attribute', $modelOption);
        }

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()->prepend(__('Add New/Edit Attribute'));

        return $resultPage;
    }
}