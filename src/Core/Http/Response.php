<?php

namespace App\Core\Http;

class Response
{
    public const OK = 200;
    public const REDIRECT = 302;
    public const NOT_FOUND = 404;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const METHOD_NOT_ALLOWED = 405;
    public const PAGE_REMOVED = 410;
    public const ERROR = 500;
    public const UNAVAILABLE = 503;

    public function __construct()
    {
        http_response_code(static::OK);
    }

    public function setStatusCode($code)
    {
        http_response_code($code);
    }

    public function redirect($to)
    {
        header('Location: ' . route($to));
    }

    public function notFound()
    {
        http_response_code(Response::NOT_FOUND);
        echo '<h1>404 - NOT FOUND</h1>';
        die;
    }

    public function methodNotAllowed()
    {
        http_response_code(Response::METHOD_NOT_ALLOWED);
        echo '<h1>405 - METHOD NOT ALLOWED</h1>';
        die;
    }
}