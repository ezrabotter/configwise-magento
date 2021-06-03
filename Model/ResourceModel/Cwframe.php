<?php
namespace ConfigWise\Configurator\Model\ResourceModel;
class Cwframe extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	){
		parent::__construct($context);
	}
	protected function _construct()
	{
		$this->_init('configwise_frame', 'entity_id');
	}
}
