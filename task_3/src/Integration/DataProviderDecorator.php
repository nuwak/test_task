<?php
namespace Integration;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use RuntimeException;
use lib\KeyGeneratorTrait;

/**
 * Декоратор провайдера данных
 */
class DataProviderDecorator implements DataProviderInterface
{
    use KeyGeneratorTrait;

    /**
     * @var CacheItemPoolInterface Кэш
     */
    public $cache;

    /**
     * @var LoggerInterface Логгер
     */
    public $logger;

    /**
     * @var DataProviderInterface Провадер данных
     */
    private $dataProvider;

    /**
     * @var string Время жизни кэша
     */
    private $expireTime;

    /**
     * @param LoggerInterface $logger Логгер
     * @param CacheItemPoolInterface $cache Кэш
     * @param DataProviderInterface $dataProvider Провадер данных
     * @param string $expireTime Время жизни кэша
     */
    public function __construct(
        LoggerInterface $logger,
        CacheItemPoolInterface $cache,
        DataProviderInterface $dataProvider,
        string $expireTime
    ) {
        $this->logger = $logger;
        $this->cache = $cache;
        $this->dataProvider = $dataProvider;
        $this->expireTime = $expireTime;
    }

    /**
     * {@inheritdoc}
     * @throws RequestException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function get(array $request): array
    {
        try {
            $cacheKey = $this->generateKeyFromArray(__METHOD__, $request);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = $this->dataProvider->get($request);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify($this->expireTime)
                );

            return $result;
        } catch (RequestException $e) {
            $this->logger->critical($e->getMessage());
            throw new RequestException($e->getMessage(), $e->getRequest());
        } catch (Exception $e) {
            $this->logger->critical('Ошибка при получение данных' . ':' . $e->getMessage());
            throw new RuntimeException('Ошибка при получение данных' . ':' . $e->getMessage());
        }
    }
}
