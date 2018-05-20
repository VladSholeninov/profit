<?php

namespace app\component\Parser;

use app\component\Common\LogClass;

/**
 * Класс для парсера отчета
 */
class ParserRoboForexClass 
{
    
    private $document;
    private $name = 'noname';
    private $currency = '';
    private $date = '';
    public  $data = [];

    public function __construct($document)
    {
        $this->document = $document;
        $this->getCommonInfo();
        $this->getDataFromDocument();
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = trim($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Сеттер даты отчета
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Геттер Даты отчета
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Получить информацию для построения графика
     * @return array
     */
    public function getDataChart()
    {
        $array_chart =[];

        foreach ($this->data as $date => $profit){
            $array_chart[] = [strtotime(str_replace('.', '-', $date))*1000, $profit];
        }

        return $array_chart;
    }

    /**
     * Получить полную информацию об отчете
     * @return array
     */
    public function getFullInfo()
    {
        return [
            'date' => $this->getDate(),
            'name' => $this->getName(),
            'currency' => $this->getCurrency(),
            'data_chart' => $this->getDataChart()
        ];
    }

    /**
     * Получить данные для анализа
     */
    private function getDataFromDocument()
    {
        $array_tr =  $this->document->find('table tr');

        foreach ($array_tr as $tr){
            $tr = pq($tr);
            if ($tr->find('td')->length() == 14){
                $this->addData(
                    $tr->find('td:eq(1)')->text(),
                    (float) $tr->find('td:eq(13)')->text()
                );
            }
        }
    }

    /**
     * Добавтиь в массив запись
     * @param $date
     * @param $profit
     */
    private function addData($date, $profit)
    {
        if (!empty($profit)){
            if (array_key_exists($date, $this->data)){
                $this->data[$date] += $profit;
            } else {
                $this->data[$date] = $profit;
            }
        }
    }

    /**
     * Получить общую информацию о документе
     */
    private  function getCommonInfo()
    {
        $tr = $this->document->find('table tr:first');

        $this->setName($this->explodeText($tr->find('td:eq(1)')->text(), 'noname'));
        $this->setCurrency($this->explodeText($tr->find('td:eq(2)')->text(), ''));
        $this->setDate($tr->find('td:eq(4)')->text());
    }

    /**
     * Разложить на составляющие
     * @param $text
     * @param string $default
     * @return string
     */
    private function explodeText($text, $default = '')
    {
        if (!empty($text)) {
            $list = explode(':', $text);
            if (!empty($list) and array_key_exists(1, $list)){
                return trim($list[1]);
            }
        }

        return $default;
    }

}
