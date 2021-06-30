<?php
namespace ConfigWise\Configurator\Model;

class Cwframe extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('ConfigWise\Configurator\Model\ResourceModel\Cwframe');
    }
}
