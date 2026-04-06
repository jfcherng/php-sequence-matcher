<?php

declare(strict_types=1);

namespace Jfcherng\Diff\Test;

use Jfcherng\Diff\SequenceMatcherOptions;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jfcherng\Diff\SequenceMatcherOptions
 *
 * @internal
 */
final class SequenceMatcherOptionsTest extends TestCase
{
    public function testFromArrayWithEmptyArrayUsesDefaults(): void
    {
        $opts = SequenceMatcherOptions::fromArray([]);

        self::assertFalse($opts->ignoreCase);
        self::assertFalse($opts->ignoreLineEnding);
        self::assertFalse($opts->ignoreWhitespace);
        self::assertSame(2000, $opts->lengthLimit);
    }

    public function testFromArrayOverridesSingleKey(): void
    {
        $opts = SequenceMatcherOptions::fromArray(['ignoreCase' => true]);

        self::assertTrue($opts->ignoreCase);
        self::assertFalse($opts->ignoreLineEnding);
        self::assertFalse($opts->ignoreWhitespace);
        self::assertSame(2000, $opts->lengthLimit);
    }

    public function testFromArrayOverridesMultipleKeys(): void
    {
        $opts = SequenceMatcherOptions::fromArray(['ignoreCase' => true, 'lengthLimit' => 500]);

        self::assertTrue($opts->ignoreCase);
        self::assertSame(500, $opts->lengthLimit);
    }

    public function testToArrayReturnsAllKeys(): void
    {
        $opts = new SequenceMatcherOptions();

        self::assertSame([
            'ignoreCase' => false,
            'ignoreLineEnding' => false,
            'ignoreWhitespace' => false,
            'lengthLimit' => 2000,
        ], $opts->toArray());
    }

    public function testRoundTrip(): void
    {
        $original = new SequenceMatcherOptions(ignoreCase: true, lengthLimit: 500);

        self::assertSame($original->toArray(), SequenceMatcherOptions::fromArray($original->toArray())->toArray());
    }

    public function testSettersNoLongerExist(): void
    {
        $opts = new SequenceMatcherOptions();

        self::assertFalse(method_exists($opts, 'setIgnoreCase'));
        self::assertFalse(method_exists($opts, 'setIgnoreLineEnding'));
        self::assertFalse(method_exists($opts, 'setIgnoreWhitespace'));
        self::assertFalse(method_exists($opts, 'setLengthLimit'));
    }
}
