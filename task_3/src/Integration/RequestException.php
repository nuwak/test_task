<?php
namespace Integration;

/**
 * Свое исключение для запроса
 */
class RequestException extends \GuzzleHttp\Exception\RequestException
{
    public const REQUEST_FAILED = 'Request failed.';
    //...
}
