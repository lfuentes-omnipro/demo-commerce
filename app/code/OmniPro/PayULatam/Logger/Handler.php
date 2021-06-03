<?php
namespace OmniPro\PayULatam\Logger;

class Handler extends  \Magento\Framework\Logger\Handler\Base
{
    
    protected $loggerType = Logger::DEBUG;
    protected $fileName = '/var/log/payulatam.log';
}
