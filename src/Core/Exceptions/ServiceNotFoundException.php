<?php

namespace App\Core\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends \Exception implements NotFoundExceptionInterface {}