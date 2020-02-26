<?php

namespace Solteq\TranslationManagement\Controller\Adminhtml\Translations;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Solteq\TranslationManagement\Model\TranslationFactory;

class Index extends Action
{
    protected $resultPageFactory = false;
    protected $translationFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        TranslationFactory $translationFactory,

        Registry $registry
    ) {
        parent::__construct($context);
        $this->registry = $registry;
        $this->resultPageFactory = $resultPageFactory;
        $this->translationFactory = $translationFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Translations')));
        $model = $this->translationFactory->create();

        $block = $this->resultPageFactory
            ->create()
            ->getLayout()
            ->createBlock('Solteq\TranslationManagement\Block\Index');

        $postparams = [
            'langFile' => $this->getRequest()->getParam('lang_file'),
            'newLine' => $this->getRequest()->getParam('new_line'),
            'deleteLine' => $this->getRequest()->getParam('delete_line'),
            'editedArray' => $this->getRequest()->getParam('editedArray'),
            'loadFromDatabase' => $this->getRequest()->getParam('load_database'),
        ];

        if(isset($postparams['langFile'])) {
            $this->registry->register('currentFile',$postparams['langFile']);
        }

        if(isset($postparams['newLine'])) {
            $block->newLine($postparams['langFile']);
        }

        if(isset($postparams['deleteLine'])) {
            $block->deleteLine($postparams['langFile'], $postparams['deleteLine']);

            $model->load($postparams['deleteLine'], 'string', $postparams['deleteLine'], 'translation');
            $model->delete();
        }

        if(isset($postparams['editedArray'])) {
            $block->saveLanguageFile($postparams['editedArray'],$postparams['langFile']);
            $block->saveLanguageFile($postparams['editedArray'],$postparams['langFile']);
            $block->saveToDatabase($postparams['editedArray'],$postparams['langFile']);
        }

        if (isset($postparams['loadFromDatabase'])) {
            $block->loadFromDatabase();
        }


        return $resultPage;
    }
}