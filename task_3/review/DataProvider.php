<?php
namespace src\Integration; //src не используется в наймспейсе

//Описание класса
class DataProvider
{
    //phpdoc и описание
    private $host;
    private $user;
    private $password;

    //тип и описание
    /**
     * @param $host
     * @param $user
     * @param $password
     */
    //указать типы передаваемых клиентов
    //а лучше создать отдельный сервис для работы с api и его сюда передавать
    // чтобы не нарушать принцип Single Responsibility
    public function __construct($host, $user, $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    //добавить пример того какой запрос можно отправлять
    //описание к параметрам
    /**
     * @param array $request
     *
     * @return array
     */
    //указать возвращаемый тип
    public function get(array $request)
    {
        // returns a response from external service
    }
}
