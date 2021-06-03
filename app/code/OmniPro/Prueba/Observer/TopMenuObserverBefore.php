<?php
namespace OmniPro\Prueba\Observer;

class TopMenuObserverBefore implements \Magento\Framework\Event\ObserverInterface
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
        $block = $observer->getData('block');
        $request = $observer->getData('request');
        $this->logger->debug("before top menu");
    }
}