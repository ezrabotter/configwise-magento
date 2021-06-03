<?php

namespace ConfigWise\Configurator\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Catalog\Model\ResourceModel\Product as ResourceProduct;
use Magento\Catalog\Model\Product\Attribute\Frontend\Image as ImageFrontendModel;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class InstallData implements InstallDataInterface
{
    protected $_attributeSet;
    protected $_eavSetupFactory;
    protected $_resourceProduct;

    public function __construct(
        AttributeSet $attributeSet,
        EavSetupFactory $eavSetupFactory,
        ResourceProduct $resourceProduct
    ) {
        $this->_attributeSet    = $attributeSet;
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->_resourceProduct = $resourceProduct;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {
        $eavSetup = $this->_eavSetupFactory->create(["setup"=>$setup]);

        $eavSetup->addAttribute(
           \Magento\Catalog\Model\Product::ENTITY,
           'app_thumbnail',
           [
               'type' => 'varchar',
               'label' => 'App Thumbnail',
               'input' => 'media_image',
               'frontend' => ImageFrontendModel::class,
               'required' => false,
               'sort_order' => 10,
               'global' => ScopedAttributeInterface::SCOPE_STORE,
               'used_in_product_listing' => true
           ]
         );

       $eavSetup->addAttribute(
           \Magento\Catalog\Model\Product::ENTITY,
           'configwise_enable',
           [
               'type' => 'int',
               'sort_order' => 10,
               'backend' => '',
               'frontend' => '',
               'label' => 'Enable For ConfigWise',
               'input' => 'boolean',
               'class' => '',
               'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
               'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
               'visible' => true,
               'required' => false,
               'user_defined' => false,
               'default' => '',
               'searchable' => false,
               'filterable' => false,
               'comparable' => false,
               'visible_on_front' => false,
               'used_in_product_listing' => true,
               'unique' => false,
               'apply_to' => ''
           ]
       );

       $eavSetup->addAttribute(
           \Magento\Catalog\Model\Product::ENTITY,
           'arbutton_enable', [
               'type' => 'int',
               'sort_order' => 20,
               'backend' => '',
               'frontend' => '',
               'label' => 'Enable AR Button',
               'input' => 'boolean',
               'class' => '',
               'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
               'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
               'visible' => true,
               'required' => false,
               'user_defined' => false,
               'default' => '',
               'searchable' => false,
               'filterable' => false,
               'comparable' => false,
               'visible_on_front' => false,
               'used_in_product_listing' => true,
               'unique' => false,
               'apply_to' => ''
           ]
       );

       $eavSetup->addAttribute(
           \Magento\Catalog\Model\Product::ENTITY,
           'qr_enable', [
               'type' => 'int',
               'sort_order' => 30,
               'backend' => '',
               'frontend' => '',
               'label' => 'Enable QR Code',
               'input' => 'boolean',
               'class' => '',
               'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
               'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
               'visible' => true,
               'required' => false,
               'user_defined' => false,
               'default' => '',
               'searchable' => false,
               'filterable' => false,
               'comparable' => false,
               'visible_on_front' => false,
               'used_in_product_listing' => true,
               'unique' => false,
               'apply_to' => ''
           ]
       );

       $eavSetup->addAttribute(
           \Magento\Catalog\Model\Product::ENTITY,
           'tszview_enable', [
               'type' => 'int',
               'sort_order' => 40,
               'backend' => '',
               'frontend' => '',
               'label' => 'Enable 360 View',
               'input' => 'boolean',
               'class' => '',
               'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
               'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
               'visible' => true,
               'required' => false,
               'user_defined' => false,
               'default' => '',
               'searchable' => false,
               'filterable' => false,
               'comparable' => false,
               'visible_on_front' => false,
               'used_in_product_listing' => true,
               'unique' => false,
               'apply_to' => ''
           ]
       );


       $eavSetup->addAttribute(
           \Magento\Catalog\Model\Product::ENTITY,
           'companyref_enable', [
               'type' => 'int',
               'sort_order' => 50,
               'backend' => '',
               'frontend' => '',
               'label' => 'Enable Company Reference',
               'input' => 'boolean',
               'class' => '',
               'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
               'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
               'visible' => true,
               'required' => false,
               'user_defined' => false,
               'default' => '',
               'searchable' => false,
               'filterable' => false,
               'comparable' => false,
               'visible_on_front' => false,
               'used_in_product_listing' => true,
               'unique' => false,
               'apply_to' => ''
           ]
       );

       $eavSetup->addAttribute(
          \Magento\Catalog\Model\Product::ENTITY,
          'ar_deeplink_url',
          [
              'type' => 'int',
              'sort_order' => 60,
              'backend' => '',
              'frontend' => '',
              'label' => 'AR Deeplink URL',
              'input' => 'select',
              'class' => '',
              'source' => 'ConfigWise\Configurator\Model\Config\Source\LinkTypes',
              'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
              'visible' => true,
              'required' => false,
              'user_defined' => false,
              'default' => '',
              'searchable' => false,
              'filterable' => false,
              'comparable' => false,
              'visible_on_front' => false,
              'used_in_product_listing' => true,
              'unique' => false,
              'apply_to' => ''
          ]
      );

      $eavSetup->addAttribute(
          \Magento\Catalog\Model\Product::ENTITY,
          'arbaseurl_override', [
              'type' => 'text',
              'sort_order' => 70,
      				'backend' => '',
      				'frontend' => '',
      				'label' => 'Override AR Base URL',
      				'input' => 'text',
      				'class' => '',
      				'source' => '',
      				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
      				'visible' => true,
      				'required' => false,
      				'user_defined' => false,
      				'default' => '',
      				'searchable' => false,
      				'filterable' => false,
      				'comparable' => false,
      				'visible_on_front' => false,
      				'used_in_product_listing' => true,
      				'unique' => false,
      				'apply_to' => ''
          ]
      );


      $eavSetup->addAttribute(
          \Magento\Catalog\Model\Product::ENTITY,
          'journeybaseurl_override', [
              'type' => 'text',
              'sort_order' => 80,
      				'backend' => '',
      				'frontend' => '',
      				'label' => 'Override Journey Base URL',
      				'input' => 'text',
      				'class' => '',
      				'source' => '',
      				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
      				'visible' => true,
      				'required' => false,
      				'user_defined' => false,
      				'default' => '',
      				'searchable' => false,
      				'filterable' => false,
      				'comparable' => false,
      				'visible_on_front' => false,
      				'used_in_product_listing' => true,
      				'unique' => false,
      				'apply_to' => ''
          ]
      );

      $eavSetup->addAttribute(
    			\Magento\Catalog\Model\Product::ENTITY,
    			'manual_url',
    			[
      				'type' => 'text',
              'sort_order' => 90,
      				'backend' => '',
      				'frontend' => '',
      				'label' => 'Manual URL',
      				'input' => 'text',
      				'class' => '',
      				'source' => '',
      				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
      				'visible' => true,
      				'required' => false,
      				'user_defined' => false,
      				'default' => '',
      				'searchable' => false,
      				'filterable' => false,
      				'comparable' => false,
      				'visible_on_front' => false,
      				'used_in_product_listing' => true,
      				'unique' => false,
      				'apply_to' => ''
    			]
		);

    $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'caption_qr_code',
        [
            'type' => 'text',
            'sort_order' => 100,
            'backend' => '',
            'frontend' => '',
            'label' => 'Caption QR Code',
            'input' => 'text',
            'class' => '',
            'source' => '',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => ''
          ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'caption_button',
            [
                'type' => 'text',
                'sort_order' => 110,
                'backend' => '',
                'frontend' => '',
                'label' => 'Caption Button',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
              ]
            );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'css_override_button',
            [
                'type' => 'text',
                'sort_order' => 120,
                'backend' => '',
                'frontend' => '',
                'label' => 'CSS Override button',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
              ]
            );



        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'android_file',
            [
                'type' => 'varchar',
                'sort_order' => 130,
                'label' => 'Android 3D Asset',
                'input' => 'file',
                'backend' => 'ConfigWise\Configurator\Model\Product\Attribute\Backend\File',
                'frontend' => '',
                'class' => '',
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'unique' => false,
                'apply_to' => 'simple,configurable', // applicable for simple and configurable product
                'used_in_product_listing' => false
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'ios_file',
            [
                'type' => 'varchar',
                'sort_order' => 140,
                'label' => 'iOS File',
                'input' => 'file',
                'backend' => 'ConfigWise\Configurator\Model\Product\Attribute\Backend\File',
                'frontend' => '',
                'class' => '',
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'unique' => false,
                'apply_to' => 'simple,configurable', // applicable for simple and configurable product
                'used_in_product_listing' => false
            ]
        );

        $eavSetup->addAttribute(
           \Magento\Catalog\Model\Product::ENTITY,
           'tsv_source',
           [
               'type' => 'int',
               'sort_order' => 150,
               'backend' => '',
               'frontend' => '',
               'label' => '360 Source',
               'input' => 'select',
               'class' => '',
               'source' => 'ConfigWise\Configurator\Model\Config\Source\TsvSources',
               'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
               'visible' => true,
               'required' => false,
               'user_defined' => false,
               'default' => '',
               'searchable' => false,
               'filterable' => false,
               'comparable' => false,
               'visible_on_front' => false,
               'used_in_product_listing' => true,
               'unique' => false,
               'apply_to' => ''
           ]
       );

       $eavSetup->addAttribute(
           \Magento\Catalog\Model\Product::ENTITY,
           'tsv_url_override', [
               'type' => 'text',
               'sort_order' => 110,
               'backend' => '',
               'frontend' => '',
               'label' => 'Override 360 URL',
               'input' => 'text',
               'class' => '',
               'source' => '',
               'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
               'visible' => true,
               'required' => false,
               'user_defined' => false,
               'default' => '',
               'searchable' => false,
               'filterable' => false,
               'comparable' => false,
               'visible_on_front' => false,
               'used_in_product_listing' => true,
               'unique' => false,
               'apply_to' => ''
           ]
       );

       $eavSetup->addAttribute(
           \Magento\Catalog\Model\Product::ENTITY,
           'caption_tsv',
           [
               'type' => 'text',
               'sort_order' => 170,
               'backend' => '',
               'frontend' => '',
               'label' => 'Caption 360 View',
               'input' => 'text',
               'class' => '',
               'source' => '',
               'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
               'visible' => true,
               'required' => false,
               'user_defined' => false,
               'default' => '',
               'searchable' => false,
               'filterable' => false,
               'comparable' => false,
               'visible_on_front' => false,
               'used_in_product_listing' => true,
               'unique' => false,
               'apply_to' => ''
             ]
           );

        $groupName = 'ConfigWise';
        $groupImagesName ='Images';
        $entityTypeId = $eavSetup->getEntityTypeId('catalog_product');
        $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);
        foreach($attributeSetIds as $attributeSetId) {
            $eavSetup->addAttributeGroup($entityTypeId, $attributeSetId, $groupName, 19);

            $attributeGroupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);
            $attributeGroupImagesId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupImagesName);

            $attributeAppThumbnailId = $eavSetup->getAttributeId($entityTypeId, 'app_thumbnail');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupImagesId, $attributeAppThumbnailId, null);

            $attributeConfigwiseEnableId = $eavSetup->getAttributeId($entityTypeId, 'configwise_enable');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeConfigwiseEnableId, 10);

            $attributeArbuttonEnableId = $eavSetup->getAttributeId($entityTypeId, 'arbutton_enable');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeArbuttonEnableId, 20);

            $attributeQrEnableId = $eavSetup->getAttributeId($entityTypeId, 'qr_enable');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeQrEnableId, 30);

            $attributeTszviewEnableId = $eavSetup->getAttributeId($entityTypeId, 'tszview_enable');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeTszviewEnableId, 40);

            $attributeCompanyrefEnableId = $eavSetup->getAttributeId($entityTypeId, 'companyref_enable');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeCompanyrefEnableId, 50);

            $attributeArDeeplinkUrlId = $eavSetup->getAttributeId($entityTypeId, 'ar_deeplink_url');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeArDeeplinkUrlId, 60);

            $attributeArbaseurlOverrideId = $eavSetup->getAttributeId($entityTypeId, 'arbaseurl_override');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeArbaseurlOverrideId, 70);

            $attributeJourneybaseurlOverrideId = $eavSetup->getAttributeId($entityTypeId, 'journeybaseurl_override');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeJourneybaseurlOverrideId, 80);

            $attributeManualUrlId = $eavSetup->getAttributeId($entityTypeId, 'manual_url');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeManualUrlId, 90);

            $attributeCaptionQrCodeId = $eavSetup->getAttributeId($entityTypeId, 'caption_qr_code');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeCaptionQrCodeId, 100);

            $attributeCaptionButtonId = $eavSetup->getAttributeId($entityTypeId, 'caption_button');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeCaptionButtonId, 110);

            $attributeCssOverrideButtonId = $eavSetup->getAttributeId($entityTypeId, 'css_override_button');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeCssOverrideButtonId, 120);

            $attributeAndroidFileId = $eavSetup->getAttributeId($entityTypeId, 'android_file');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeAndroidFileId, 130);

            $attributeIosFileId = $eavSetup->getAttributeId($entityTypeId, 'ios_file');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeIosFileId, 140);

            $attributeTsvSourceId = $eavSetup->getAttributeId($entityTypeId, 'tsv_source');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeTsvSourceId, 160);

            $attributeTsvUrlOverrideId = $eavSetup->getAttributeId($entityTypeId, 'tsv_url_override');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeTsvUrlOverrideId, 170);

            $attributeCaptionTsvId = $eavSetup->getAttributeId($entityTypeId, 'caption_tsv');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeCaptionTsvId, 180);

        }
    }
}
