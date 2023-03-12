<?php

use App\Core\Application;
use App\Core\Http\Response;

/**
 * If serviceName parameter is provided then this returns
 * an instance of the service if it exists otherwise returns 
 * the application instance.
 *
 * @param string $serviceName
 *
 * @return App\Core\Application|Object the application or service
 */
function application(string $serviceName = '')
{
    if ($serviceName) {
        return Application::getInstance()
            ->getApplicationContainer()
            ->get($serviceName);
    }

    return Application::getInstance();
}

/**
 * Returns the config service.
 *
 * @return \App\Core\Services\ConfigService
 */
function config() 
{
    return application('config');
}

/**
 * Returns the database service.
 *
 * @return \App\Core\Services\DatabaseService
 */
function database() 
{
    return application('database');
}

/**
 * Returns the request instance.
 *
 * @return \App\Core\Http\Request
 */
function request()
{
    return application('request');
}

/**
 * Returns the base path of the app.
 *
 * @return string
 */
function root_path() 
{
    return $_SERVER['DOCUMENT_ROOT'] . '/..';
}

/**
 * Returns a view with the data passed to it.
 * The view location uses the dot notation to separate
 * folders.
 *
 * @param string $view
 * @param array $data
 *
 * @return void
 */
function view($view, $data = [])
{
    extract($data);

    $view = str_replace('.', '/', $view);

    return require "views/$view.view.php";
}

/**
 * Returns the given route url.
 *
 * @param string $route
 *
 * @return string
 */
function route($route)
{
    return application('router')
        ->getRoute($route);
}

/**
 * Returns a new Response instance.
 *
 * @return \App\Core\Http\Response
 */
function response() {
    return new Response;
}

/**
 * Recursively trim strings in an array.
 *
 * @param array $items
 *
 * @return array
 */
function array_trim(array $items): array
{
    return array_map(function ($item) {
        if (is_string($item)) {
            return trim($item);
        } elseif (is_array($item)) {
            return array_trim($item);
        } else
            return $item;
    }, $items);
}

/**
 * Dumps the data passed and exits.
 * Used for debuging.
 *
 * @return string
 */
function dd($data)
{
    var_dump($data);

    die;
}