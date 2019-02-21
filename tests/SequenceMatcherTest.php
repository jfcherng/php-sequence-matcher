<?php

declare(strict_types=1);

namespace Jfcherng\Diff\Test;

use Jfcherng\Diff\SequenceMatcher;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class SequenceMatcherTest extends TestCase
{
    /**
     * The SequenceMatcher object.
     *
     * @var \Jfcherng\Diff\SequenceMatcher
     */
    protected $sm;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        $this->sm = new SequenceMatcher([], []);
    }

    /**
     * Data provider for SequenceMatcher::getGroupedOpcodes.
     *
     * @return array the data provider
     */
    public function getGroupedOpcodesDataProvider(): array
    {
        return [
            [
                <<<'EOT'
apples
oranges
kiwis
carrots
EOT
                ,
                <<<'EOT'
apples
kiwis
carrots
grapefruits
EOT
                ,
                [
                    [
                        [SequenceMatcher::OP_EQ, 0, 1, 0, 1],
                        [SequenceMatcher::OP_DEL, 1, 2, 1, 1],
                        [SequenceMatcher::OP_EQ, 2, 4, 1, 3],
                        [SequenceMatcher::OP_INS, 4, 4, 3, 4],
                    ],
                ],
            ],
        ];
    }

    /**
     * Test the SequenceMatcher::getGroupedOpcodes.
     *
     * @covers       \Jfcherng\Diff\SequenceMatcher::getGroupedOpcodes
     * @dataProvider getGroupedOpcodesDataProvider
     *
     * @param string $old      the old
     * @param string $new      the new
     * @param array  $expected the expected
     */
    public function testGetGroupedOpcodes(string $old, string $new, array $expected): void
    {
        $this->sm->setSequences(
            \explode("\n", $old),
            \explode("\n", $new)
        );

        $this->assertSame($expected, $this->sm->getGroupedOpcodes());
    }

    /**
     * Data provider for SequenceMatcher::getOpcodes.
     *
     * @return array the data provider
     */
    public function getOpcodesDataProvider(): array
    {
        return [
            [
                ['I', 'Am', 'the', 'best'],
                ['I', 'am', 'almost', 'the', 'best', 'one'],
                [
                    [SequenceMatcher::OP_EQ, 0, 1, 0, 1],
                    [SequenceMatcher::OP_REP, 1, 2, 1, 3],
                    [SequenceMatcher::OP_EQ, 2, 4, 3, 5],
                    [SequenceMatcher::OP_INS, 4, 4, 5, 6],
                ],
            ],
            [
                ['Who', 'has', 'been', 'read', 'the', 'book'],
                ['Who', 'read', 'the', 'book', 'today'],
                [
                    [SequenceMatcher::OP_EQ, 0, 1, 0, 1],
                    [SequenceMatcher::OP_DEL, 1, 3, 1, 1],
                    [SequenceMatcher::OP_EQ, 3, 6, 1, 4],
                    [SequenceMatcher::OP_INS, 6, 6, 4, 5],
                ],
            ],
        ];
    }

    /**
     * Test the SequenceMatcher::getOpcodes.
     *
     * @covers       \Jfcherng\Diff\SequenceMatcher::getOpcodes
     * @dataProvider getOpcodesDataProvider
     *
     * @param string $old      the old
     * @param string $new      the new
     * @param array  $expected the expected
     */
    public function testGetOpcodes(array $old, array $new, array $expected): void
    {
        $this->sm->setSequences($old, $new);

        $this->assertSame($expected, $this->sm->getOpcodes());
    }
}
