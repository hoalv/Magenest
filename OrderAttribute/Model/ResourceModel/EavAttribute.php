<?php
namespace Magenest\OrderAttribute\Model\ResourceModel;

/**
 * Class CustomerAttribute
 * @package Magenest\AdminLogger\Model\ResourceModel
 */
class EavAttribute extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('eav_attribute', 'attribute_id');
    }

    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        $select->join(
            array('t_b' => 'magenest_orderattribute_order_eav_attribute'),
            $this->getMainTable() . '.attribute_id = t_b.attribute_id'
        );
        return $select;
    }

//    protected function _beforeSave( \Magento\Framework\Model\AbstractModel $object )
//    {
//        // do some model loads and checks here so that you will update existing data and not duplicate rows on editAction saves...
//        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//        $otherModel = $objectManager->create('Magenest\OrderAttribute\Model\OrderEavAttribute');
//        $otherModel->setData( 'order_eav_attribute', $object->getData( 'magenest_orderattribute_order_eav_attribute' ) )->save();
//        $object->setData( 'attribute_id', $otherModel->getAttributeId() );
//
//        return parent::_beforeSave( $object );
//    }
}
