<?php
namespace Solteq\TranslationManagement\Model\ResourceModel\Translation;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection{

    public function _construct()
    {
        $this->_init("Solteq\TranslationManagement\Model\Translation","Solteq\TranslationManagement\Model\ResourceModel\Translation");
    }
}
?>