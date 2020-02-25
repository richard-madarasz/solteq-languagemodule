<?php

namespace Solteq\TranslationManagement\Block;

use Magento\Framework\View\Element\Template\Context;
use Mageplaza\HelloWorld\Model\PostFactory;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Registry;
use Solteq\TranslationManagement\Model\TranslationFactory;

/**
 * @property PostFactory _postFactory
 * @property FormKey formKey
 * @property DirectoryList dir
 */

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_languageFiles = [];
    protected $_currentFile;

    public function __construct(
        Context $context,
        PostFactory $postFactory,
        FormKey $formKey,
        DirectoryList $dir,
        Registry $registry,
        TranslationFactory $translationFactory
    ) {
        $this->_postFactory = $postFactory;
        $this->formKey = $formKey;
        $this->dir = $dir;
        $this->registry = $registry;
        $this->translationFactory = $translationFactory;
        parent::__construct($context);
    }

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    public function findLanguageFiles()
    {
        $this->listFolderFiles($this->dir->getPath('app'));
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

    function newLine($languageFile)
    {
        if (($langFile = fopen($languageFile, "a")) !== false) {
            fputcsv($langFile, ["New string", "New translation"], ",");
            fclose($langFile);
        }
        return;
    }

    function deleteLine($languageFile, $lineToDelete)
    {
        if (($langFile = fopen($languageFile, "r")) !== false) {
            while (($data = fgetcsv($langFile, 0, ",")) !== false) {
                if(isset($data[2]) && isset($data[3])) {
                    $langArray[] = array(
                        $data[0],
                        $data[1],
                        $data[2],
                        $data[3]
                    );
                }
                else {
                    $langArray[] = array(
                        $data[0],
                        $data[1],
                    );
                }
            }
            fclose($langFile);
        }

        $model = $this->translationFactory->create();
        if(isset($langArray[$lineToDelete][2]) && isset($langArray[$lineToDelete][3])) {
            $model->load(hash('ripemd160', $langArray[$lineToDelete][0] . $languageFile . $langArray[$lineToDelete][2] . $langArray[$lineToDelete][3]));
        }
        else {
            $model->load(hash('ripemd160', $langArray[$lineToDelete][0] . $languageFile));

        }
        $model->delete();
        unset($langArray[$lineToDelete]);
        $this->saveLanguageFile($langArray, $languageFile);
    }

    function saveLanguageFile($arrayToSave, $languageFile)
    {
        if (($langFile = fopen($languageFile, "w")) !== false) {
            foreach ($arrayToSave as $lines) {
                fputcsv($langFile, $lines);
            }
            fclose($langFile);
        }
        return;
    }

    function openLanguageFile($languageFile)
    {
        $langArray = [];
        if (($langFile = fopen($languageFile, "r")) !== false) {
            while (($data = fgetcsv($langFile, 0, ",")) !== false) {
                if(isset($data[2]) && isset($data[3])) {
                    $langArray[] = array(
                        $data[0],
                        $data[1],
                        $data[2],
                        $data[3]
                    );
                }
                else {
                    $langArray[] = array(
                        $data[0],
                        $data[1],
                    );
                }
            }
            fclose($langFile);
        }
        return $langArray;
    }

    function languageFileToName($languageFile)
    {
        $nameList = array(
            'en_US.csv' => 'English',
            'fi_FI.csv' => 'Finnish',
            'hu_HU.csv' => 'Hungarian',
            'sv_SE.csv' => 'Swedish',
            'pl_PL.csv' => 'Polish',
        );
        if (strpos($languageFile, '/code')) {
            $split = explode('app/code/', $languageFile);
            $split = explode('/', $split[1]);
            return 'Module: ' . $split[0] . ' - ' . $split[1] . ' - ' . $nameList[substr($languageFile, -9)];
        } else if (strpos($languageFile, '/design')) {
                $split = explode('app/design/', $languageFile);
                $split = explode('/', $split[1]);
                return 'Design: ' . ucfirst($split[0]) . ' - ' . $split[1] . ' - ' . $nameList[substr($languageFile, -9)];
        } else if (strpos($languageFile, '/i18n')) {
            $split = explode('app/i18n/', $languageFile);
            $split = explode('/', $split[1]);
            return 'Main Project Translation: ' . $nameList[substr($languageFile, -9)];
        }
        return;
    }

    function saveToDatabase($langArray, $file)
    {
        $model = $this->translationFactory->create();
        foreach ($langArray as  $line) {
            if(isset($line[2]) && isset($line[3])) {
                $model->addData([
                    'id' => hash('ripemd160', $line[0] . $file . $line[2] . $line[3]),
                    'string' => $line[0],
                    'translation' => $line[1],
                    'location' => $file,
                    'parent_type' => $line[2],
                    'parent_name' => $line[3]
                ]);
            }
            else {
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

    public function getCurrentFile()
    {
        return $this->registry->registry('currentFile');
    }
}