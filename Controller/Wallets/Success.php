<?php
/**
 * @copyright 2017 Sapient
 */
namespace Sapient\Worldpay\Controller\Wallets;

use Magento\Framework\App\Action\Context;
use Exception;

class Success extends \Magento\Framework\App\Action\Action
{
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Sapient\Worldpay\Logger\WorldpayLogger $wplogger
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(Context $context,
        \Sapient\Worldpay\Logger\WorldpayLogger $wplogger,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->wplogger = $wplogger;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Renders the 3D Secure  page, responsible for forwarding
     * all necessary order data to worldpay.
     */
    public function execute()
    {
        return $this->resultRedirectFactory->create()->setPath('checkout/onepage/success', ['_current' => true]);
    }
}
