<?php
namespace Magenest\OrderAttribute\Model\ResourceModel;


class OrderAttributeValue extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('magenest_orderattribute_order_attribute_value', 'id');
    }


}
