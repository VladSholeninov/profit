<?php

namespace app\component\Common;

class FileClass
{
    const TAIL = '';
    private $array_document_extension = array('doc', 'docx', 'xlsx', 'xls', 'rar', 'zip', 'pdf');
    private $array_image_extension = array('png', 'jpg', 'bmp', 'gif', 'tif', 'tiff', 'jpeg', 'ico');
    public $files = array();
    private $array_allowed_extension = array('png', 'jpg', 'bmp', 'gif', 'doc', 'docx', 'pdf', 'txt', 'tif', 'tiff');
    private $max_size_file = 5;
    private $errors_1 = 'Недопустимое расширение файла.';
    private $errors_2 = 'Размер файла превышает максимально допустимый ';
    private $errors = array(
        "1" => "Размер принятого файла превысил максимально допустимый размер.",
        "2" => "Размер загружаемого файла превысил значение.",
        "3" => "Загружаемый файл был получен только частично.",
        "4" => "Не выбран файл для загрузки.",
        "6" => "Отсутствует временная папка.",
        "7" => "Не удалось записать файл на диск.",
        "8" => "Программа остановила загрузку файла."
    );

    function __construct()
    {
        if (isset($_FILES)) {
            $this->files = $_FILES;
        }
        $this->setExtensionAllFiles();
        $this->setAllowedExtension($this->array_allowed_extension);
        $this->setHashNameAllFiles();
        $this->setSizeAllFiles();
        $this->setAllowedSize($this->max_size_file);
    }

    public function getDefaultMaxSize()
    {
        return $this->max_size_file;
    }

    /**
     * Для всех файлов который загружены считать значение размера файла и перевести его в Мегабайты
     */
    private function setSizeAllFiles()
    {
        $array_name_files = array_keys($this->files);
        foreach ($array_name_files as $name_file) {
            $this->files[$name_file]['size'] = $this->convertBaytToMbaytGF((int)$this->files[$name_file]['size']);
        }
    }

    /**
     * Для всех загруженных файлов установить максимальное значение допустимого размера файла, взятого с константы
     * @param int $allowed_size - допустимый размер файла
     */
    public function setAllowedSize($allowed_size)
    {
        $array_name_files = array_keys($this->files);
        foreach ($array_name_files as $name_file) {
            $this->setAllowedSizeFile($allowed_size, $name_file);
        }
    }

    /**
     * Установить максимально допустимый размер для определенного файла
     * @param int $allowed_size - допустимый размер файла
     * @param string $name_file - имя файла
     */
    public function setAllowedSizeFile($allowed_size, $name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        $this->files[$name_file]['allowed_size'] = $allowed_size;
    }

    /**
     * Получить максимально допустимый размер файла, установленного для определенного файла
     * @param string $name_file
     * @return int
     */
    public function getAllowedSizeFile($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        return $this->files[$name_file]['allowed_size'];
    }

    /**
     * Получить по имени размер загруженного файла
     * @param string $name_file
     * @return int
     */
    public function getSizeFile($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        if (isset($this->files[$name_file])) {
            return $this->files[$name_file]['size'];
        }
        return 0;
    }

    /**
     * Считать расширение всех загруженных файлов
     */
    private function setExtensionAllFiles()
    {
        $array_name_files = array_keys($this->files);
        foreach ($array_name_files as $name_file) {
            $this->files[$name_file]['extension'] = strtolower(
                $this->getExtensionFileGF($this->getNameFile($name_file))
            );
        }
    }

    /**
     * Установить разрешенным набор расширений - изображения
     * @return array
     */
    public function setImageTypeExtension()
    {
        return $this->setAllowedExtension($this->array_image_extension);
    }

    /**
     * Установить разрешенным набор расширений - документы
     * @return array
     */
    public function setDocumentTypeExtension()
    {
        return $this->setAllowedExtension($this->array_document_extension);
    }

    /**
     * Получить массив расширений установленных для файлов типа "документы"
     * @return array
     */
    public function getArrayDocumentExtension()
    {
        return $this->array_document_extension;
    }

    /**
     * Получить массив расширений установленных для файлов типа "изображения"
     * @return array
     */
    public function getArrayImagesExtension()
    {
        return $this->array_image_extension;
    }

    /**
     * Установить массив допустимых расширений
     * @param array $array_allowed_extension
     * @return bool
     */
    public function setAllowedExtension($array_allowed_extension)
    {
        $array_name_files = array_keys($this->files);
        foreach ($array_name_files as $name_file) {
            $this->setAllowedExtensionFile($array_allowed_extension, $name_file);
            $this->setMessage('', $name_file);
        }
        return true;
    }

    public function setAllowedExtensionFile($allowed_extension, $name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        if (!is_array($allowed_extension)) {
            $allowed_extension = $this->str2arrayGF($allowed_extension);
        }
        $allowed_extension_lower = $this->strtolowerArrayStringGF($allowed_extension);
        $this->files[$name_file]['allowed_extension'] = $allowed_extension_lower;
    }

