<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

/**
 * Function tool that is called.
 */
final class FunctionData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        /**
         * The name of the function e.g. getCurrentTemperature.
         */
        public string $name,

        /**
         * Arguments for the function.
         * JSON format arguments.
         */
        public ?string $arguments = null,

        /**
         * A description of the function.
         */
        public ?string $description = null,

        /**
         * Parameters for the function.
         * JSON Schema object.
         */
        public ?array $parameters = null
    ) {
        parent::__construct(...func_get_args());
    }

    public function convertToArray(): array
    {
        return array_filter(
            [
                'name' => $this->name,
                'arguments' => $this->arguments,
                'description' => $this->description,
                'parameters' => $this->parameters,
            ],
            fn($value) => $value !== null
        );
    }
}
