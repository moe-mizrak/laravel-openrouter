<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * Function tool that is called.
 *
 * Class FunctionData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
class FunctionData extends DataTransferObject
{
    /**
     * The name of the function e.g. getCurrentTemperature.
     *
     * @var string
     */
    public string $name;

    /**
     * Arguments for the function.
     * JSON format arguments.
     *
     * @var string|null
     */
    public ?string $arguments;

    /**
     * A description of the function.
     *
     * @var string|null
     */
    public ?string $description;

    /**
     * Parameters for the function.
     * JSON Schema object.
     *
     * @var array|null
     */
    public ?array $parameters;
}