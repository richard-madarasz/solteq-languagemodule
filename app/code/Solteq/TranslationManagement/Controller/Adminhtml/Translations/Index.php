<?php

namespace Solteq\TranslationManagement\Controller\Adminhtml\Translations;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $number = $this->getRequest()->getParam('number');

        $resultPage = $this->resultPageFactory->create();
        // $resultPage->setActiveMenu('Solteq_TranslationManagement::menu');
        $resultPage->getConfig()->getTitle()->prepend((__('Translations')));

//        $messageBlock = $resultPage->getLayout()->createBlock(
//            'Magento\Framework\View\Element\Messages',
//            'answer'
//        );
//            $messageBlock->addSuccess($number . ' times 2 is ' . ($number * 2));
//
//        $resultPage->getLayout()->setChild(
//            'content',
//            $messageBlock->getNameInLayout(),
//            'answer_alias'
//        );

        return $resultPage;
    }
}