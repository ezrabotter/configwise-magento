<?php
namespace ConfigWise\Configurator\Block\Adminhtml\Product\Edit\Button;

class GenerateQRImageButton extends \Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic
{
    public function getButtonData()
    {
        return [
            'label' => __('Generate QR image'),
            'class' => 'action-secondary',
            'on_click' => sprintf("location.href = '%s';", $this->getGenerateUrl()),
            'sort_order' => 10
        ];
    }

    /**
     * Get URL for back
     *
     * @return string
     */
    private function getGenerateUrl()
    {
        if ($this->context->getRequestParam('id')) {
            return $this->getUrl(
                'configwise/index/index',
                ['id' => $this->context->getRequestParam('id')]
            );
        }
        return $this->getUrl('*/*/');
    }
}