    public function getExtensionFile($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }

        if (isset($this->files[$name_file])) {
            return $this->files[$name_file]['extension'];
        }
        return '';
    }

    public function getAllowedExtensionFile($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        return $this->files[$name_file]['allowed_extension'];
    }

    private function setHashNameAllFiles()
    {
        $array_name_files = array_keys($this->files);
        foreach ($array_name_files as $name_file) {
            $new_name_file = self::addTailToNameFileGF(md5(time() . rand(0, 999)) . '.' . $this->getExtensionFile($name_file), self::TAIL);
            $this->setNameFileOnServer(
                $name_file,
                $new_name_file
            );
        }
    }

    private function getFirstName()
    {
        reset($this->files);
        return key($this->files);
    }

    public function getNameFile($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        if (isset($this->files[$name_file])) {
            return $name = $this->files[$name_file]['name'];
        }
        return '';
    }

    function getTmpNameFile($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        if (isset($this->files[$name_file])) {
            return $name = $this->files[$name_file]['tmp_name'];
        }
        return '';
    }

    public function getMimeFile($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        if (isset($this->files[$name_file])) {
            return $mime = $this->files[$name_file]['mime'];
        }
        return '';
    }

    public function getFileInfo($name_file = null)
    {
        if ($name_file == null) {
            return $this->files;
        } else {
            if (array_key_exists($name_file, $this->files)) {
                return $this->files[$name_file];
            }
        }
        return array();
    }

    public function getNameFileOnServer($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        $name = '';
        if (isset($this->files[$name_file])) {
            $name = $this->files[$name_file]['hash_name'];
        }
        return $name;
    }

    public function setNameFileOnServer($name_file, $name_file_on_server)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        if (isset($this->files[$name_file])) {
            $this->files[$name_file]['hash_name'] = $name_file_on_server;
        }
        return $this->files[$name_file]['hash_name'];
    }

    public function isValid()
    {
        $array_name_files = array_keys($this->files);
        $valid = true;
        foreach ($array_name_files as $name_file) {
            $valid = $valid * $this->isValidFile($name_file);
        }
        if ($valid == 1) {
            return true;
        }
        return false;
    }

    public function getErrorMessage($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        $error_number = $this->files[$name_file]['error'];
        if (array_key_exists($error_number, $this->errors)) {
            return $this->errors[$error_number];
        }
        return $this->errors["4"];
    }

    public function setMessage($message, $name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        if (empty($message)) {
            $this->files[$name_file]['message'] = '';
        } else {
            $this->files[$name_file]['message'] .= $message . ' ';
        }
    }

    public function getMessage($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        return $this->files[$name_file]['message'];
    }

    /**
     * Проверяет на валидность файл
     * @param string $name_file
     * @return boolean
     */
    public function isValidFile($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }

        if ($this->isValidUploadFile($name_file)) {
            $valid = $this->isValidSizeFile($name_file) * $this->isValidExtensionFile($name_file);
            if ($valid != 1) {
                return false;
            }
            if ($this->isImageExtension($name_file)) {
                if (!$this->isImageFile($name_file)) {
                    $this->setMessage('Файл не является изображением.', $name_file);
                    return false;
                }
                return true;
            } elseif ($this->isDocumentExtension($name_file)) {
                if (!$this->isDocumentFile($name_file)) {
                    $this->setMessage('Файл не является документом.', $name_file);
                    return false;
                }
                return true;
            }
        }
        return true;
    }

    /**
     * Определяет принадлежит ли расширение файла к изображениям
     * @param string $name_file
     * @return boolean
     */
    public function isImageExtension($name_file)
    {
        $extension_file = mb_strtolower($this->getExtensionFile($name_file));
        return in_array($extension_file, $this->array_image_extension);
    }

    /**
     * Определяет принадлежит ли расширение файла к документам
     * @param string $name_file
     * @return boolean
     */
    public function isDocumentExtension($name_file)
    {
        $extension_file = mb_strtolower($this->getExtensionFile($name_file));
        return in_array($extension_file, $this->array_document_extension);
    }

    /**
     * Проверка на валидность загружаемого файла
     * @param string $name_file
     * @return boolean
     */
    public function isValidUploadFile($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        if ($this->files[$name_file]['error'] > 0) {
            $this->setMessage($this->getErrorMessage($name_file), $name_file);
            return false;
        }
        return true;
    }

    /**
     * Проверяется расширения файла на допустимый список
     * @param string $name_file
     * @return boolean
     */
    public function isValidExtensionFile($name_file)
    {
        $array_allow_extension = $this->getAllowedExtensionFile($name_file);
        $extension_file = mb_strtolower($this->getExtensionFile($name_file));
        if (in_array($extension_file, $array_allow_extension) or empty($array_allow_extension)) {
            return true;
        }
        $message = $this->errors_1 . ' Допустимые расширения: ' . implode(', ', $array_allow_extension);
        $this->setMessage($message, $name_file);
        return false;
    }

    /**
     * Проверка допустимого размера загружаемого Файла
     * @param string $name_file
     * @return boolean
     */
    public function isValidSizeFile($name_file)
    {
        if ($this->getSizeFile($name_file) <= $this->getAllowedSizeFile($name_file)) {
            return true;
        }
        $this->setMessage($this->errors_2 . $this->getAllowedSizeFile($name_file) . ' Мб.', $name_file);
        return false;
    }

    /**
     * Проверяет является ли файл изображением
     * @param string $name_file
     * @return boolean
     */
    public function isImageFile($name_file)
    {
        $name_file_path = $this->getTmpNameFile($name_file);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if (file_exists($name_file_path)) {
            $info = finfo_file($finfo, $name_file_path);
            finfo_close($finfo);
            if (strpos($info, 'php') !== false || strpos($info, 'plain') !== false) {
                return false;
            }
            if (strpos($info, 'image/') !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Проверяет является ли файл документом
     * @param string $name_file
     * @return boolean
     */
    public function isDocumentFile($name_file)
    {
        $name_file_path = $this->getTmpNameFile($name_file);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if (file_exists($name_file_path)) {
            $info = finfo_file($finfo, $name_file_path);
            finfo_close($finfo);
            if (strpos($info, 'php') !== false || strpos($info, 'plain') !== false) {
                return false;
            }
            if (strpos($info, 'application/') !== false) {
                return true;
            }
        }
        return false;
    }

    public function setDestination($path)
    {
        $array_name_files = array_keys($this->files);
        foreach ($array_name_files as $name_file) {
            $this->setDestinationFile($path, $name_file);
        }
    }

    public function setDestinationFile($path, $name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        $last_char = substr($path, -1);
        if ($last_char != '/' && $last_char != "\\") {
            $path .= '/';
        }

        $name_file_new = mb_strtolower(
            $this->transliterateForNameFileGF($this->getNameFile($name_file))
        );

        $name_file_new = self::addTailToNameFileGF($name_file_new, self::TAIL);
        $name_file_on_server = $path . $name_file_new;

        if (file_exists($name_file_on_server)) {
            $name_file_on_server = $path . $this->getNameFileOnServer($name_file);
        } else {
            $this->setNameFileOnServer($name_file, $name_file_new);
        }
        $this->files[$name_file]['path_receive'] = $name_file_on_server;
        $this->files[$name_file]['path'] = $path;
    }

    public function getDestinationFile($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        return $this->files[$name_file]['path_receive'];
    }

    public function receive()
    {
        $result = true;
        $array_name_files = array_keys($this->files);
        foreach ($array_name_files as $name_file) {
            $result *= $this->receiveFile($name_file);
        }

        if ($result == 1) {
            return true;
        }
        return false;
    }

    public function receiveFile($name_file = null)
    {
        if ($name_file == null) {
            $name_file = $this->getFirstName();
        }
        if (!is_dir($this->files[$name_file]['path'])) {
            $this->setMessage(
                'Ошибка направления файла в несуществующую директорию, обратитесь к администратору',
                $name_file
            );
            return false;
        }
        if (!move_uploaded_file($this->getTmpNameFile($name_file), $this->getDestinationFile($name_file))) {
            $this->setMessage('Произошла ошибка загрузки, обратитесь к администратору', $name_file);
            return false;
        }
        $this->setMessage('Файл ' . $this->getNameFile($name_file) . ' успешно загружен на сервер.', $name_file);
        return true;
    }


    /**
     * Функция из GF
     * @param $b
     * @return float
     */
    private function convertBaytToMbaytGF($b)
    {
        return $b / 1000 / 1000;
    }

    /**
     * Получить расширение файла
     * Функция из GF
     * @param string $name_file
     * @return string
     */
    static public function getExtensionFileGF($name_file)
    {
        if (!empty($name_file)) {
            $file_info = pathinfo($name_file);
            return $file_info['extension'];
        }
        return "";
    }

    /**
     * Выдает название файла без расширения
     * @param string $full_name_file
     * @return string
     */
    static public function getBodyNameFileGF($full_name_file)
    {
        if (!empty($full_name_file)) {
            $file_info = pathinfo($full_name_file);
            return $file_info['filename'];
        }
        return "";
    }

    /**
     * Добавить к телу названия файла дополнительный текст (tail)
     * @param $full_name_file
     * @param $tail
     * @return string
     */
    static public function addTailToNameFileGF($full_name_file, $tail)
    {
        if (!empty($tail)) {
            $name_file = self::getBodyNameFileGF($full_name_file);
            $extension_file = self::getExtensionFileGF($full_name_file);
            return $name_file . $tail . '.' . $extension_file;
        }
        return $full_name_file;
    }

    /**
     * Сформировать массив значений из строки разделенный некоторыми символами
     * Функция из GF
     * @param string $string
     * @return array
     */
    private function str2arrayGF($string)
    {
        $new_string = str_replace(array(';', '/', '|'), ',', $string);
        $array_explode = explode(',', $new_string);
        $array_explode_triming = $this->trimArrayStringGF($array_explode);
        $array_explode_noempty = array_diff($array_explode_triming, array(''));
        return $array_explode_noempty;
    }

    /**
     * Преобразовать весь массив строк в нижний резистр
     * Функция из GF
     * @param array $array_string - массив с строками
     * @return array - переработанный массив
     */
    private function strtolowerArrayStringGF($array_string)
    {
        $transform_array = array();
        if (is_array($array_string)) {
            if (count($array_string) > 0) {
                foreach ($array_string as $key => $value) {
                    $transform_array[$key] = strtolower($value);
                }
            }
        } else {
            $transform_array [] = strtolower($array_string, "UTf-8");
        }
        return $transform_array;
    }

    /**
     * функция превода текста с кириллицы в траскрипт для названия файлов
     * Функция пришла из GF
     * @param $input_text
     * @return string
     */
    private function transliterateForNameFileGF($input_text)
    {
        $gost = array(
            "Є" => "YE",
            "І" => "I",
            "Ѓ" => "G",
            "і" => "i",
            "№" => "-",
            "є" => "ye",
            "ѓ" => "g",
            "А" => "A",
            "Б" => "B",
            "В" => "V",
            "Г" => "G",
            "Д" => "D",
            "Е" => "E",
            "Ё" => "YO",
            "Ж" => "ZH",
            "З" => "Z",
            "И" => "I",
            "Й" => "J",
            "К" => "K",
            "Л" => "L",
            "М" => "M",
            "Н" => "N",
            "О" => "O",
            "П" => "P",
            "Р" => "R",
            "С" => "S",
            "Т" => "T",
            "У" => "U",
            "Ф" => "F",
            "Х" => "X",
            "Ц" => "C",
            "Ч" => "CH",
            "Ш" => "SH",
            "Щ" => "SHH",
            "Ъ" => "'",
            "Ы" => "Y",
            "Ь" => "",
            "Э" => "E",
            "Ю" => "YU",
            "Я" => "YA",
            "а" => "a",
            "б" => "b",
            "в" => "v",
            "г" => "g",
            "д" => "d",
            "е" => "e",
            "ё" => "yo",
            "ж" => "zh",
            "з" => "z",
            "и" => "i",
            "й" => "j",
            "к" => "k",
            "л" => "l",
            "м" => "m",
            "н" => "n",
            "о" => "o",
            "п" => "p",
            "р" => "r",
            "с" => "s",
            "т" => "t",
            "у" => "u",
            "ф" => "f",
            "х" => "x",
            "ц" => "c",
            "ч" => "ch",
            "ш" => "sh",
            "щ" => "shh",
            "ъ" => "",
            "ы" => "y",
            "ь" => "",
            "э" => "e",
            "ю" => "yu",
            "я" => "ya",
            " " => "-",
            "—" => "-",
            "," => "-",
            "!" => "-",
            "@" => "-",
            "#" => "-",
            "$" => "S",
            "%" => "",
            "^" => "-",
            "&" => "-",
            "*" => "-",
            "(" => "",
            ")" => "",
            "+" => "",
            "=" => "",
            ";" => "",
            ":" => "-",
            "~" => "",
            "`" => "",
            "?" => "",
            "/" => "",
            "[" => "",
            "]" => "",
            "{" => "",
            "}" => "",
            "|" => ""
        );

        return strtr($input_text, $gost);
    }

    /**
     * Удаление концевых пробелов в массиве строк
     * Функция пришла с GF
     * @param $array_string
     * @return array
     */
    private function trimArrayStringGF($array_string)
    {
        $transform_array = array();
        if (is_array($array_string)) {
            if (count($array_string) > 0) {
                foreach ($array_string as $key => $value) {
                    $transform_array[$key] = trim($value);
                }
            }
        } else {
            $transform_array [] = trim($array_string);
        }
        return $transform_array;
    }

}

//
//        $obj_file_class = new FileClass();
//        $obj_file_class->setAllowedExtension('gif, png, jpg');
//        $obj_file_class->setAllowedSize(4);
//        $obj_file_class->setDestination('/public/files/upload/');
//  
//        if ($obj_file_class->isValid()) {
//            $obj_file_class->receive();          
//        }
//        $obj_file_class->getNameFile();
//        $obj_file_class->getNameFileOnServer();