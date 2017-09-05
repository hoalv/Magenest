<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderAttribute\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Backend\Block\Widget\Form;

/**
 * Blog post edit form main tab
 */
class Configuration extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
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

        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $magentoFields = array();
        $form->setHtmlIdPrefix('order_config_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Information')]);
        $fieldset->addField(
            'checkout_step',
            'select',
            [
                'name' => 'checkout_step',
                'label' => __('Show On Checkout Step'),
                'title' => __('Show On Checkout Step'),
                'required' => false,
                'options' => [
                    '1' => __('Shipping'),
                    '2' => __('Review & Payment'),
                ]
            ]
        );
        $fieldset->addField(
            'sorting_order',
            'text',
            [
                'name' => 'sorting_order',
                'label' => __('Display Sorting Order'),
                'title' => __('Display Sorting Order'),
                'required' => false,
                'after_element_html'=>'<small>Numeric, used in front-end to sort attributes.</small>',
            ]
        );
//        $fieldset->addField(
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
//        $fieldset->addField(
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
        $fieldset->addField(
            'include_pdf',
            'select',
            [
                'name' => 'include_pdf',
                'label' => __('Include Into PDF Documents'),
                'title' => __('Include Into PDF Documents'),
                'required' => false,
                'values' => $this->_yesNo->toOptionArray(),
            ]
        );

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $countries = $objectManager->get('Magento\Directory\Model\Config\Source\Country')->toOptionArray(false, 'US');
        $fieldset->addField(
            'country_id',
            'select',
            [
                'name' => 'country_id',
                'label' => __('Country'),
                'title' => __('Country'),
                'required' => false,
                'after_element_html'=>'<small><br/>The country in which you want to display the attribute.</small>',
                'values' => $countries,
            ]
        );

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
        return __('Configuration');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Configuration');
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
