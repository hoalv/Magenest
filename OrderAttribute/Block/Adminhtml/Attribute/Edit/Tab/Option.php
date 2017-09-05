<?php
/**
 * Created by PhpStorm.<?php
 */

namespace Magenest\OrderAttribute\Block\Adminhtml\Attribute\Edit\Tab;

/**
 * Class DefaultInvoice
 * @package Magenest\PDFInvoice\Block\Adminhtml\Invoice\Edit\Tab
 */
class Option extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_template = 'option.phtml';
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var
     */
    protected $_fieldFactory;

    /**
     * @var
     */
    protected $coreAttribute;

    /**
     * @var \Magenest\CustomerAttributes\Model\CustomerOptionFactory
     */
    protected $optionFactory;

    /**
     * Option constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magenest\CustomerAttributes\Model\Status $status
     * @param \Magenest\CustomerAttributes\Helper\Data $customerData
     * @param \Magenest\CustomerAttributes\Model\CustomerOptionFactory $optionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magenest\OrderAttribute\Model\OrderOptionFactory $optionFactory,
        array $data = []
    ) {
        $this->optionFactory = $optionFactory;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return bool|string
     */
    public function getOptionArray()
    {
        $data = $this->_request->getParams();
        $array = '';
        if (isset($data['id'])) {
            $modelOption = $this->optionFactory->create()->load($data['id'], 'attribute_id');
            $option = $modelOption->getValue();
            $type = $modelOption->getType();
            if ($type == 'select' || $type == 'multiselect') {
                $array = unserialize($option);
            } else {
                $array = '';
            }
        }

        return $array;
    }

    public function getOptionArrayById($attributeId)
    {
        $array = '';
        if (isset($attributeId)) {
            $modelOption = $this->optionFactory->create()->load($attributeId, 'attribute_id');
            $option = $modelOption->getValue();
            $type = $modelOption->getType();
            if ($type == 'select' || $type == 'multiselect') {
                $array = unserialize($option);
            } else {
                $array = '';
            }
        }

        return $array;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        $countryCode = $this->_coreRegistry->registry('country_code');

        return $countryCode;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Option ');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Option ');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @param $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
