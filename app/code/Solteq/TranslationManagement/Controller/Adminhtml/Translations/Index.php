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
            $langArray = $postparams['editedArray'];
            $file = $postparams['langFile'];

            foreach ($langArray as  $line) {
                if(isset($line[2]) && isset($line[3])) {
                    echo hash('ripemd160', $file);
                    $model->addData([
//                        'string' => $line[0] .' /****/ '. $line[1] .' /****/ '. $file .' /****/ '. $line[2] .' /****/ '.  $line[3],
                        'id' => hash('ripemd160', $line[0] . $file . $line[2] . $line[3]),
                        'string' => $line[0],
                        'translation' => $line[1],
                        'location' => $file,
                        'parent_type' => $line[2],
                        'parent_name' => $line[3]
                    ]);
                }
                else {
//                    echo hash('ripemd160', $line[0] . line[1] . $file);
                    $id = hash('ripemd160', $line[0] . $file);
                    $model->addData([
                        'id' => $id,
                        'string' => $line[0],
                        'translation' => $line[1],
                        'location' => $file,
                    ]);
                }
                $model->save();
            }
        }


        return $resultPage;
    }
}