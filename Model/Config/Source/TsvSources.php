<?php
namespace ConfigWise\Configurator\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;

/**
 * Custom Attribute Renderer
 */
class TsvSources extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
        ['label'=>'Catalog', 'value'=>'1'],
        ['label'=>'Manual', 'value'=>'2']
        ];
        return $this->_options;
    }
}
