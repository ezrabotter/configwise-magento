<?php

declare(strict_types=1);

namespace ConfigWise\Configurator\Controller\Adminhtml\Index;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;

class Index extends \Magento\Backend\App\Action
{

    protected $directoryList;
    
    protected $file;

    protected $resultPageFactory;

    private $productFactory;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        DirectoryList $directoryList,
        ProductFactory $productFactory,
        File $file
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->directoryList = $directoryList;
        $this->file = $file;
        $this->productFactory = $productFactory;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $storeId = $this->getRequest()->getParam('store', 0);
        $productId = $this->getRequest()->getParam('id');

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("Generating Desktop AR image..."));

        $this->_view->loadLayout();
        $this->_view->renderLayout();
        $this->messageManager->addSuccessMessage(__('Desktop AR image has been generated.'));
    }
    
    protected function getMediaDirTmpDir()
    {
        return $this->directoryList->getPath(DirectoryList::MEDIA) . '/' . 'tmp/';
    }
}
