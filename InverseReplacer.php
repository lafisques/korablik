<?php

/**
 * Class InverseReplacer
 */
class InverseReplacer extends Replacer
{
    /**
     * Переопределенный метод подготовки карты замены строк. Меняет местами
     * искомое и заменяемое значение для обратной конвертации строки.
     * todo: Как вариант - передавать массив для замены по ссылке
     * @param array $replacementMap
     * @return array
     */
    protected function _prepareReplacementMap(array $replacementMap)
    {
        foreach ($replacementMap as &$pair) {
            $pair = [
                'search' => $pair['replace'],
                'replace' => $pair['search'],
            ];
        }
        return $replacementMap;
    }
}