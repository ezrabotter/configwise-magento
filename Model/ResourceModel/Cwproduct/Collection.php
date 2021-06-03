<?php
namespace ConfigWise\Configurator\Model\ResourceModel\Cwproduct;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('ConfigWise\Configurator\Model\Cwproduct', 'ConfigWise\Configurator\Model\ResourceModel\Cwproduct');
	}
}
