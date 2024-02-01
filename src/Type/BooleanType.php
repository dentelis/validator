<?php
declare(strict_types=1);

namespace Dentelis\Validator\Type;

use Dentelis\Validator\TypeInterface;

class BooleanType extends AbstractType implements TypeInterface
{
    public function __construct()
    {
        parent::__construct('boolean');
    }
}