<?php
/**
 * @copyright 2017 Sapient
 */
namespace Sapient\Worldpay\Model\XmlBuilder;

use Sapient\Worldpay\Model\XmlBuilder\Config\ThreeDSecureConfig;
use \Sapient\Worldpay\Logger\WorldpayLogger;

/**
 * Build xml for token inquiry request
 */
class TokenInquiry
{
    const TOKEN_SCOPE = 'shopper';
    const ROOT_ELEMENT = <<<EOD
<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE paymentService PUBLIC '-//WorldPay/DTD WorldPay PaymentService v1//EN'
        'http://dtd.worldpay.com/paymentService_v1.dtd'> <paymentService/>
EOD;

    /**
     * @var Mage_Customer_Model_Customer
     */
    private $customer;

    /**
     * @var Sapient_WorldPay_Model_Token
     */
    private $tokenModel;

    /**
     * @var string
     */
    protected $merchantCode;

    public function __construct(array $args = array())
    {
        if (isset($args['tokenModel']) && $args['tokenModel'] instanceof \Sapient\WorldPay\Model\SavedToken) {
            $this->tokenModel = $args['tokenModel'];
        }

        if (isset($args['customer']) && $args['customer'] instanceof \Magento\Customer\Model\Customer) {
            $this->customer = $args['customer'];
        }

        if (isset($args['merchantCode'])) {
            $this->merchantCode = $args['merchantCode'];
        }
    }

    /**
     * Build xml for processing Request
     * @return SimpleXMLElement $xml
     */
    public function build()
    {
        $xml = new \SimpleXMLElement(self::ROOT_ELEMENT);
        $xml['version'] = '1.4';
        $xml['merchantCode'] = $this->merchantCode;

        $inquiry = $this->_addInquiryElement($xml);
        $this->_addTokenInquiryElement($inquiry);

        return $xml;
    }

    /**
     * @param SimpleXMLElement $xml
     * @return SimpleXMLElement
     */
    private function _addInquiryElement($xml)
    {
        return $xml->addChild('inquiry');
    }

    /**
     * @param SimpleXMLElement $inquiry
     * @return SimpleXMLElement $xml
     */
    private function _addTokenInquiryElement($inquiry)
    {
        $tokenInquiry = $inquiry->addChild('paymentTokenInquiry');
        $tokenInquiry['tokenScope'] = self::TOKEN_SCOPE;

        $tokenInquiry->addChild('authenticatedShopperID', $this->customer->getId());
        $tokenInquiry->addChild('paymentTokenID', $this->tokenModel->getTokenCode());
        
        return $tokenInquiry;
    }
}
