<?php
namespace ConfigWise\Configurator\Controller\Adminhtml\Components;

use Magento\Framework\App\Config\ScopeConfigInterface;
use ConfigWise\Configurator\Model\CwframeFactory;
use Magento\Framework\Event\ObserverInterface;
use ConfigWise\Configurator\Model\CwproductFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Catalog\Model\ProductFactory;
use Magento\Backend\App\Action\Context;

class Index extends \Magento\Backend\App\AbstractAction
{
    protected $_productFactory;
    protected $_messageManager;
    protected $_scopeConfig;
    protected $_cwframeFactory;
    protected $_cwproductFactory;
    protected $_storeManager;
    protected $_dir;

    public function __construct(
        Context $context,
        ProductFactory $productFactory,
        ScopeConfigInterface $scopeConfig,
        CwframeFactory $cwframeFactory,
        CwproductFactory $cwproductFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        MessageManagerInterface $messageManager,
        DirectoryList $dir
    ) {
        parent::__construct($context);
        $this->_productFactory = $productFactory;
        $this->_messageManager = $messageManager;
        $this->_scopeConfig = $scopeConfig;
        $this->_cwframeFactory = $cwframeFactory;
        $this->_cwproductFactory = $cwproductFactory;
        $this->_storeManager = $storeManager;
        $this->_logger = $logger;
        $this->_dir = $dir;
    }


    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($productId = $this->getRequest()->getParam('product_id')) {
            try {
                $_product = $this->_productFactory->create()->load($productId);
                $_sku = $_product->getSku();
                $result = json_decode($this->getComponentDetails($_sku), true);
                $frame = $result['images']['default']['frame360'];
                if (array_key_exists('error', $result)) {
                    $this->_logger->debug($result['error']);
                    $this->_messageManager->addError($result['error']);
                } else if($frame['original'] && $frame['small'] && $frame['medium'] && $frame['large']){
                    // Store frame in local database

                    $model = $this->_cwframeFactory->create();

                    $model->addData([
                      "product_number" => $_sku,
                      "original" => $frame['original'],
                      "small" => $frame['small'],
                      "medium" => $frame['medium'],
                      "large" => $frame['large']
                    ]);
                    $model->save();
                    $this->_messageManager->addSuccess(_('Product synced successfully!'));
                }

                $websiteUrl = $this->_storeManager->getStore()->getBaseUrl();
                $configWiseEnabled = $this->_scopeConfig->getValue('configwise/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

                $_urlKey = $_product->getUrlKey();

                $_sku = $_product->getSku();
                $_name = $_product->getName();
                $_description = $_product->getDescription();
                $_url = $websiteUrl . $_urlKey;

                $appThumbnail = $_product->getAppThumbnail();
                $thumbnailDir = $this->_dir->getRoot() . '/pub/media/catalog/product';

                $fileDir = $this->_dir->getRoot() . '/pub/media/catalog/product/file/';

                $iosFile = $_product->getIosFile();
                $androidFile = $_product->getAndroidFile();

                $_isConfigWiseEnabledForThisProduct = $_product->getConfigwiseEnable();

                if($_isConfigWiseEnabledForThisProduct && $configWiseEnabled){

                    $collection = $this->_cwproductFactory->create()->getCollection();
                    $collection->addFieldToFilter('product_number', $_sku);

                    if($collection->getSize()) {
                        $cwProduct = $collection->getFirstItem();

                        if($_name == $cwProduct['app_name'] && $_url == $cwProduct['product_url']){
                            $this->_logger->debug('Data is the same');
                        } else {
                            // Update URL and Name
                            $cwProduct->setAppName($_name);
                            $cwProduct->setProductUrl($_url);
                            $cwProduct->save();
                            $this->cwUpdateProduct($_sku, $_name, $_description, $_url);
                        }
                    } else {
                        $result = json_decode($this->cwCreateProduct($_sku, $_name, $_description, $_url), true);
                        if (array_key_exists('error', $result)) {
                            $this->_logger->debug($result['error']);
                            $this->_messageManager->addError($result['error']);
                        } else {
                            // Store product in local database
                            $model = $this->_cwproductFactory->create();
                            $model->addData([
                              "product_number" => $_sku,
                              "app_name" => $_name,
                              "product_url" => $_url
                            ]);
                            $model->save();
                            // Upload Thumbnail
                            if($appThumbnail){
                                $thumbnailPwd = $thumbnailDir . $appThumbnail;
                                $resultThumbnail = json_decode($this->cwUploadThumbnail($_sku, $thumbnailPwd), true);
                                if (array_key_exists('error', $resultThumbnail)) {
                                    $this->_messageManager->addError($resultThumbnail['error']);
                                }
                            }

                            // Upload iOS files
                            $this->_logger->debug('iosFile: ' . $iosFile);
                            if($iosFile){
                                $iosPwd = $fileDir . $iosFile;
                                $resultIos = json_decode($this->cwUploadIosFile($_sku, $iosPwd), true);
                                if (array_key_exists('error', $resultIos)) {
                                    $this->_messageManager->addError($resultIos['error']);
                                }
                            }

                            // Upload ANdroid files
                            $this->_logger->debug('androidFile: ' . $androidFile);
                            if($androidFile){
                                $androidPwd = $fileDir . $androidFile;
                                $resultAndroid = json_decode($this->cwUploadAndroidFile($_sku, $androidPwd), true);
                                if (array_key_exists('error', $resultAndroid)) {
                                    $this->_messageManager->addError($resultAndroid['error']);
                                }
                            }
                        }
                    }
                }



                $resultRedirect->setPath('catalog/product/edit', ['id' => $productId, '_current' => true]);
            } catch (\Exception $e) {
                $this->_messageManager->addError($e->getMessage());
                $resultRedirect->setPath('catalog/product/edit', ['id' => $productId, '_current' => true]);
            }
        } else {
            $this->_messageManager->addError(__('We can\'t find a product to perform this action'));
            $resultRedirect->setPath('catalog/product/index');
        }
        return $resultRedirect;
    }


