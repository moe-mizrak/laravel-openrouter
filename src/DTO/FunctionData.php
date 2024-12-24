<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\LaravelData\Data as DataTransferObject;

/**
 * Function tool that is called.
 *
 * Class FunctionData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class FunctionData extends DataTransferObject
{
    /**
     * @param string $name
     * @param string|null $arguments
     * @param string|null $description
     * @param array|null $parameters
     */
    public function __construct(
        /**
         * The name of the function e.g. getCurrentTemperature.
         *
         * @var string
         */
        public string $name,

        /**
         * Arguments for the function.
         * JSON format arguments.
         *
         * @var string|null
         */
        public ?string $arguments = null,

        /**
         * A description of the function.
         *
         * @var string|null
         */
        public ?string $description = null,

        /**
         * Parameters for the function.
         * JSON Schema object.
         *
         * @var array|null
         */
        public ?array $parameters = null
    ) {}
}