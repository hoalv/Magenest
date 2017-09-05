<?php

namespace Magenest\OrderAttribute\Block\Adminhtml\Attribute;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize blog post edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'attribute_id';
        $this->_blockGroup = 'Magenest_OrderAttribute';
        $this->_controller = 'adminhtml_attribute';
        parent::_construct();
//        $this->removeButton('save');
//        $this->removeButton('back');
//        $this->removeButton('reset');
        $this->buttonList->update('save', 'label', __('Save Attribute'));
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );

        $this->buttonList->add(
            'delete',
            [
                'label' => __('Delete'),
                'onclick' => 'deleteConfirm(' . json_encode(__('Are you sure you want to delete this attribute ?'))
                    . ','
                    . json_encode($this->getDeleteUrl()
                    )
                    . ')',
                'class' => 'scalable delete',
                'level' => -1
            ]
        );
//        $this->buttonList->update('delete', 'label', __('Delete'));
    }

    /**
     * Retrieve text for header element depending on loaded post
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('attr_edit')->getId()) {
            return __("Edit List '%1'", $this->escapeHtml($this->_coreRegistry->registry('attr_edit')->getTitle()));
        } else {
            return __('New Info');
        }
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('orderattribute/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }

    public function getDeleteUrl()
    {
        $params = $this->_coreRegistry->registry('attr_edit')->getId();
        return $this->getUrl('orderattribute/*/delete', ['id'=> $params]);
    }
}
