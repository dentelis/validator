<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use tests\structs\StructFactory;

final class ObjectSimpleTest extends TestCase
{

    public function testSuccess(): void
    {
        $data = (object)['id' => 1, 'title' => 'lorem ipsum'];

        $struct = StructFactory::simpleClass();

        try {
            $struct->validate($data);
        } catch (Throwable $e) {
            $this->assertNull($e);
        }
        $this->expectNotToPerformAssertions();
    }


}
