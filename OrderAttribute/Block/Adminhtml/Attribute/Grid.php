<?php
namespace Magenest\OrderAttribute\Block\Adminhtml\Attribute;

use Magenest\OrderAttribute\Model\ResourceModel\EavAttribute;
use    Magento\Backend\Block\Widget\Grid as WidgetGrid;

class    Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $_eavAttributeCollection;

    private $eavSetupFactory;

    /**
     * @param    \Magento\Backend\Block\Template\Context $context
     * @param    \Magento\Backend\Helper\Data $backendHelper
     * @param

     * @param    array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
//        \Magenest\OrderAttribute\Model\ResourceModel\OrderEavAttribute\Collection $eavAttributeCollection,
        \Magenest\OrderAttribute\Model\ResourceModel\EavAttribute\Collection $eavAttributeCollection,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        array $data = []
    )
    {
        $this->_eavAttributeCollection = $eavAttributeCollection;
        $this->eavSetupFactory = $eavSetupFactory;
        parent::__construct($context, $backendHelper, $data);
        $this->setEmptyText(__('No	Attribute	Found'));
    }

    /**
     *    Initialize    the    subscription    collection
     *
     * @return    WidgetGrid
     */
    protected function _prepareCollection()
    {
        $collection = $this->_eavAttributeCollection->getSelect()->join(
            ['sm' => 'magenest_orderattribute_order_eav_attribute'],
            'main_table.attribute_id = sm.attribute_id'
        );
        $this->setCollection($this->_eavAttributeCollection);
        return parent::_prepareCollection();
    }

    /**
     *    Prepare    grid    columns
     *
     * @return    $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'attribute_id',
            [
                'header' => __('ID'),
                'index' => 'attribute_id',
            ]
        );
        $this->addColumn(
            'attribute_code',
            [
                'header' => __('Attribute code'),
                'index' => 'attribute_code',
            ]
        );
        $this->addColumn(
            'attribute_label',
            [
                'header' => __('Attribute label'),
                'index' => 'frontend_label',
            ]
        );
        $this->addColumn(
            'is_visible_on_back',
            [
                'header' => __('Visible on back'),
                'index' => 'is_visible_on_back',
            ]
        );
        $this->addColumn(
            'is_visible_on_front',
            [
                'header' => __('Visible on front'),
                'index' => 'is_visible_on_front',
            ]
        );
        $this->addColumn(
            'checkout_step',
            [
                'header' => __('Checkout step'),
                'index' => 'checkout_step',
            ]
        );
        $this->addColumn(
            'include_pdf',
            [
                'header'	=>	__('Include into PDF'),
                'index'	=>	'include_pdf',
            ]
        );
        $this->addColumn(
            'default_value',
            [
                'header'	=>	__('Default Value'),
                'index'	=>	'default_value',
            ]
        );
        $this->addColumn('edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [['caption' => __('Edit'),
                    'url' => ['base' => 'orderattribute/attribute/edit'],'field' => 'id'],],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );
        return $this;
    }

}