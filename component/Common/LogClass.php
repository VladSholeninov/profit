<?php

namespace app\component\Common;

/**
 * Класс для логирования
 */
class LogClass
{

    private static function getStrFromArray($type_file_log, $count_item_get, $key_html, $array, $ss = '', $tab = '')
    {
        $count = count($array);
        $h = self::getHtmlArray($type_file_log);
        $count_info = self::getCountInfo($type_file_log, $count_item_get);
        $ss .= " {$tab}{$h['t']} {$count_info} {$key_html} {$h['i']} array (size={$count}) {$h['i_']} <span style='color:red;'>{</span> {$h['br']}";

        $count_item = 0;
        foreach ($array as $key => $value) {
            $type_var = gettype($value);
            $lenght_string = '';
            $count_info = self::getCountInfo($type_file_log, $count_item);
            if (is_array($value)) {
                $ss = self::getStrFromArray($type_file_log, $count_item, $h['k'] . $key . $h['k_'] . " => ", $value, $ss, $tab . $h['t']);
            } elseif ($type_var == 'boolean') {
                $text_info = self::getTextBoolInfo($value);
                $ss .= " {$tab}{$h['t']} {$h['t']} $count_info {$h['k']} $key {$h['k_']} => {$h['b']}$text_info{$h['b_']} {$h['i']}($type_var : $lenght_string){$h['i_']} {$h['br']}";
            } elseif ($type_var == 'object') {
                $array_metod = get_class_methods($value);
                asort($array_metod);
                $name_class = get_class($value);
                $array_vars = get_class_vars($name_class);
                $parent_class = get_parent_class($value);
                $text_of_parent = ($parent_class != false) ? " унаследован от {$h['b']}$parent_class{$h['b_']}" : ' нет родителей';
                $text_info = "(object of class {$h['b']} $name_class {$h['b_']} {$text_of_parent}) {$h['br']}" . self::getStrFromArray($type_file_log, 'Список методов объекта ', $key . ' => ', $array_metod, $ss);
                $array_vars = self::convertStdClassToArray($value);
                if (count($array_vars) > 0) {
                    $text_info .= "{$h['br']}" . self::getStrFromArray($type_file_log, 'Список свойств объекта ' . $h['br'] , $key . ' => ', $array_vars, $ss);
                }
                $ss .= " {$tab}{$h['t']} {$h['t']} $count_info {$h['k']} $key {$h['k_']} => {$h['b']}$text_info{$h['b_']} {$h['i']}($type_var : $lenght_string){$h['i_']} {$h['br']}";
            } elseif ($type_var == 'string') {
                $text_info = $value;
                $lenght_string = strlen($value);
                $ss .= " {$tab}{$h['t']} {$h['t']} $count_info {$h['k']} $key {$h['k_']} => {$h['b']}$text_info{$h['b_']} {$h['i']}($type_var : $lenght_string){$h['i_']} {$h['br']}";
            } elseif ($type_var == 'integer' || $type_var == 'double') {
                $string_value = '' . $value;
                $lenght_string = strlen($string_value);
                $text_info = $value;
                $ss .= " {$tab}{$h['t']} {$h['t']} $count_info {$h['k']} $key {$h['k_']} => {$h['b']}$text_info{$h['b_']} {$h['i']}($type_var : $lenght_string){$h['i_']} {$h['br']}";
            } elseif ($value == null) {
                $text_info = 'null';
                $ss .= " {$tab}{$h['t']} {$h['t']} $count_info {$h['k']} $key {$h['k_']} => {$h['b']}$text_info{$h['b_']} {$h['i']}($type_var : $lenght_string){$h['i_']} {$h['br']}";
            } else {
                $text_info = $value;
                $ss .= " {$tab}{$h['t']} {$h['t']} $count_info {$h['k']} $key {$h['k_']} => {$h['b']}$text_info{$h['b_']} {$h['i']}($type_var : $lenght_string){$h['i_']} {$h['br']}";
            }

            $count_item++;
        }
        $ss .= " {$tab}{$h['t']} <span style='color:red;'>}</span>  {$h['br']}";
        return $ss;
    }

