<?php
namespace OmniPro\Prueba\Controller\Bootcamp;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Prueba extends Action
{
    /**
     * @var PageFactory
     */
    protected $_pageFactory;

    /**
     * @param Context $context
     */
    public function __construct(
       Context $context,
       PageFactory $pageFactory
    )
    {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        return $this->_pageFactory->create();
    }
}
