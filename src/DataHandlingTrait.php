<?php

namespace MoeMizrak\LaravelOpenrouter;

use Spatie\DataTransferObject\DataTransferObject;

/**
 *  The DataHandlingTrait provides utility methods for handling data.
 *
 * Trait DataHandlingTrait
 * @package MoeMizrak\LaravelOpenrouter
 */
trait DataHandlingTrait
{
    /**
     * Recursively filter null values from an array or DTO.
     *
     * @param array|DataTransferObject $data
     * @return array
     */
    protected function filterNullValuesRecursive(array|DataTransferObject $data): array
    {
        // Convert DTO to array in case $data is a DTO object
        if ($data instanceof DataTransferObject) {
            $data = $data->toArray();
        }

        // Recursively filter null values from the array.
        return array_map(function ($value) {
            if (is_array($value)) {
                return $this->filterNullValuesRecursive($value);
            } elseif ($value instanceof DataTransferObject) {
                return $this->filterNullValuesRecursive($value->toArray());
            } else {
                return $value;
            }
        }, array_filter($data, function ($value) {
            return $value !== null;
        }));
    }
}