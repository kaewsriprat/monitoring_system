<?php

declare(strict_types=1);

class Method
{
    // get method name
    private static function requestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getRequestMethod(): string
    {
        return self::requestMethod();
    }

    public static function isPost(): bool
    {
        return self::requestMethod() === 'POST';
    }

    public static function isGet(): bool
    {
        return self::requestMethod() === 'GET';
    }

    public static function isPut(): bool
    {
        return self::requestMethod() === 'PUT';
    }

    public static function isDelete(): bool
    {
        return self::requestMethod() === 'DELETE';
    }

    public static function restrictMethod(): void
    {
        http_response_code(405);
        exit;
    }
}
