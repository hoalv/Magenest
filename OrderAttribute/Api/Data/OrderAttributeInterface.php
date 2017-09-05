<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */

namespace Magenest\OrderAttribute\Api\Data;

/**
 * @api
 */
interface OrderAttributeInterface extends \Magento\Eav\Api\Data\AttributeInterface
{
    const ENTITY_TYPE_CODE = 'order';
}
