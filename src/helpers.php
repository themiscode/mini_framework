<?php

use App\Core\Application;

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
 * Returns the databse service.
 *
 * @return \App\Core\Services\DatabaseService
 */
function database() 
{
    return application('database');
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