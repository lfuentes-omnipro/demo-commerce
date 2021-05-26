<?php
namespace OmniPro\Nmi\Gateway\Http;

class TransferFactory implements \Magento\Payment\Gateway\Http\TransferFactoryInterface
{
    /**
     * @param \Magento\Payment\Gateway\Http\TransferBuilder
     */
    private $transferBuilder;

    /**
     * @param \Magento\Payment\Gateway\ConfigInterface
     */
    private $config;

    public function __construct(
        \Magento\Payment\Gateway\Http\TransferBuilder $transferBuilder,
        \Magento\Payment\Gateway\ConfigInterface $config
    )
    {
        $this->transferBuilder = $transferBuilder;
        $this->config = $config;
    }

    public function create(array $request) {
        return $this->transferBuilder
            ->setBody($request)
            ->setMethod('POST')
            ->setUri($this->config->getValue('nmi_gateway'))
            ->build();
    }
}