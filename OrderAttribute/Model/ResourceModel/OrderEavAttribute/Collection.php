<?php
/**
 * Created by PhpStorm.
 * User: hoalv
 * Date: 13/03/2017
 * Time: 16:47
 */
namespace Magenest\OrderAttribute\Model\ResourceModel\OrderEavAttribute;

/**
 * Class Collection
 * @package Magenest\OrderAttribute\Model\ResourceModel\OrderEavAttribute
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     *  Initialize  resource    collection
     *
     * @return     void
     */
    public function _construct()
    {
        $this->_init('Magenest\OrderAttribute\Model\OrderEavAttribute', 'Magenest\OrderAttribute\Model\ResourceModel\OrderEavAttribute');
    }
}
