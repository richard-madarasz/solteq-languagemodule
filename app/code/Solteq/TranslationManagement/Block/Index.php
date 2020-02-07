<?php
namespace Solteq\TranslationManagement\Block;
class Index extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Mageplaza\HelloWorld\Model\PostFactory $postFactory,
        \Magento\Framework\Data\Form\FormKey $formKey
    )
    {
        $this->_postFactory = $postFactory;
        $this->formKey = $formKey;
        parent::__construct($context);
    }
    protected $_languageFiles = [];

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    public function findLanguageFiles()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList')->getRoot() . '/app/code';
        $this->listFolderFiles($directory);
        return $this->_languageFiles;
    }

    function listFolderFiles($dir)
    {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                if (substr($file, -4) == '.csv') {
                    $this->_languageFiles[] = $dir . '/' . $file;
                }
                if (is_dir($dir . '/' . $file)) {
                    $this->listFolderFiles($dir . '/' . $file);
                }
            }
        }
        return;
    }

    function saveLanguageFile($arrayToSave, $languageFile) {
        if (($langFile = fopen($languageFile, "w")) !== false) {
            foreach ($arrayToSave as $lines) {
                fputcsv($langFile, $lines);
            }
            fclose($langFile);
        }
        return $arrayToSave[0][0];
    }

    function openLanguageFile($languageFile) {
        if (($langFile = fopen($languageFile, "r")) !== false) {
            while (($data = fgetcsv($langFile, 0, ",")) !== false) {
                $langArray[] = array(
                    $data[0],
                    $data[1]
                );
            }
            fclose($langFile);
        }
        return $langArray;
    }
}