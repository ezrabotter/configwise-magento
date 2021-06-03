<?php
namespace ConfigWise\Configurator\Block\Adminhtml\Product\Edit\Button;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ComponentDetailsButton extends Generic implements ButtonProviderInterface
{


    public function getButtonData()
    {
        $message = __('Are you sure you want to sync ?');
        return [
            'label' => __('Sync'),
            'on_click' => "confirmSetLocation('{$message}', '{$this->getComponentsUrl()}')",
            //'on_click' => "alert('it works')",
            'class' => 'action-secondary',
            'sort_order' => 100
        ];
    }

    /**
     * URL getter
     *
     * @return string
     */
    public function getComponentsUrl()
    {
        return $this->getUrl('configwise/components/index', ['product_id' => $this->getProduct()->getId()]);
    }
}