    /**
     * Сохраняет переданный текст
     * @param string $value
     * @param string $title
     * @param bool $cleare
     * @return bool
     */
    public static function saveLog($value, $title = '', $cleare = false)
    {
        $type_file_log = 'html';
        $h = self::getHtmlArray($type_file_log);
        $text_info = '';
        $lenght_string = '';
        $type_var = gettype($value);
        if (is_array($value)) {
            $text_info = self::getStrFromArray($type_file_log, 0, '', $value, '', $h['t']);
        } elseif ($type_var == 'boolean') {
            $text_info = self::getTextBoolInfo($value);
        } elseif ($type_var == 'object') {
            $array_metod = get_class_methods($value);
            asort($array_metod);
            $name_class = get_class($value);
            $array_vars = get_class_vars($name_class);
            $parent_class = get_parent_class($value);
            $text_of_parent = ($parent_class != false) ? " унаследован от {$h['b']}$parent_class{$h['b_']}" : ' нет родителей';
            $text_info = "{$h['i']}(object of class {$h['b']} $name_class {$h['b_']} {$text_of_parent}){$h['i_']}  {$h['br']}" . self::getStrFromArray($type_file_log, "Список методов объекта ", '', $array_metod, $h['t']);
            $array_vars = self::convertStdClassToArray($value);
            if (count($array_vars) > 0) {
                $text_info .= self::getStrFromArray($type_file_log, 'Список свойств объекта ', '', $array_vars, $h['t']);
            }
        } elseif ($type_var == 'string') {
            $lenght_string = ':' . strlen($value);
            $text_info = $h['b'] . $value . $h['b_'];
        } elseif ($type_var == 'integer' || $type_var == 'double') {
            $string_value = '' . $value;
            $lenght_string = strlen($string_value);
            $text_info = $h['b'] . $value . $h['b_'];
        } elseif ($value == null) {
            $text_info = $h['b'] . 'null' . $h['b_'];
        } else {
            $text_info = $h['b'] . $value . $h['b_'];
        }

        $fp = self::openFile($type_file_log, $cleare);
        fwrite($fp, self::getTitleInfo($title, $type_file_log) . self::getDateInfo($type_file_log) . '    ' . $text_info . $h['i'] . ' (' . gettype($value) . $lenght_string . ") {$h['i_']}{$h['br']} {$h['br']}");
        fclose($fp);
        return true;
    }

    static private function getHtmlArray($type_file_log)
    {
        $h['br'] = ($type_file_log == 'html') ? '<br>' : "\n";
        $h['i'] = ($type_file_log == 'html') ? '<i><span style="color:green;">' : '';
        $h['i_'] = ($type_file_log == 'html') ? '</span></i>' : '';
        $h['b'] = ($type_file_log == 'html') ? '<b>' : '';
        $h['b_'] = ($type_file_log == 'html') ? '</b>' : '';
        $h['k'] = ($type_file_log == 'html') ? '<span style="color:blue;">' : '';
        $h['k_'] = ($type_file_log == 'html') ? '</span>' : '';
        $h['t'] = ($type_file_log == 'html') ? '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>' : "    ";
        return $h;
    }

    /**
     * Почистить файл лога
     * @return boolean
     */
    static public function cleare()
    {
        $fp = self::openFile('html', true);
        fwrite($fp, '<meta charset="utf-8" />');
        fclose($fp);
        return true;
    }

    static private function openFile($type_file_log, $cleare)
    {
        $path = 'log' . self::getExtentionFile($type_file_log);
        if (!file_exists($path)) {
            $fp = fopen($path, 'wt+');
        } else {
            if ($cleare) {
                unlink($path);
            }
            $fp = fopen($path, 'at');
        }
        return $fp;
    }

    static private function getExtentionFile($type_file_log)
    {
        return ($type_file_log == 'html') ? '.html' : '.txt';
    }

    static private function getTextBoolInfo($value)
    {
        if ($value === true) {
            return 'true';
        }
        return 'false';
    }

    static private function getDateInfo($type_file_log)
    {
        if ($type_file_log == 'html') {
            $date_info = "<span style='font-size:10px;color:gray;'><i>" . date('d-m-y G:i:s') . "</i></span> <br>";
        } else {
            $date_info = date('d-m-y G:i:s');
        }
        return $date_info;
    }

    static private function getCountInfo($type_file_log, $count_item_get)
    {
        if ($type_file_log == 'html') {
            $count_info = "<span style='font-size:12px;color:gray;'>$count_item_get</span>:";
        } else {
            $count_info = "$count_item_get:";
        }
        return $count_info;
    }

    static private function getTitleInfo($title, $type_file_log)
    {
        if (empty($title)) {
            return '';
        }
        if ($type_file_log == 'html') {
            $title_info = "<span style='font-size:15px;color:#9C27B0;'><b>" . $title . "</b></span> <br>";
        } else {
            $title_info = $title;
        }
        return $title_info;
    }


    /**
     * Преобразовать поля объекта  в массив
     * @param $class
     * @return array
     */
    public static function convertStdClassToArray($class)
    {
        $new_array = array();
        if ($class) {
            foreach ($class as $name_field => $value_field) {
                $new_array[$name_field] = $value_field;
            }
        }
        return $new_array;
    }

}
