<?php

namespace App\Core\Contracts\Http;

use App\Core\Http\Sanitizer;

abstract class ControllerContract {
    /**
     * Sanitizes request data specified in the dataMap argument.
     *
     * @param array $dataMap
     *
     * @return array
     */
    protected function sanitize($dataMap)
    {
        return (new Sanitizer)->sanitize($dataMap);
    }
}