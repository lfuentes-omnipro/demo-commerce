<?php
namespace OmniPro\Prueba\Observer;

class TopMenuObserverAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $menu = $observer->getData('menu');
        $transportObject = $observer->getData('transportObject');
        $this->logger->debug("after top menu");
    }
}