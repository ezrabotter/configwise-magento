<?php
namespace ConfigWise\Configurator\Block;

class Info extends \Magento\Config\Block\System\Config\Form\Field
{


    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = '<p>For mor information please go to our <b>documentation site</b>: <a target="_blank" href="https://docs.configwise.io/">docs.configwise.io</a></p>';

        $html .='<br/>';
        $html .='<p>Download the ConfigWise app to view your products in AR! Or use the ConfigWise iOS App to create 360 view render images.</p>';
        $html .='
        <div class="row">
          <div style="width:48%;display:inline-block;margin-right:2%;">
            <a  href="https://apps.apple.com/nl/app/configwise/id1362438883">
              <img  alt="" src="https://manage.configwise.io/assets/img/download-on-appstore.png">
            </a>
            <img  alt="" src="https://manage.configwise.io/assets/img/qr-download-on-appstore.png">
          </div>
          <div style="width:48%;display:inline-block;">
            <a href="https://play.google.com/store/apps/details?id=io.configwise.android&amp;hl=nl">
              <img alt="" src="https://manage.configwise.io/assets/img/download-on-googleplay.png">
            </a>
            <img alt="" src="https://manage.configwise.io/assets/img/qr-download-on-googleplay.png">
          </div>
        </div>
        <script>// <![CDATA[
            require([
                "jquery"
            ], function ($) {
                $("#row_configwise_info_test td.label").hide();
                $("#row_configwise_info_test td.value").css("width","80%");
            });
            // ]]>
        </script>
        ';
        return $html;
    }
}
