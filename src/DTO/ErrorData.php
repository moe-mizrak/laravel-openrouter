<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

final class ErrorData extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        public int $code,
        public string $message,
        public ?array $metadata = null
    ) {
        parent::__construct(...func_get_args());
    }
}
