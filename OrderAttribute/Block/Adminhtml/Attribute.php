<?php
namespace	Magenest\OrderAttribute\Block\Adminhtml;
class	Attribute	extends	\Magento\Backend\Block\Widget\Grid\Container
{
    protected	function	_construct()
    {
        $this->_blockGroup	=	'Magenest_OrderAttribute';
        $this->_controller	=	'adminhtml_attribute';
        parent::_construct();

    }
}