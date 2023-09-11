<?php

namespace tests\structs;

use EntelisTeam\Validator\Enum\_simpleType;
use EntelisTeam\Validator\Structure\_object;
use EntelisTeam\Validator\Structure\_property_simple;

class StructFactory
{
    static function simpleClass(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

}