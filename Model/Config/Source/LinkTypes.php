<?php
namespace ConfigWise\Configurator\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;

/**
 * Custom Attribute Renderer
 */
class LinkTypes extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var OptionFactory
     */
    protected $optionFactory;
    /**
     * @param OptionFactory $optionFactory
     */
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        /* your Attribute options list*/
        $this->_options=[
        ['label'=>'Select Option', 'value'=>''],
        ['label'=>'SKU Link', 'value'=>'1'],
        ['label'=>'Journey Link', 'value'=>'2'],
        ['label'=>'Manual Link', 'value'=>'3']
        ];
        return $this->_options;
    }
}
