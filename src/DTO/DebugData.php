<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelOpenrouter\DTO;

use MoeMizrak\LaravelOpenrouter\Rules\AllowedValues;

/**
 * DTO for the debug options.
 *
 * Docs: https://openrouter.ai/docs/api/api-reference/chat/send-chat-completion-request#request.body.debug
 *
 * Class DebugData
 * @package MoeMizrak\LaravelOpenrouter\DTO
 */
final class DebugData extends DataTransferObject {
  /**
   * The allowed debug type value.
   */
  public const ALLOWED_TYPE = [false, true];

  /**
   * @inheritDoc
   */
  public function __construct(
    /**
     * Debug options for inspecting request transformations (streaming only)
     * If true, includes the transformed upstream request body in a debug chunk at the start of the stream. Only works with streaming mode.
     *
     * @var bool
     */
    #[AllowedValues(self::ALLOWED_TYPE)]
    public bool $echo_upstream_body = false,
  ) {
    parent::__construct(...func_get_args());
  }

  /**
   * @return array
   */
  public function convertToArray(): array {
    return array_filter(
      [
        'echo_upstream_body' => $this->echo_upstream_body,
      ],
      fn($value) => $value !== null
    );
  }
}
