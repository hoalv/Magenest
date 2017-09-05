<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderAttribute\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Backend\Block\Widget\Form;

/**
 * Blog post edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected $_groupRepository;
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;
    /**
     * @var \Magento\Framework\Convert\DataObject
     */
    protected $_objectConverter;


    protected $_status;

    /**
     * @var
     */
    protected $_fieldFactory;

    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    protected $_yesNo;

    /**
     * Main constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Convert\DataObject $objectConverter
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Convert\DataObject $objectConverter,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Config\Model\Config\Source\Yesno $yesno,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_yesNo = $yesno;
        $this->_groupRepository = $groupRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_objectConverter = $objectConverter;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * prepare form
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('attr_edit');
        $arr_customergroups = explode(',', $model->getCustomerGroups());
        $arr_storeids = explode(',', $model->getStoreIds());
        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $magentoFields = array();
        $form->setHtmlIdPrefix('order_rule_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Information')]);
//        $fieldsetConfig = $form->addFieldset('config_fieldset', ['legend' => __('Attribute Configuration')]);

        if ($model->getId()) {
            $fieldset->addField(
                'attribute_id',
                'hidden',
                [
                    'name' =>'id'
                ]
            );
        }
        $fieldset->addField(
            'frontend_label',
            'text',
            [
                'name' => 'frontend_label',
                'label' => __('Default Label'),
                'title' => __('Default Label'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'attribute_code',
            'text',
            [
                'name' => 'attribute_code',
                'label' => __('Attribute Code'),
                'title' => __('Attribute Code'),
                'required' => true,
                'after_element_html'=>'<small>This is used internally. Make sure you don\'t use spaces or more than 30 symbols.</small>',
            ]
        );
        $fieldset->addField(
            'store_ids',
            'multiselect',
            [
                'name'     => 'store_ids[]',
                'label'    => __('Store Views'),
                'title'    => __('Store Views'),
                'required' => true,
                'values'   => $this->_systemStore->getStoreValuesForForm(false, true),
                'value'    => $arr_storeids
            ]
        );

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $groupOptions = $objectManager->get('\Magento\Customer\Model\ResourceModel\Group\Collection')->toOptionArray();

        $fieldset->addField(
            'customer_groups',
            'multiselect',
            [
                'name' => 'customer_groups[]',
                'label' => __('Customer Group'),
                'title' => __('Customer Group'),
                'required' => true,
                'values' => $groupOptions,
                'disabled' => $isElementDisabled,
                'value' => $arr_customergroups
            ]
        );
        $fieldset->addField(
            'frontend_input',
            'select',
            [
                'label' => __('Select Input Type'),
                'title' => __('Select Input Type'),
                'name' => 'frontend_input',
                'required' => true,
                'options' => [
                    'text'          => 'Text Field',
                    'textarea'      => 'Text Area',
                    'date'          => 'Date',
                    'multiselect'   => 'Multiple Select',
                    'select'        => 'Dropdown',
                    'boolean'         => 'Yes/No'
                ],
            ]
        );
        $fieldset->addField(
            'frontend_input_hidden',
            'hidden',
            [
                'name' =>'frontend_input_hidden'
            ]
        );
        $fieldset->addField(
            'value_text_field',
            'text',
            [
                'name' => 'value_text_field',
                'label' => __('Default Value'),
                'title' => __('Default Value'),
            ]
        );
        $fieldset->addField(
            'value_text_area',
            'textarea',
            [
                'name' => 'value_text_area',
                'label' => __('Default Value'),
                'title' => __('Default Value'),
            ]
        );
        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $fieldset->addField(
            'value_date',
            'date',
            [
                'name' => 'value_date',
                'label' => __('Default Value'),
                'title' => __('Default Value'),
                'date_format' => $dateFormat,
            ]
        );
        $fieldset->addField(
            'value_yes_no',
            'select',
            [
                'name' => 'value_yes_no',
                'label' => __('Default Value'),
                'title' => __('Default Value'),
                'values' => $this->_yesNo->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'is_visible_on_front',
            'select',
            [
                'name' => 'is_visible_on_front',
                'label' => __('Visible On Frontend'),
                'title' => __('Visible On Frontend'),
                'required' => false,
                'values' => $this->_yesNo->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'is_visible_on_back',
            'select',
            [
                'name' => 'is_visible_on_back',
                'label' => __('Visible On Backend'),
                'title' => __('Visible On Backend'),
                'required' => false,
                'values' => $this->_yesNo->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'is_required',
            'select',
            [
                'name' => 'is_required',
                'label' => __('Values Required'),
                'title' => __('Values Required'),
                'values' => $this->_yesNo->toOptionArray(),
            ]
        );
//        $fieldsetConfig->addField(
//            'checkout_step',
//            'select',
//            [
//                'name' => 'checkout_step',
//                'label' => __('Show On Checkout Step'),
//                'title' => __('Show On Checkout Step'),
//                'required' => false,
//                'options' => [
//                    '1' => __('Shipping'),
//                    '2' => __('Review & Payment'),
//                ]
//            ]
//        );
//        $fieldsetConfig->addField(
//            'sorting_order',
//            'text',
//            [
//                'name' => 'sorting_order',
//                'label' => __('Display Sorting Order'),
//                'title' => __('Display Sorting Order'),
//                'required' => false,
//                'after_element_html'=>'<small>Numeric, used in front-end to sort attributes.</small>',
//            ]
//        );
//        $fieldsetConfig->addField(
//            'is_used_in_grid',
//            'select',
//            [
//                'name' => 'is_used_in_grid',
//                'label' => __('Show on Admin Grids'),
//                'title' => __('Show on Admin Grids'),
//                'required' => false,
//                'values' => $this->_yesNo->toOptionArray(),
//            ]
//        );
//        $fieldsetConfig->addField(
//            'include_html_print_order',
//            'select',
//            [
//                'name' => 'include_html_print_order',
//                'label' => __('Include Into HTML Print-out'),
//                'title' => __('Include Into HTML Print-out'),
//                'required' => false,
//                'after_element_html'=>'<small><br/>Order confirmation HTML print-out.</small>',
//                'values' => $this->_yesNo->toOptionArray(),
//            ]
//        );
//        $fieldsetConfig->addField(
//            'include_pdf',
//            'select',
//            [
//                'name' => 'include_pdf',
//                'label' => __('Include Into PDF Documents'),
//                'title' => __('Include Into PDF Documents'),
//                'required' => false,
//                'values' => $this->_yesNo->toOptionArray(),
//            ]
//        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Properties');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Properties');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

}
