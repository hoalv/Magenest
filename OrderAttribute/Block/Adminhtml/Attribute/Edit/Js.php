<?php
/**
 * Created by PhpStorm.
 * User: hoang
 * Date: 05/07/2016
 * Time: 11:01
 */
namespace Magenest\OrderAttribute\Block\Adminhtml\Attribute\Edit;

use Magento\Backend\Block\Template\Context;

/**
 * Class Js
 * @package Magenest\CustomerAttributes\Block\Adminhtml\Customer\Edit
 */
class Js extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magenest\OrderAttribute\Model\OrderOptionFactory
     */
    protected $optionFactory;

    protected $eavAttributeFactory;

    /**
     * Js constructor.
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magenest\OrderAttribute\Model\OrderOptionFactory $OrderOptionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Magenest\OrderAttribute\Model\OrderOptionFactory $orderOptionFactory,
        \Magenest\OrderAttribute\Model\EavAttributeFactory $eavAttributeFactory,
        array $data
    ) {
        $this->optionFactory = $orderOptionFactory;
        $this->eavAttributeFactory = $eavAttributeFactory;
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getCollection()
    {
        $data = $this->_request->getParams();
        $model = ' ';
        if (isset($data['id'])) {
            $model = $this->eavAttributeFactory->create()->load($data['id']);
        }

        return $model;
    }
    /**
     * @param $attributeId
     * @return $this
     */
    public function getOptionAttribute($attributeId)
    {
        $modelOption = $this->optionFactory->create()->load($attributeId, 'attribute_id');
        
        return $modelOption;
    }
}
