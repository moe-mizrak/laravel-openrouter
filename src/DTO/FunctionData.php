<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * Function tool that is called.
 *
 * Class FunctionData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class FunctionData extends DataTransferObject
{
    /**
     * @inheritDoc
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
    ) {
        parent::__construct(...func_get_args());
    }

    /**
     * @return array
     */
    public function convertToArray(): array
    {
        return array_filter(
            [
                'name'        => $this->name,
                'arguments'   => $this->arguments,
                'description' => $this->description,
                'parameters'  => $this->parameters,
            ],
            fn($value) => $value !== null
        );
    }
}