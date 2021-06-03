<?php
namespace OmniPro\Sections\CustomerData;

use function PHPUnit\Framework\isNull;

class CustomAttributes implements \Magento\Customer\CustomerData\SectionSourceInterface
{
    /**
     * @param \Magento\Customer\Helper\Session\CurrentCustomer
     */
    private $currentCustomer;

    public function __construct(
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
    )
    {
        $this->currentCustomer = $currentCustomer;
        
    }

    public function getSectionData() {
        if (!$this->currentCustomer->getCustomerId()) {
            return [];
        }

        $customer = $this->currentCustomer->getCustomer();
        $dniAttribute = $customer->getCustomAttribute('dni');
        $dniValue = !isNull($dniAttribute) ? $dniAttribute->getValue() : null;
        return [
            'dni' => $dniValue
        ];
    }
}