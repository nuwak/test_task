<?php
namespace Integration;

/**
 * Провайдер данных
 */
class DataProvider implements DataProviderInterface
{
    /**
     * @var SomeApi Какое-то API
     */
    private $someApi;

    /**
     * @param SomeApi $someApi Какое-то API
     */
    public function __construct(SomeApi $someApi)
    {
        $this->someApi = $someApi;
    }

    /**
     * @param array $request Запрос
     *
     * @return array Ответ
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(array $request): array
    {
        // ...
        $response = $this->someApi->request($request);
        // ...
    }
}
