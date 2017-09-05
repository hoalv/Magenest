<?php
namespace Magenest\OrderAttribute\Block\Adminhtml\Order\Create;
use Magenest\OrderAttribute\Model\ResourceModel\OrderEavAttribute;
use Magento\Backend\Block\Template\Context;

class Custom extends \Magento\Backend\Block\Template
{

    private $eavSetupFactory;
    protected $eavConfig;
    protected $orderAttrEav;
    protected $logger;
    protected $_sessionQuote;
    protected $countryFactory;
    protected $curl;
    protected $objectManager;
    protected $attributeValueFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Eav\Model\Config $eavConfig,
        \Magenest\OrderAttribute\Model\OrderEavAttributeFactory $orderAttrEav,
        \Magenest\OrderAttribute\Model\OrderAttributeValueFactory $orderAttributeValueFactory,
        \Magento\Backend\Model\Session\Quote $sessionQuote,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Psr\Log\LoggerInterface $logger
    )
    {
        parent::__construct($context);
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
        $this->orderAttrEav = $orderAttrEav;
        $this->attributeValueFactory = $orderAttributeValueFactory;
        $this->_sessionQuote = $sessionQuote;
        $this->countryFactory = $countryFactory;
        $this->curl = $curl;
        $this->objectManager = $objectManager;
        $this->logger = $logger;

    }

    public  function getOrderAttribute(){
        $customAttr = $this->orderAttrEav->create()->getCollection();
        $arrAttr = [];
        foreach ($customAttr as $item){
            $arrAttr[] = $this->eavConfig->getAttribute('order', $item->getAttributeId().'');
        }
        return $arrAttr;
    }

    public  function getOrderAttributeValue($orderId, $attributeId){
        $valueRecord = $this->attributeValueFactory->create()->getCollection()->addFieldToFilter('order_id', $orderId);
        foreach ($valueRecord as $value){
            if($value->getAttributeId()==$attributeId){
                return $value->getAttributeValue();
            }
        }

        return '';
    }
    protected function _getSession()
    {
        return $this->_sessionQuote;
    }

    public function getCountrynameByCode($countryCode){
        $country = $this->countryFactory->create()->loadByCode($countryCode);
        return $country->getName();
    }

    public function getCountryNameByIp() {
        $visitorIp = $this->getVisitorIp();
        $url = "freegeoip.net/json/".$visitorIp;
        $this->curl->get($url);
        $response = json_decode($this->curl->getBody(), true);
        $countryName = $response['country_name'];
        $stateName = $response['region_name'];
        return $stateName;
    }

    function getVisitorIp() {
        $remoteAddress = $this->objectManager->create('Magento\Framework\HTTP\PhpEnvironment\RemoteAddress');
        return $remoteAddress->getRemoteAddress();
    }
}