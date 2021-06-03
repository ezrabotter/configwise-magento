<?php
namespace ConfigWise\Configurator\Model\ResourceModel\Cwframe;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('ConfigWise\Configurator\Model\Cwframe', 'ConfigWise\Configurator\Model\ResourceModel\Cwframe');
	}
}
