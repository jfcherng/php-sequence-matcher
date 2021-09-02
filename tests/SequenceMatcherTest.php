<?php

declare(strict_types=1);

namespace Jfcherng\Diff\Test;

use Jfcherng\Diff\SequenceMatcher;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
final class SequenceMatcherTest extends TestCase
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
A
B
C
D
EOT
                ,
                <<<'EOT'
A
B
C
D
EOT
                ,
                [],
            ],
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

        static::assertSame($expected, $this->sm->getGroupedOpcodes());
    }

    /**
     * Data provider for SequenceMatcher::getGroupedOpcodes.
     *
     * @return array the data provider
     */
    public function getGroupedOpcodesWithZeroContextDataProvider(): array
    {
        return [
            [
                <<<'EOT'
A
B
C
D
EOT
                ,
                <<<'EOT'
A
B
C
D
EOT
                ,
                [],
            ],
            [
                <<<'EOT'
A
B
C
D
EOT
                ,
                <<<'EOT'
A
B
X
D
EOT
                ,
                [
                    [
                        [SequenceMatcher::OP_REP, 2, 3, 2, 3],
                    ],
                ],
            ],
        ];
    }

    /**
     * Test the SequenceMatcher::getGroupedOpcodes.
     *
     * @covers       \Jfcherng\Diff\SequenceMatcher::getGroupedOpcodes
     * @dataProvider getGroupedOpcodesWithZeroContextDataProvider
     *
     * @param string $old      the old
     * @param string $new      the new
     * @param array  $expected the expected
     */
    public function testGetGroupedOpcodesWithZeroContext(string $old, string $new, array $expected): void
    {
        $this->sm->setSequences(
            \explode("\n", $old),
            \explode("\n", $new)
        );

        static::assertSame($expected, $this->sm->getGroupedOpcodes(0));
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
                ['foo'],
                [],
                [
                    [SequenceMatcher::OP_DEL, 0, 1, 0, 0],
                ],
            ],
            [
                ['foo'],
                [''],
                [
                    [SequenceMatcher::OP_REP, 0, 1, 0, 1],
                ],
            ],
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

        static::assertSame($expected, $this->sm->getOpcodes());
    }

    /**
     * Data provider for SequenceMatcher::getGroupedOpcodes with "ignoreWhitespaces".
     *
     * @return array the data provider
     */
    public function getGroupedOpcodesIgnoreWhitespacesDataProvider(): array
    {
        return [
            [
                <<<'OLD'
<?php

function foo(\DateTimeImmutable $date)
{
    if ($date) {
        echo 'foo';
    } else {
        echo 'bar';
    }
}

OLD
                ,
                <<<'NEW'
<?php

function foo(\DateTimeImmutable $date)
{
    echo 'foo';
}

NEW
                ,
                [
                    [
                        [SequenceMatcher::OP_DEL, 4, 5, 4, 4],
                    ],
                    [
                        [SequenceMatcher::OP_DEL, 6, 9, 5, 5],
                    ],
                ],
            ],
            [
                <<<'OLD'
<?php

class Foo
{
    function foo()
    {
        echo 'haha';
        return;

        echo 'blabla';
        if (false) {

        }
    }

}

OLD
                ,
                <<<'NEW'
<?php

class Foo
{
    function foo()
    {
        echo 'haha';
        return;
    }

}

NEW
                ,
                [
                    [
                        [SequenceMatcher::OP_DEL, 8, 13, 8, 8],
                    ],
                ],
            ],
            [
                \file_get_contents(__DIR__ . '/data/WorkerCommandA.php'),
                \file_get_contents(__DIR__ . '/data/WorkerCommandB.php'),
                [
                    [
                        [SequenceMatcher::OP_DEL, 217, 222, 217, 217],
                    ],
                ],
            ],
        ];
    }

    /**
     * Test the SequenceMatcher::getOpcodes with "ignoreWhitespaces".
     *
     * @covers       \Jfcherng\Diff\SequenceMatcher::getOpcodes
     * @dataProvider getGroupedOpcodesIgnoreWhitespacesDataProvider
     *
     * @param string $old      the old
     * @param string $new      the new
     * @param array  $expected the expected
     */
    public function testGetOpcodesIgnoreWhitespaces(string $old, string $new, array $expected): void
    {
        $this->sm->setSequences(\explode("\n", $old), \explode("\n", $new));
        $this->sm->setOptions(['ignoreWhitespace' => true]);

        static::assertSame($expected, $this->sm->getGroupedOpcodes(0));
    }

    /**
     * @see https://github.com/jfcherng/php-sequence-matcher/issues/2
     */
    public function testCachingBugIssue2(): void
    {
        $old = ['a'];
        $new = ['a', 'b', 'c'];

        $this->sm->setSeq1($old)->setSeq2($new);

        $old = ['a', 'b'];
        $new = ['a', 'b', 'c'];

        $this->sm->setSeq1($old)->setSeq2($new);

        try {
            // Throws ErrorException: "Undefined array key 1"
            $this->sm->getOpcodes();
        } catch (\Exception $e) {
            static::fail((string) $e);
        }

        static::assertTrue(true);
    }
}
