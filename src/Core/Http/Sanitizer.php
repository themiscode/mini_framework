<?php

namespace App\Core\Http;

class Sanitizer
{
    protected const FILTERS = [
        'string' => FILTER_FLAG_NONE,
        'string[]' => [
            'filter' => FILTER_FLAG_NONE,
            'flags' => FILTER_REQUIRE_ARRAY
        ],
        'email' => FILTER_SANITIZE_EMAIL,
        'int' => [
            'filter' => FILTER_SANITIZE_NUMBER_INT,
            'flags' => FILTER_REQUIRE_SCALAR
        ],
        'int[]' => [
            'filter' => FILTER_SANITIZE_NUMBER_INT,
            'flags' => FILTER_REQUIRE_ARRAY
        ],
        'float' => [
            'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
            'flags' => FILTER_FLAG_ALLOW_FRACTION
        ],
        'float[]' => [
            'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
            'flags' => FILTER_REQUIRE_ARRAY
        ],
        'url' => FILTER_SANITIZE_URL,
    ];
    
    /**
    * Sanitize the inputs based on the rules and optionally trim the string
    * @param array $inputTypes
    * @param bool $trim
    *
    * @return array
    */
    public static function sanitize(
        array $inputTypes,
        bool $trim = true
    ): array
    {
        $sanitizedData  = request()->data();
        $requestData = [];
        $filterData = [];

        if (!empty($inputTypes)) {
            $requestDataKeys = array_keys($inputTypes);

            foreach ($requestDataKeys as $key) {
                if (in_array($inputTypes[$key], ['string', 'string[]'])) {
                    if (is_array(request()->get($key))) {
                        $sanitizedArray = [];
                        foreach (request()->get($key) as $arrayKey => $value) {
                            $sanitizedArray[$arrayKey] = htmlspecialchars($value);
                        }

                        $sanitizedData[$key] = $sanitizedArray;
                    }
                    else {
                        $sanitizedData[$key] = htmlspecialchars(request()->get($key));
                    }
                }
                else {
                    $requestData[$key] = request()->get($key);
                    $filterData[$key] = static::FILTERS[$inputTypes[$key]];
                }
            }

            $sanitizedData = array_merge(
                filter_var_array($requestData, $filterData, false),
                $sanitizedData
            );
        }
    
        return $trim ? array_trim($sanitizedData) : $sanitizedData;
    }
}