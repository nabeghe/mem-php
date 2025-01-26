<?php declare(strict_types=1);

use Nabeghe\Mem\Mem;
use Nabeghe\Mem\Storage;

class MemTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        Mem::reset();
        Mem::config('default', ['length_limit' => -1]);

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
            'default',
            'my_group',
        ], array_keys(Mem::all()));

        $this->assertEquals((new Storage(['name' => 'nabeghe/mem']))->getData(), Mem::group()->getData());
        $this->assertEquals((new Storage(['my_key' => 'my_value']))->getData(), Mem::group('my_group')->getData());

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

    public function testLengthLimit()
    {
        Mem::reset();

        Mem::config('default', ['length_limit' => 3]);

        Mem::set('item_1', 'value 1');
        Mem::set('item_2', 'value 2');
        Mem::set('item_3', 'value 3');
        Mem::set('item_4', 'value 4');

        $expected = ['item_2' => 'value 2', 'item_3' => 'value 3', 'item_4' => 'value 4'];

        $this->assertEquals((new Storage($expected))->getData(), Mem::group()->getData());
    }

    public function testMatches()
    {
        Mem::reset();
        Mem::config('default', ['length_limit' => -1]);

        Mem::set('nabeghe_1', 'nabeghe value 1');
        Mem::set('nabeghe_2', 'nabeghe value 2');
        Mem::set('mem_1', 'mem value 1');
        Mem::set('mem_2', 'mem value 2');

        $this->assertSame('nabeghe_1', Mem::match('/^nabeghe_.*/'));

        $this->assertSame([
            'nabeghe_1' => 'nabeghe value 1',
            'nabeghe_2' => 'nabeghe value 2',
        ], Mem::matches('/^nabeghe_.*/'));

        $this->assertTrue(Mem::delMatches('/^nabeghe_.*/'));

        $this->assertNull(Mem::match('/^nabeghe_.*/'));

        $this->assertNull(Mem::matches('/^nabeghe_.*/'));

        Mem::set('nabeghe_1', 'nabeghe value 1');
        Mem::set('nabeghe_2', 'nabeghe value 2');

        $this->assertSame([
            'nabeghe_1' => 'nabeghe value 1',
            'nabeghe_2' => 'nabeghe value 2',
        ], Mem::matches('/^nabeghe_.*/'));

        $this->assertSame([
            'mem_1' => 'mem value 1',
            'mem_2' => 'mem value 2',
        ], Mem::matches('/^mem_.*/'));
    }
}