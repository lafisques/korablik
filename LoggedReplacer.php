<?php

/**
 * Class LoggedReplacer
 */
class LoggedReplacer extends Replacer
{
    /** @var array Отладочная информация */
    protected $_stackTrace = [];

    /**
     * Выводит очередь преобразованных строк
     * @return null
     */
    public function printStackTrace()
    {
        echo "STACK TRACE:\n";
        foreach ($this->_stackTrace as $key => $item) {
            echo $key . ":\t" . $item . "\n";
        }
    }

    /**
     * Очищает очереди преобразованных строк
     * @return null
     */
    public function clearStackTrace()
    {
        $this->_stackTrace = [];
    }
}