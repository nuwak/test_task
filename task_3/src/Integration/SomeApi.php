<?php
namespace Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

/**
 * Api для работы с каким-то сервисом
 */
class SomeApi
{
    /**
     * @var Client Http client
     */
    private $client;

    /**
     * @var string Имя пользователя
     */
    private $user;

    /**
     * @var string Пароль
     */
    private $password;

    /**
     * SomeApi constructor.
     *
     * @param Client $client
     * @param string $user
     * @param string $password
     */
    public function __construct(Client $client, string $user, string $password)
    {
        //host передается при создание объекта
        $this->client = $client;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Отправляем запрос
     *
     * @param array $request Запрос
     *
     * @return string
     * @throws RequestException
     * @throws GuzzleException
     */
    public function request(array $request): string
    {
        try {
            $response = $this->client->request('POST', '/get', [
                'auth' => [$this->user, $this->password],
                'form_params' => $request
            ])->getBody();
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            throw new RequestException(
                RequestException::REQUEST_FAILED . ' : ' . $e->getMessage(),
                $e->getRequest(),
                $e->getResponse(),
                $e
            );
        } catch (GuzzleException $e) {
            throw new RuntimeException(RequestException::REQUEST_FAILED. ' : ' . $e->getMessage());
        }

        return $response;
    }
}
