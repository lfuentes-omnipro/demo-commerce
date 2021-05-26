<?php
namespace OmniPro\PayULatam\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class CustomConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $_assetRepo;

    /**
     * @var string
     */
    protected $methodCode = \OmniPro\PayuLatam\Model\PayuLatam::CODE;

    public function __construct(
        \Magento\Framework\View\Asset\Repository $assetRepo
    )
    {
        $this->_assetRepo = $assetRepo;
    }

    public function getConfig()
    {
        return [
            'payment' => [
                $this->methodCode => [
                    'logoUrl' => $this->_assetRepo->getUrl("OmniPro_PayULatam::images/logo.png")
                ]
            ]
        ];
    }
}
