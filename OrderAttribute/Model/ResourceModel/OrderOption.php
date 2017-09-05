<?php
namespace Magenest\OrderAttribute\Model\ResourceModel;


class OrderOption extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('magenest_orderattribute_order_option_value', 'id');
    }


}
