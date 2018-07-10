<?php
namespace Integration;

/**
 * Интерфейс провайдера данных
 */
interface DataProviderInterface
{
    /**
     * Получаем данные из API
     *
     * @param array $request запрос
     *
     * @return array
     */
    public function get(array $request): array;
}
