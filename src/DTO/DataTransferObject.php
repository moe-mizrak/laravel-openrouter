<?php

namespace MoeMizrak\LaravelOpenrouter\DTO;

use ReflectionClass;
use Spatie\LaravelData\Data;

/**
 * DataTransferObject is the base class for all DTOs.
 *
 * Class DataTransferObject
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
abstract class DataTransferObject extends Data
{
    /**
     * DataTransferObject constructor.
     *
     * @param mixed ...$args
     *
     * @throws \ReflectionException
     */
    public function __construct(...$args)
    {
        $this->handlePropertyValidations();
    }

    /**
     * Handle property validations.
     *
     * @return void
     * @throws \ReflectionException
     */
    private function handlePropertyValidations(): void
    {
        $reflectionClass = new ReflectionClass($this);

        // Filter out properties that have a null value
        $nonNullProperties = array_filter(
            $reflectionClass->getProperties(),
            fn($property) => $property->getValue($this) !== null
        );

        // Iterate over the non-null properties
        foreach ($nonNullProperties as $property) {
            foreach ($property->getAttributes() as $attribute) {
                // Get the instance of the attribute
                $instance = $attribute->newInstance();
                // Get the reflection class of the attribute instance
                $reflectionClassAttribute = new ReflectionClass($instance);

                // The method name to call on the attribute instance
                $validateMethodName = 'handle';

                if ($reflectionClassAttribute->hasMethod($validateMethodName)) {
                    // Get the handle method of the attribute instance
                    $validateMethod = $reflectionClassAttribute->getMethod($validateMethodName);

                    // Call the handle method on the attribute instance
                    $validateMethod->invoke($instance, $property->getValue($this));
                }
            }
        }
    }
}