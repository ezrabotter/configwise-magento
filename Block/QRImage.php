<?php
namespace ConfigWise\Configurator\Block;

use ConfigWise\Configurator\Model\CwframeFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

class QRImage extends \Magento\Framework\View\Element\Template
{
    protected $_cwframeFactory;
    protected $httpHeader;
    protected $_dir;
    protected $_storeManager;
    protected $_fileSystem;
    protected $_io;
    protected $_fileDriver;
    private $productRepository;
    protected $request;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        ProductRepositoryInterface $productRepository,
        CwframeFactory $cwframeFactory,
        \Magento\Framework\HTTP\Header $httpHeader,
        \Magento\Framework\Filesystem\DirectoryList $dir,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Framework\Filesystem\Io\File $io,
        \Magento\Framework\Filesystem\Driver\File $fileDriver,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
        $this->productRepository = $productRepository;
        $this->_cwframeFactory = $cwframeFactory;
        $this->httpHeader = $httpHeader;
        $this->_dir = $dir;
        $this->_storeManager = $storeManager;
        $this->fileSystem = $fileSystem;
        $this->_io = $io;
        $this->_fileDriver = $fileDriver;
        $this->request = $context->getRequest();
    }

    public function getProduct()
    {
        $productId = $this->request->getParam('id');
        $_product = $this->productRepository->getById($productId);
        return $_product;
    }
    public function getFrame()
    {
        $collection = $this->_cwframeFactory->create()->getCollection();
        $_product = $this->getProduct();
        $_productNumber = $_product->getSku();

        $collection->addFieldToFilter('product_number', $_productNumber);
        if ($collection->getSize()) {
            $cwFrame = $collection->getFirstItem();
            if ($cwFrame['original']) {
                return $cwFrame['original'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getQr($url, $sku)
    {
        try {
            if ($this->qrImageExists($sku .'.png')) {
            } else {
                $qrCode = new QrCode($url);
                $qrCode->setSize(150);
                $qrCode->setMargin(0);
                $qrCode->setWriterByName('png');
                $qrCode->setEncoding('UTF-8');
                $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
                $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
                $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
                $qrCode->setValidateResult(false);
                $this->_io->mkdir($this->_dir->getPath('media') . '/qrcode/', 0775);
                $qrCode->writeFile($this->_dir->getPath('media') . '/qrcode/'. $sku .'.png');
            }
            return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'qrcode/'. $sku .'.png' ;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function qrImageExists($fileName)
    {
        $fileName = $this->_dir->getPath('media') . '/qrcode/'. $fileName;
        if ($this->_fileDriver->isExists($fileName)) {
            return true;
        } else {
            return false;
        }
    }
    public function getHttpUserAgent()
    {
        return $this->httpHeader->getHttpUserAgent();
    }
    public function getMediadirectory()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . '/configwise_image/';
    }
}
