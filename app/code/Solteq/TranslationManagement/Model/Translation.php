<?php
namespace Solteq\TranslationManagement\Model;
class Translation extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Solteq\TranslationManagement\Model\ResourceModel\Translation');
    }
}
