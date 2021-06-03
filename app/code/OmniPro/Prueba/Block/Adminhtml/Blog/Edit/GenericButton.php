<?php
namespace OmniPro\Prueba\Block\Adminhtml\Blog\Edit;

class GenericButton
{
    /**
     * @param \Magento\Backend\Block\Widget\Context
     */
    private $context;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context
    

    ) {
        $this->context = $context;
    }

    public function getBackUrl() {
        return $this->getUrl('*/blog/');
    }

    public function getUrl($route = '', $params = []) {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
