<?php
namespace Solteq\TranslationManagement\Block;
class Index extends \Magento\Framework\View\Element\Template
{
    protected $_languageFiles = [];

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

    /**
     * Generate collect button html
     *
     * @return string
     */
    public function getButtonHtml($onclick, $lang)
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'collect_button',
                'label' => __('Edit'),
                'onclick' => $onclick . '"'. $lang.'")',
            ]
        );

        return $button->toHtml();
    }
}