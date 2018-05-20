<?php

namespace app\component\Parser;

use app\component\Common\LogClass;
use app\component\Parser\ParserRoboForexClass;

/**
 * Класс для парсера отчета
 */
class ParserClass
{
    private $name_file = '';
    private $text = '';
    private $document = '';
    private $valid_report = false;
    private $message = '';

    private $class_type_report;

    const ROBOFOREX = 'RoboForex (CY) Ltd.';

    public function __construct($name_file)
    {
        $this->loadFile($name_file);

        if ($this->isValid()){
            $this->class_type_report= $this->getReportByType();
        }
    }

    /**
     * Задать имя файла для загрузки
     * @param string $name_file
     */
    public function setNameFile($name_file)
    {
        $this->name_file = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/web/files/' . $name_file;
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
     * @param $text
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
     * Проверка принадлежности загруженного файла к одному из отчетов
     * @return bool
     */
    public function checkValidFileReport()
    {
        if ($this->isRoboForexFile()) {
            return $this->valid_report = true;
        }

        $this->message = 'Файл не является отчетом';

        return $this->valid_report = false;;
    }

    /**
     * Вернуть итог - валидный файл или нет
     * @return bool
     */
    public function isValid()
    {
        return $this->valid_report;
    }

    /**
     * Получить сообщение об ошибке
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Получить информацию отчета
     * @return mixed
     */
    public function getFullInfo()
    {
        return $this->class_type_report->getFullInfo();
    }

    /**
     * Загрузить файл
     * @param string $name_file
     * @return bool
     */
    public function loadFile($name_file)
    {
        $this->setNameFile($name_file);

        if (!file_exists($this->getNameFile())) {
            $this->message = 'Не найден файл с отчетом. Возможно он удален';
            $this->valid_report = false;
            return false;
        }

        $this->setText(
            file_get_contents($this->getNameFile())
        );
        $this->document = \phpQuery::newDocumentHTML($this->getText());

        if (!$this->checkValidFileReport()) {
            return false;
        }

        return $this->valid_report;
    }

    /**
     * В зависимости от типа отчета вернуть класс который его обрабатывает
     * @return ParserRoboForexClass|bool
     */
    public function getReportByType()
    {
        if ($this->isRoboForexFile()) {
            return new ParserRoboForexClass($this->document);
        }

        return false;
    }

    /**
     * Проверить является ли файл от робофорекса
     * @return bool
     */
    private function isRoboForexFile()
    {
        return ($this->hasText(self::ROBOFOREX));
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
