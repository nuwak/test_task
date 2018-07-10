<?php

namespace lib;

use InvalidArgumentException;

/**
 * Трейт генерации ключей.
 */
trait KeyGeneratorTrait
{
    /**
     * Сгенерировать ключ.
     *
     * Генерирует ключ на основе имени метода и массива.
     *
     * @param string $method Метод
     * @param array $array Массив
     * @param string $delimiter Разделитель
     *
     * @return string Ключ
     * @throws InvalidArgumentException
     */
    protected function generateKeyFromArray(string $method, array $array, string $delimiter = ':'): string
    {
        if (empty($method)) {
            throw new InvalidArgumentException('Был передан пустой префикс', 1);
        }

        if (empty($array)) {
            return $method;
        }

        return $method . $delimiter . md5(serialize($array));
    }
}