    public function getComponentDetails($_sku) {
        try {
          	$url = "https://manage.configwise.io/configwise/api/components/".$_sku."/?include_360=true";
            $apiToken = $this->_scopeConfig->getValue('configwise/general/api_token', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

          	$ch = curl_init();
          	curl_setopt($ch, CURLOPT_URL, $url);
          	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                             'x-token:' . $apiToken,
                             'Accept: application/json'));

          	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          	$responseData = curl_exec($ch);
          	if(curl_errno($ch)) {
              $this->_logger->debug('curl_error: ' . curl_error($ch));
          		return curl_error($ch);
          	}
          	curl_close($ch);

        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
            $responseData;
            $responseData = new \stdClass();
            $responseData->error = $e->getMessage();
            $responseData = json_encode($responseData);
        }
        return $responseData;
    }

    public function cwCreateProduct($_sku, $_name, $_description, $_url) {
        try {
            $url = "https://manage.configwise.io/configwise/api/components";
            $apiToken = $this->_scopeConfig->getValue('configwise/general/api_token', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $cwproduct = new \stdClass();
            $cwproduct->productNumber = $_sku;
            $cwproduct->name = $_name;
            $cwproduct->appName = $_name;
            $cwproduct->isFloating = false;
            $cwproduct->description = $_description;
            $cwproduct->productLink = $_url;

            $cwproductJSON = json_encode($cwproduct);
            $this->_logger->debug('--------------------------------');
            $this->_logger->debug('$cwproductJSON:' .$cwproductJSON);
            $this->_logger->debug('--------------------------------');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                             'x-token:' . $apiToken,
                             'Content-Type: application/json',
                             'Accept: application/json',
                             'Content-Length: ' . strlen($cwproductJSON)));

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $cwproductJSON);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $responseData = curl_exec($ch);
            if(curl_errno($ch)) {
              $this->_logger->debug('curl_error: ' . curl_error($ch));
              return curl_error($ch);
            }
            curl_close($ch);

        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
            $responseData;
            $responseData = new \stdClass();
            $responseData->error = $e->getMessage();
            $responseData = json_encode($responseData);
        }
        return $responseData;
    }


    public function cwUpdateProduct($_sku, $_name, $_description, $_url) {
        try {
            $this->_logger->debug('----------------- Update CW 1--------------');
            $url = "https://manage.configwise.io/configwise/api/components/".$_sku;
            $apiToken = $this->_scopeConfig->getValue('configwise/general/api_token', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $cwproduct = new \stdClass();
            $cwproduct->name = $_name;
            $cwproduct->appName = $_name;
            $cwproduct->isFloating = false;
            $cwproduct->description = $_description;
            $cwproduct->productLink = $_url;

            $cwproductJSON = json_encode($cwproduct);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                             'x-token:' . $apiToken,
                             'Content-Type: application/json',
                             'Accept: application/json',
                             'Content-Length: ' . strlen($cwproductJSON)));

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $cwproductJSON);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $responseData = curl_exec($ch);
            if(curl_errno($ch)) {
              $this->_logger->debug('curl_error: ' . curl_error($ch));
              return curl_error($ch);
            }
            curl_close($ch);

        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
            $responseData;
            $responseData = new \stdClass();
            $responseData->error = $e->getMessage();
            $responseData = json_encode($responseData);
        }
        $this->_logger->debug('----------------- Update CW 2--------------');
        return $responseData;
    }

    public function cwUploadThumbnail($productNumber, $thumbnailPwd) {
        try {
            $url = "https://manage.configwise.io/configwise/api/components/" . $productNumber . "/thumbnail";
            $apiToken = $this->_scopeConfig->getValue('configwise/general/api_token', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

            if (function_exists('curl_file_create')) { // php 5.5+
              $cFile = curl_file_create($thumbnailPwd);
            } else {
              $cFile = '@' . realpath($thumbnailPwd);
            }
            $post = ['file' => $cFile];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                             'x-token:' . $apiToken,
                             'Accept: application/json'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $responseData = curl_exec($ch);
            if(curl_errno($ch)) {
              $this->_logger->debug('curl_error: ' . curl_error($ch));
              return curl_error($ch);
            }
            curl_close($ch);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
            $responseData;
            $responseData = new \stdClass();
            $responseData->error = $e->getMessage();
            $responseData = json_encode($responseData);
        }
        return $responseData;
    }

    public function cwUploadIosFile($productNumber, $iosFilePwd) {
        try {
            $this->_logger->debug('4444--------------------');
            $url = "https://manage.configwise.io/configwise/api/components/" . $productNumber . "/ios";
            $apiToken = $this->_scopeConfig->getValue('configwise/general/api_token', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

            if (function_exists('curl_file_create')) { // php 5.5+
              $cFile = curl_file_create($iosFilePwd);
            } else {
              $cFile = '@' . realpath($iosFilePwd);
            }
            $post = ['files[]' => $cFile];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                             'x-token:' . $apiToken,
                             'Accept: application/json',
                            'Content-Type: multipart/form-data'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $responseData = curl_exec($ch);
            $this->_logger->debug('5555--------------------');
            if(curl_errno($ch)) {
              $this->_logger->debug('curl_error: ' . curl_error($ch));
              return curl_error($ch);
            }
            curl_close($ch);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
            $this->_messageManager->addError($e->getMessage());
            $responseData;
            $responseData = new \stdClass();
            $responseData->error = $e->getMessage();
            $responseData = json_encode($responseData);
        }
        return $responseData;
    }

    public function cwUploadAndroidFile($productNumber, $androidFilePwd) {
        try {
            $url = "https://manage.configwise.io/configwise/api/components/" . $productNumber . "/android";
            $apiToken = $this->_scopeConfig->getValue('configwise/general/api_token', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

            if (function_exists('curl_file_create')) { // php 5.5+
              $cFile = curl_file_create($androidFilePwd);
            } else {
              $cFile = '@' . realpath($androidFilePwd);
            }
            $post = ['files[]' => $cFile];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                             'x-token:' . $apiToken,
                             'Accept: application/json',
                             'Content-Type: multipart/form-data'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $responseData = curl_exec($ch);
            if(curl_errno($ch)) {
              $this->_logger->debug('curl_error: ' . curl_error($ch));
              return curl_error($ch);
            }
            curl_close($ch);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
            $this->_messageManager->addError($e->getMessage());
            $responseData;
            $responseData = new \stdClass();
            $responseData->error = $e->getMessage();
            $responseData = json_encode($responseData);
        }
        return $responseData;
    }

    public function getStoreUrl($storeId)
    {
        return $this->_storeManager->getStore($storeId)->getBaseUrl();
    }


}
