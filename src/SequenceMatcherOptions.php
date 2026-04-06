<?php

declare(strict_types=1);

namespace Jfcherng\Diff;

/**
 * Options for SequenceMatcher.
 */
final class SequenceMatcherOptions
{
    /**
     * @param bool $ignoreCase       ignore case when comparing lines
     * @param bool $ignoreLineEnding ignore line ending differences
     * @param bool $ignoreWhitespace ignore all whitespace when comparing lines
     * @param int  $lengthLimit      lines above this threshold are treated as popular (junk)
     */
    public function __construct(
        public bool $ignoreCase = false,
        public bool $ignoreLineEnding = false,
        public bool $ignoreWhitespace = false,
        public int $lengthLimit = 2000,
    ) {
    }

    /**
     * Create an instance from an associative array.
     * Unknown keys throw \InvalidArgumentException.
     *
     * @param array $options partial or complete options array
     */
    public static function fromArray(array $options): static
    {
        return new self(
            ignoreCase: $options['ignoreCase'] ?? false,
            ignoreLineEnding: $options['ignoreLineEnding'] ?? false,
            ignoreWhitespace: $options['ignoreWhitespace'] ?? false,
            lengthLimit: $options['lengthLimit'] ?? 2000,
        );
    }

    /**
     * Convert options to an associative array.
     *
     * @return array<string, bool|int>
     */
    public function toArray(): array
    {
        return [
            'ignoreCase' => $this->ignoreCase,
            'ignoreLineEnding' => $this->ignoreLineEnding,
            'ignoreWhitespace' => $this->ignoreWhitespace,
            'lengthLimit' => $this->lengthLimit,
        ];
    }

    public function isDifferent(self $that): bool
    {
        return $this->ignoreCase !== $that->ignoreCase
            || $this->ignoreLineEnding !== $that->ignoreLineEnding
            || $this->ignoreWhitespace !== $that->ignoreWhitespace
            || $this->lengthLimit !== $that->lengthLimit;
    }
}
