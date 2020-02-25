<?php
namespace Solteq\TranslationManagement\Model\ResourceModel;


class Translation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('solteq_translationmanagement', 'id');
        $this->_isPkAutoIncrement = false;
    }

}
