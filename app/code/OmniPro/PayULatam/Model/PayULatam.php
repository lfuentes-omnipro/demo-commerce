<?php

namespace OmniPro\PayULatam\Model;

use Magento\Payment\Model\InfoInterface;
use OmniPro\PayULatam\Logger;

class PayULatam extends \Magento\Payment\Model\Method\Cc
{
    const CODE = 'payulatam';
    const PAYU_URL = 'https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi';
    const PAYU_APPROVED = 'APPROVED';
    const PAYU_DECLINED = 'DECLINED';
    const PAYU_PENDING = 'PENDING';
    protected $_code = self::CODE;

    protected $_canAuthorize = true;
    protected $_canCapture = true;

    /**
     * @param \Magento\Framework\HTTP\Client\Curl
     */
    private $curl;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->curl = $curl;
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $moduleList,
            $localeDate,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Capture Payment.
     *
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     */
    public function capture(InfoInterface $payment, $amount)
    {
        try {
            //check if payment has been authorized
            if (is_null($payment->getParentTransactionId())) {
                $this->authorize($payment, $amount);
            }

            //build array of payment data for API request.
            $request = [
                'capture_amount' => $amount,
                //any other fields, api key, etc.
            ];

            //make API request to credit card processor.
            $response = $this->makeCaptureRequest($request);

            //todo handle response

            //transaction is done.
            $payment->setIsTransactionClosed(1);
        } catch (\Exception $e) {
            // $this->debug($payment->getData(), $e->getMessage());
        }

        return $this;
    }

    /**
     * Authorize a payment.
     *
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     */

    public function authorize(InfoInterface $payment, $amount)
    {

        $order = $payment->getOrder();
        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress() ?? $billingAddress;

        try {
            $strSignature = '4Vj8eK4rloUd272L48hsrarnUA~508029~' . $order->getIncrementId() . 'lk~' . $amount . '~COP';
            $md5 = md5($strSignature);
            ///build array of payment data for API request.
            $request = [
                "command" => "SUBMIT_TRANSACTION",
                "language" => "es",
                "merchant" => [
                    "apiKey" => "4Vj8eK4rloUd272L48hsrarnUA",
                    "apiLogin" => "pRRXKOl8ikMmt9u"
                ],
                "test" => true,
                "transaction" => [
                    "creditCard" => [
                        "expirationDate" => "2024/05",
                        "name" => $billingAddress->getFirstname() . $billingAddress->getLastname(),
                        "number" => $payment->getCcNumber(),
                        "securityCode" => "123"
                    ],
                    "order" => [
                        "accountId" => "512321",
                        "additionalValues" => [
                            "TX_TAX" => [
                                "currency" => "COP",
                                "value" => 0
                            ],
                            "TX_TAX_RETURN_BASE" => [
                                "currency" => "COP",
                                "value" => 0
                            ],
                            "TX_VALUE" => [
                                "currency" => "COP",
                                "value" => $amount
                            ]
                        ],
                        "buyer" => [
                            "contactPhone" => $billingAddress->getTelephone(),
                            "dniNumber" => null,
                            "emailAddress" => $billingAddress->getEmail(),
                            "fullName" => $billingAddress->getFirstname() . $billingAddress->getLastname(),
                            "merchantBuyerId" => null,
                            "shippingAddress" => [
                                "city" => $billingAddress->getCity(),
                                "country" => $billingAddress->getCountryId(),
                                "phone" => $billingAddress->getTelephone(),
                                "postalCode" => $billingAddress->getPostcode(),
                                "state" => $billingAddress->getRegionCode(),
                                "street1" => $billingAddress->getStreetLine1(),
                                "street2" => $billingAddress->getStreetLine2()
                            ]
                        ],
                        "description" => "OrderLuz",
                        "language" => "es",
                        "notifyUrl" => "http://www.test.com/confirmation",
                        "referenceCode" => $order->getIncrementId().'lk',
                        "shippingAddress" => [
                            "city" => $billingAddress->getCity(),
                            "country" => $billingAddress->getCountryId(),
                            "phone" => $billingAddress->getTelephone(),
                            "postalCode" => $billingAddress->getPostcode(),
                            "state" => $billingAddress->getRegionCode(),
                            "street1" => $billingAddress->getStreetLine1(),
                            "street2" => $billingAddress->getStreetLine2()
                        ],
                        "signature" => $md5
                    ],
                    "payer" => [
                        "billingAddress" => [
                            "city" => $billingAddress->getCity(),
                            "country" => $billingAddress->getCountryId(),
                            "phone" => $billingAddress->getTelephone(),
                            "postalCode" => $billingAddress->getPostcode(),
                            "state" => $billingAddress->getRegionCode(),
                            "street1" => $billingAddress->getStreetLine1(),
                            "street2" => $billingAddress->getStreetLine2()
                        ],
                        "emailAddress" => $billingAddress->getEmail(),
                        "fullName" => $billingAddress->getFirstname() . $billingAddress->getLastname(),
                        "merchantPayerId" => null
                    ],
                    "paymentCountry" => "CO",
                    "paymentMethod" => "VISA",
                    "type" => "AUTHORIZATION_AND_CAPTURE"
                ]

            ];


            //check if payment has been authorized////////////////////
            $response = $this->makeAuthRequest($request);
            $this->logger->debug($response);
        } catch (\Exception $e) {
            $this->debug($payment->getData(), $e->getMessage());
        }

        if (isset($response['transactionResponse'])) {
            if (isset($response['transactionResponse']['state'])) {
                switch ($response['transactionResponse']['state']) {
                    case self::PAYU_APPROVED:
                        $payment->setTransactionId($response['transactionResponse']['transactionId']);
                        $payment->setParentTransactionId($response['transactionResponse']['transactionId']);
                        $payment->setIsTransactionClosed(0);
                        break;
                    case self::PAYU_DECLINED:
                        break;
                }
            }
        }

        //processing is not done yet.


        return $this;
    }

    /**
     * Set the payment action to authorize_and_capture
     *
     * @return string
     */
    public function getConfigPaymentAction()
    {
        return self::ACTION_AUTHORIZE_CAPTURE;
    }

    /**
     * Test method to handle an API call for authorization request.
     *
     * @param $request
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function makeAuthRequest($request)
    {
        $this->logger->debug([
            'request' => $request,
            'json' => json_encode($request, JSON_UNESCAPED_SLASHES)
        ], null, true);
        $headers = ["Content-Type" => "application/json", "Accept" => "application/json"];
        $this->curl->setHeaders($headers);
        $this->curl->post(self::PAYU_URL, json_encode($request, JSON_UNESCAPED_SLASHES));
        $response = json_decode($this->curl->getBody(), true);
        $this->logger->debug([
            'response' => $response
        ], null, true);
        return $response;
    }

    /**
     * Test method to handle an API call for capture request.
     *
     * @param $request
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function makeCaptureRequest($request)
    {
        $response = ['success']; //todo implement API call for capture request.

        if (!$response) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Failed capture request.'));
        }

        return $response;
    }
}
