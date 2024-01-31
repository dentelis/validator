<?php
declare(strict_types=1);

namespace tests;

use Dentelis\Validator\Type\StringType;
use Dentelis\Validator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[
    CoversClass(StringType::class),
]
final class StringTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            ['foo', (new StringType())],
            ['foo bar', (new StringType())],
            ['', (new StringType())],
            ['123', (new StringType())],
            ['123456', (new StringType())->assertRegexp('~^(\d+)$~')],
            ['foo', (new StringType())->assertLength(3, 3)],
            ['foo', (new StringType())->assertLength(0, 100)],
            ['user@example.com', (new StringType())->assertEmail()],
            ['https://example.com', (new StringType())->assertUrl()],
            [null, (new StringType())->setNullAllowed()],
            [null, (new StringType())->setNullAllowed()->assertEmail()],
            [null, (new StringType())->setNullAllowed()->assertUrl()],
            [null, (new StringType())->setNullAllowed()->assertRegexp('~^(\d+)$~')],
            [null, (new StringType())->setNullAllowed()->assertLength(3, 3)],
        ];
    }

    public static function failProvider(): array
    {
        return [
            [1, (new StringType())],
            [-1, (new StringType())],
            [0, (new StringType())],
            [100, (new StringType())],
            [100.11, (new StringType())],
            [true, (new StringType())],
            [false, (new StringType())],
            [['foo' => 'bar'], (new StringType())],
            [(object)['foo' => 'bar'], (new StringType())],
            ['foobar', (new StringType())->assertRegexp('~^(\d+)$~')],
            [null, (new StringType())],
            ['', (new StringType())->assertLength(1)],
            ['foobar', (new StringType())->assertLength(0, 3)],
            ['foo', (new StringType())->assertEmail()],
            ['', (new StringType())->assertEmail()],
            ['example.com', (new StringType())->assertUrl()],
            ['', (new StringType())->assertUrl()],
            ['user@example.com', (new StringType())->assertEmail()->assertLength(max:6)]
        ];

    }

    #[DataProvider('successProvider')]
    public function testSuccess(mixed $value, TypeInterface $type): void
    {
        try {
            $type->validate($value);
        } catch (\Throwable $e) {
            $this->assertNull($e);
        }
        $this->expectNotToPerformAssertions();
    }

    #[DataProvider('failProvider')]
    public function testFail(mixed $value, TypeInterface $type): void
    {
        $e = null;
        try {
            $type->validate($value);
        } catch (\Throwable $e) {
            $this->assertInstanceOf(\Throwable::class, $e);
        } finally {
            $this->assertNotNull($e, sprintf('Value <%s> MUST throw an exception', ($value instanceof \Stringable ? $value : '...')));
        }
    }


}