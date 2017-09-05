<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderAttribute\Block\Adminhtml\Attribute\Edit;

/**
 * Class Tabs
 * @package Magenest\AdminLogger\Block\Adminhtml\Logger\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('page_base_fieldset');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Order Attribute'));
    }
}
