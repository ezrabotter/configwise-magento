<?php

namespace ConfigWise\Configurator\Controller\Index;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    protected $directoryList;
    
    protected $file;

    protected $productFactory;

    protected $_backendUrl;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        JsonFactory $resultJsonFactory,
        DirectoryList $directoryList,
        ProductFactory $productFactory,
        File $file,
        \Magento\Backend\Model\UrlInterface $backendUrl
    ) {
        $this->_pageFactory = $pageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->directoryList = $directoryList;
        $this->file = $file;
        $this->productFactory = $productFactory;
        $this->_backendUrl = $backendUrl;
        return parent::__construct($context);
    }

    public function execute()
    {
        $productId = $this->getRequest()->getParam('id');

        $img = $this->getRequest()->getPostValue('imgdata');//$_POST['imgdata'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);

        $tmpDir = $this->getMediaDirTmpDir();
        $this->file->checkAndCreateFolder($tmpDir);

        $data = base64_decode($img);
        $file = $tmpDir . uniqid() . '.png';
        $success = file_put_contents($file, $data);

        $product = $this->productFactory->create()->load($productId);

        if ($success) {
            /**
*
 * add saved file to the $product gallery
*/
            $product->addImageToMediaGallery($file, [], true, false);
            $product->save();
            $resultJson = $this->resultJsonFactory->create();
            return $resultJson->setData(['redirect_url' => $this->getEditUrl($productId)]);
        }
    }

    protected function getMediaDirTmpDir()
    {
        return $this->directoryList->getPath(DirectoryList::MEDIA) . '/' . 'tmp/';
    }

    protected function getEditUrl($productId)
    {
        $params = ['id' => $productId, 'back' => null, '_current' => true];
        $url = $this->_backendUrl->getUrl("catalog/product/edit", $params);
        return $url;
    }
}
