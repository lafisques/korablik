<?php

/**
 * Class Replacer
 * Производит замену строк в контенте, получаемой по передаваемому url
 */
class Replacer
{
    /** @var null|string URL адрес страницы */
    protected $_url = null;
    /** @var null|string Контент */
    protected $_data = null;
    /** @var null|string Рабочая версия данных */
    protected $_opData = null;
    /** @var null|resource curl resource */
    protected $_ch = null;
    /** Лимит итераций при замене */
    const ITERATION_LIMIT = 10;
    /** Таймаут подключения */
    const SETTING_CONNECTION_TIMEOUT = 10;
    /** Таймаут получения контента */
    const SETTING_TIMEOUT = 10;

    /**
     * Простенький билдер
     * @param string $url
     * @param bool $inverse
     * @return Replacer
     */
    public static final function build(string $url, bool $inverse = false)
    {
        if(!$inverse) {
            return new Replacer($url);
        } else {
            return new InverseReplacer($url);
        }
    }

    /**
     * Test constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        /** Инициализируем curl */
        $this->_ch = curl_init();
        curl_setopt_array($this->_ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_CONNECTTIMEOUT => Replacer::SETTING_CONNECTION_TIMEOUT,
            CURLOPT_TIMEOUT => Replacer::SETTING_TIMEOUT,
            CURLOPT_URL => $url,
        ]);
    }

    /**
     * Ручаная установка рабочих данных
     * @param string $data
     * @return null
     */
    public function setData(string $data)
    {
        $this->_data = $data;
    }

    /**
     * Задает url адрес страницы для разбора
     * @param string $url
     * @return null
     */
    public function setUrl(string $url)
    {
        curl_setopt($this->_ch, CURLOPT_URL, $url);
        $this->_url = $url;
    }

    /**
     * Получает содержимое страницы по адресу $_url
     * @return null
     */
    public function fetchURL()
    {
        /** Получение контента по адресу с проверкой ошибок */
        if (!$this->_data = curl_exec($this->_ch)) {
            die('Error: "' . curl_error($this->_ch) . '" - Code: ' . curl_errno($this->_ch));
        }
    }

    /**
     * Собираем массив и делегируем проведение замены методу - обработчику
     * массива
     * @param string $search Строка для замены
     * @param string $replace На что заменяем
     * @return string
     */
    public function replace(string $search, string $replace)
    {
        return $this->replaceByArray([
            ['search' => $search, 'replace' => $replace]
        ]);
    }

    /**
     * Подготовка карты замены по ссылке. Применяется перед проведением замены
     * текстов
     * @param array $replacementMap
     * @return array
     */
    protected function _prepareReplacementMap(array $replacementMap)
    {
        return $replacementMap;
    }

    /**
     * Рекурсивно производит замену вхождений искомых строк, согласно
     * $replacementMap
     * @param array $replacementMap Массив строк для замены
     * @param string $opString Рабочая строка
     * @param int $iterationNum Номер итерации замены строк
     * @return string
     */
    public function replaceByArray(array $replacementMap, string $opString = '', int $iterationNum = 0)
    {
        $replacementMap = $this->_prepareReplacementMap($replacementMap);

        $patterns = [];
        $replacements = [];

        if (empty($opString)) {
            $opString = $this->_data;
        }

        foreach ($replacementMap as $pair) {
            $patterns[] = '~' . $pair['search'] . '~';
            $replacements[] = $pair['replace'];
        }

        $result = preg_replace($patterns, $replacements, $opString, -1, $replacementCnt);
        $this->_stackTrace[] = $result;
        if ($replacementCnt > 0 && $iterationNum < Replacer::ITERATION_LIMIT) {
            $result = $this->replaceByArray($replacementMap, $result, ++$iterationNum);
        }
        $this->_opData = $result;

        return $result;
    }

    /**
     * Выводит результат проведения замены
     * @return null
     */
    public function printResult()
    {
        echo "BEFORE REPLACE:\n" . $this->_data . "\n\n";
        echo "AFTER REPLACE:\n" . $this->_opData . "\n\n";
    }
}