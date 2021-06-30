<?php

namespace ConfigWise\Configurator\Block\Adminhtml;

use Magento\Framework\App\RequestInterface;

class Template extends \Magento\Backend\Block\Template
{
    protected $urlBuider;
    protected $request;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\UrlInterface $urlBuilder,
        RequestInterface $request,
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
        parent::__construct($context, $data);
    }
    public function createUrl($path, $paramsHere)
    {
        return $this->urlBuilder->getUrl($path, $paramsHere);
    }

    public function getProductId()
    {
        return $this->request->getParam('id');
    }
}
