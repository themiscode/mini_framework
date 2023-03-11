<?php

namespace App\Core\Http;

/**
 * Wrapper class for interacting with the server variables
 * in a more humanized way.
 */
class Request
{
    public const GET = 'GET';
    public const POST = 'POST';

    public function contentType()
    {
        return $_SERVER['CONTENT_TYPE'];
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function query()
    {
        return $_SERVER['QUERY_STRING'];
    }

    public function get($key, $default = null)
    {
        if (isset($this->data()[$key])) {
            return $this->data()[$key];
        }

        return $default;
    }

    public function uri()
    {
        return strtok($_SERVER['REQUEST_URI'], '?');
    }

    public function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function clientIp()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    public function data()
    {
        if ($this->contentType() === 'application/json') {
            return json_decode(file_get_contents('php://input', true), true);
        }

        return $_REQUEST;
    }
}