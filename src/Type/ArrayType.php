<?php
declare(strict_types=1);

namespace Dentelis\Validator\Type;

use Closure;
use Dentelis\Validator\Exception\ValidationException;
use Dentelis\Validator\TypeInterface;
use RuntimeException;

class ArrayType extends AbstractType implements TypeInterface
{
    public function __construct()
    {
        parent::__construct('array');
    }

    public function assertType(TypeInterface|Closure $type): self
    {
        $this->addCustom(function (array $values, array $path) use ($type) {
            $realType = $type;
            foreach ($values as $key => $value) {
                if (is_callable($type)) {
                    $realType = $type($value);
                    if (!($realType instanceof TypeInterface)) {
                        throw new RuntimeException('Property type must be instance of TypeInterface');
                    }
                }
                $realType->validate($value, [...$path, '[' . $key . ']']);
            }
            return true;
        });
        return $this;
    }

    public function assertEmpty(): self
    {
        return $this->assertCount(0);
    }

    public function assertCount(int ...$expected): self
    {
        return $this->assertCountIn($expected);
    }

    /**
     * @param Int[] $expectedCounts possible count values
     */
    public function assertCountIn(array $expectedCounts): self
    {
        $this->addCustom(function ($value) use ($expectedCounts) {
            return in_array(count($value), $expectedCounts, true) ?: throw new ValidationException('array count', join(',', $expectedCounts), count($value));
        });
        return $this;
    }

    public function assertNotEmpty(): self
    {
        return $this->assertCountInterval(min: 1);
    }

    public function assertCountInterval(?int $min = null, ?int $max = null): self
    {
        if (!is_null($min)) {
            $this->addCustom(function ($value) use ($min) {
                return count($value) >= $min ?: throw new ValidationException('array count', '>=' . $min, count($value));
            });
        }
        if (!is_null($max)) {
            $this->addCustom(function ($value) use ($max) {
                return count($value) <= $max ?: throw new ValidationException('array count', '<=' . $max, count($value));
            });
        }
        return $this;
    }

}