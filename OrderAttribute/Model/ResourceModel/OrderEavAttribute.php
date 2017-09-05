<?php
namespace Magenest\OrderAttribute\Model\ResourceModel;

/**
 * Class CustomerAttribute
 * @package Magenest\AdminLogger\Model\ResourceModel
 */
class OrderEavAttribute extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('magenest_orderattribute_order_eav_attribute', 'id');
    }


}
