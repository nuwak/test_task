<?php

namespace src\Decorator; //src не используется в наймспейсе

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

//Нет описания класса
//Не говорящие название
//Лучше не наследовать декорируемы класс, а передать его в конструктор таким образом мы на его жестко не завязываемая и
//сможет декорировать и другие классы, а здесь импрелементить интерфейс
//Класс нарушает принцип Single Responsibility, кеширует и логирует можно сделать отдельные декораторы для кеширования
// и логирования с общим интерфейсом и оборачивать один в другой при необходимости
class DecoratorManager extends DataProvider
{
    //privet к этим переменным не нужен доступ снаружи
    public $cache;
    //использовать \Psr\Log\LoggerAwareTrait
    //privet к этим переменным не нужен доступ снаружи
    public $logger;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheItemPoolInterface $cache
     */
    //Передать сервис для работы с API и в нем будут устанавливаться хост, пользователь и пароль
    public function __construct($host, $user, $password, CacheItemPoolInterface $cache)
    {
        parent::__construct($host, $user, $password);
        $this->cache = $cache;
    }

    //использовать \Psr\Log\LoggerAwareTrait
    //но лучше передать в конструктор т.к. тут явная зависимость от логера
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    //Название не соответствует родительскому классу
    //Указать возвращаймы тип
    //Название параметра $request, как в родительском классе
    public function getResponse(array $input)
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = parent::get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    //Время жизни лучше передать в конструктор,
                    // так мы сможем использовать этот декоратор для других компонентов
                    (new DateTime())->modify('+1 day')
                );

            return $result;
            //try охватываем неколько компонентов лучше для каждого сделать свой catch
        } catch (Exception $e) {
            //С критической ошибкой завершаем работу и выбрасываем исключение дальше
            //Сделать сообщение об ошибке более понятным, чтобы можно было понять где оно возниклов
            $this->logger->critical('Error');
        }

        return [];
    }

    //phpdoc
    //private
    //return type hint
    //json может быть очень большим и нужен префикс для того чтобы понятно где этот кеш был создан
    //Нарушает PSR, ключ не может содержать {}()/\@:
    //Лучше вынести в трейт чтобы во всем приложение было однотипное формирование ключа
    public function getCacheKey(array $input)
    {
        return json_encode($input);
    }
}