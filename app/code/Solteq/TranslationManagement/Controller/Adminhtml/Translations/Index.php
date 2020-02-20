<?php

namespace Solteq\TranslationManagement\Controller\Adminhtml\Translations;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

class Index extends Action
{
    protected $resultPageFactory = false;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        parent::__construct($context);
        $this->registry = $registry;
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Translations')));
        $block = $this->resultPageFactory
            ->create()
            ->getLayout()
            ->createBlock('Solteq\TranslationManagement\Block\Index');

        $postparams = [
            'langFile' => $this->getRequest()->getParam('lang_file'),
            'newLine' => $this->getRequest()->getParam('new_line'),
            'deleteLine' => $this->getRequest()->getParam('delete_line'),
            'editedArray' => $this->getRequest()->getParam('editedArray'),
        ];

        if(isset($postparams['langFile'])) {
            $this->registry->register('currentFile',$postparams['langFile']);
        }

        if(isset($postparams['newLine'])) {
            $block->newLine($postparams['langFile']);
        }

        if(isset($postparams['deleteLine'])) {
            $block->deleteLine($postparams['langFile'], $postparams['deleteLine']);
        }

        if(isset($postparams['editedArray'])) {
            $block->saveLanguageFile($postparams['editedArray'],$postparams['langFile']);
        }


        return $resultPage;
    }
}