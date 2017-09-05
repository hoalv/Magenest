<?php

namespace Magenest\OrderAttribute\Block\Checkout\View;

use Magento\Catalog\Block\Product\Context;

class Config extends \Magento\Framework\View\Element\Template
{
    protected $orderAttrEav;
    protected $orderOption;
    protected $eavConfig;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\OrderAttribute\Model\EavAttributeFactory $orderAttrEav,
        \Magenest\OrderAttribute\Model\OrderOptionFactory $orderOption,
        \Magento\Eav\Model\Config $eavConfig

    )
    {
        parent::__construct($context);
        $this->orderAttrEav = $orderAttrEav;
        $this->orderOption = $orderOption;
        $this->eavConfig = $eavConfig;
    }

    public  function getOrderAttribute(){
        $customAttr = $this->orderAttrEav->create()->getCollection();
        $arrAttr = [];
        foreach ($customAttr as $item){
            if($item->getEntityTypeId()=='5'){
                $arrAttr[] = $item;
            }
        }
        return $arrAttr;
    }

    public function getOrderOption(){
        $oppAttr = $this->orderOption->create()->getCollection();
        $arrOpp = [];
        foreach ($oppAttr as $oppItem){
            $arrOpp[] = $oppItem;
        }
        return $arrOpp;
    }

    public function getTest(){
        $a = 'ABC';
        return $a;
    }




}