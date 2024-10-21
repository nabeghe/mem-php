<?php declare(strict_types=1);

use Nabeghe\Mem\Mem;

class MemTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        // has (default group)
        $this->assertFalse(Mem::has('name'));
        $this->assertEquals(0, Mem::groupsCount());

        // get default value (default group)
        $this->assertNull(Mem::get('name'));
        $this->assertSame('my_default_value', Mem::get('name', 'default', 'my_default_value'));
        $this->assertNull(Mem::get('name'));

        // set (default group)
        Mem::set('name', 'nabeghe/mem');
        $this->assertTrue(Mem::has('name'));
        $this->assertSame('nabeghe/mem', Mem::get('name'));
        $this->assertEquals(1, Mem::groupsCount());

        // has (my_group)
        $this->assertFalse(Mem::has('name', 'my_group'));

        // get default value (my_group)
        $this->assertNull(Mem::get('name', 'my_group'));
        $this->assertSame('my_default_value', Mem::get('name', 'my_group', 'my_default_value'));
        $this->assertNull(Mem::get('name', 'my_group'));

        // set (my_group)
        Mem::set('my_key', 'my_value', 'my_group');
        $this->assertTrue(Mem::has('my_key', 'my_group'));
        $this->assertSame('my_value', Mem::get('my_key', 'my_group'));

        $this->assertEquals([
            'default' => ['name' => 'nabeghe/mem'],
            'my_group' => ['my_key' => 'my_value'],
        ], Mem::all());

        $this->assertEquals(['name' => 'nabeghe/mem'], Mem::group());
        $this->assertEquals(['my_key' => 'my_value'], Mem::group('my_group'));

        $this->assertEquals(2, Mem::groupsCount());

        // del
        $this->assertTrue(Mem::has('name'));
        Mem::del('name');
        $this->assertFalse(Mem::has('name'));
        $this->assertTrue(Mem::has('my_key', 'my_group'));
        Mem::del('my_key', 'my_group');
        $this->assertFalse(Mem::has('name'));
        $this->assertFalse(Mem::has('my_key', 'my_group'));

        Mem::set('name', 'nabeghe/mem');
        Mem::set('my_key', 'my_value', 'my_group');
        $this->assertTrue(Mem::has('name'));
        $this->assertTrue(Mem::has('my_key', 'my_group'));
        $this->assertSame('nabeghe/mem', Mem::get('name'));
        $this->assertSame('my_value', Mem::get('my_key', 'my_group'));

        Mem::drop();
        $this->assertEquals(1, Mem::groupsCount());
        $this->assertFalse(Mem::has('false'));
        $this->assertTrue(Mem::has('my_key', 'my_group'));

        Mem::reset();
        $this->assertEquals([], Mem::all());
    }
}