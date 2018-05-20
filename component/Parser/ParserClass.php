<?php

namespace app\component\Parser;

use app\component\Parser\ParserRoboForexClass;

/**
 * Класс для парсера отчета
 */
class ParserClass
{
    private $name_file = '';
    private $text = '';
    private $document = '';
    
    const ROBOFOREX = 'RoboForex (CY) Ltd.';
    
    public function __construct($name_file)
    {
        $this->setNameFile($name_file);
    }

    /**
     * Задать имя файла для загрузки
     * @param string $name_file
     */
    public function setNameFile($name_file)
    {
        $this->name_file = filter_input(INPUT_SERVER , 'DOCUMENT_ROOT') . '/web/files/' . $name_file;
    }

    /**
     * Получить имя файла для загрукзи
     * @return string
     */
    public function getNameFile()
    {
        return $this->name_file;
    }

    /**
     * сеттер загруженного текста
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * геттер загруженного текста
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * Загрузить файл
     * @param string $name_file
     */
    public function loadFile($name_file = null)
    {
        if (!empty($name_file)) {
            $this->setNameFile($name_file);
        }
        
        if (file_exists($this->getNameFile())){
            $this->setText(
              file_get_contents($this->getNameFile())
              );
            
            $this->document = \phpQuery::newDocumentHTML($this->getText());
            
            return true;
        }
        
        return false;
    }
    
    public function getReportByType()
    {
        if ($this->hasText(self::ROBOFOREX)){
            return new ParserRoboForexClass($this->document);
        }
        
        return false;
    }

    /**
     * Определяем наличие текста в документе
     * @param string $text
     * @return boolean
     */
    public function hasText($text)
    {
        $find_text = $this->document->find("body:contains('{$text}')")->text();
        
        return (mb_strlen($find_text) > 0);
    }

}
