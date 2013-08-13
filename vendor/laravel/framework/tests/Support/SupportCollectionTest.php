<?php

use Mockery as m;
use Illuminate\Support\Collection;

class SupportCollectionTest extends PHPUnit_Framework_TestCase {

	public function testFirstReturnsFirstItemInCollection()
	{
		$c = new Collection(array('foo', 'bar'));
		$this->assertEquals('foo', $c->first());
	}

	public function testLastReturnsLastItemInCollection()
	{
		$c = new Collection(array('foo', 'bar'));

		$this->assertEquals('bar', $c->last());
	}


	public function testPopReturnsAndRemovesLastItemInCollection()
	{
		$c = new Collection(array('foo', 'bar'));

		$this->assertEquals('bar', $c->pop());
		$this->assertEquals('foo', $c->first());
	}


	public function testShiftReturnsAndRemovesFirstItemInCollection()
	{
		$c = new Collection(array('foo', 'bar'));

		$this->assertEquals('foo', $c->shift());
		$this->assertEquals('bar', $c->first());
	}


	public function testEmptyCollectionIsEmpty()
	{
		$c = new Collection();

		$this->assertTrue($c->isEmpty());
	}


	public function testToArrayCallsToArrayOnEachItemInCollection()
	{
		$item1 = m::mock('Illuminate\Support\Contracts\ArrayableInterface');
		$item1->shouldReceive('toArray')->once()->andReturn('foo.array');
		$item2 = m::mock('Illuminate\Support\Contracts\ArrayableInterface');
		$item2->shouldReceive('toArray')->once()->andReturn('bar.array');
		$c = new Collection(array($item1, $item2));
		$results = $c->toArray();

		$this->assertEquals(array('foo.array', 'bar.array'), $results);
	}


	public function testToJsonEncodesTheToArrayResult()
	{
		$c = $this->getMock('Illuminate\Support\Collection', array('toArray'));
		$c->expects($this->once())->method('toArray')->will($this->returnValue('foo'));
		$results = $c->toJson();

		$this->assertEquals(json_encode('foo'), $results);
	}


	public function testCastingToStringJsonEncodesTheToArrayResult()
	{
		$c = $this->getMock('Illuminate\Database\Eloquent\Collection', array('toArray'));
		$c->expects($this->once())->method('toArray')->will($this->returnValue('foo'));

		$this->assertEquals(json_encode('foo'), (string) $c);
	}


	public function testOffsetAccess()
	{
		$c = new Collection(array('name' => 'taylor'));
		$this->assertEquals('taylor', $c['name']);
		$c['name'] = 'dayle';
		$this->assertEquals('dayle', $c['name']);
		$this->assertTrue(isset($c['name']));
		unset($c['name']);
		$this->assertFalse(isset($c['name']));
		$c[] = 'jason';
		$this->assertEquals('jason', $c[0]);
	}


	public function testCountable()
	{
		$c = new Collection(array('foo', 'bar'));
		$this->assertEquals(2, count($c));
	}


	public function testIterable()
	{
		$c = new Collection(array('foo'));
		$this->assertInstanceOf('ArrayIterator', $c->getIterator());
		$this->assertEquals(array('foo'), $c->getIterator()->getArrayCopy());
	}


	public function testFilter()
	{
		$c = new Collection(array(array('id' => 1, 'name' => 'Hello'), array('id' => 2, 'name' => 'World')));
		$this->assertEquals(array(1 => array('id' => 2, 'name' => 'World')), $c->filter(function($item)
		{
			return $item['id'] == 2;
		})->all());
	}


	public function testValues()
	{
		$c = new Collection(array(array('id' => 1, 'name' => 'Hello'), array('id' => 2, 'name' => 'World')));
		$this->assertEquals(array(array('id' => 2, 'name' => 'World')), $c->filter(function($item)
		{
			return $item['id'] == 2;
		})->values()->all());
	}


	public function testFlatten()
	{
		$c = new Collection(array(array('#foo', '#bar'), array('#baz')));
		$this->assertEquals(array('#foo', '#bar', '#baz'), $c->flatten()->all());
	}


	public function testMergeArray()
	{
		$c = new Collection(array('name' => 'Hello'));
		$this->assertEquals(array('name' => 'Hello', 'id' => 1), $c->merge(array('id' => 1))->all());
	}


	public function testMergeCollection()
	{
		$c = new Collection(array('name' => 'Hello'));
		$this->assertEquals(array('name' => 'World', 'id' => 1), $c->merge(new Collection(array('name' => 'World', 'id' => 1)))->all());
	}


	public function testCollapse()
	{
		$data = new Collection(array(array($object1 = new StdClass), array($object2 = new StdClass)));
		$this->assertEquals(array($object1, $object2), $data->collapse()->all());
	}


	public function testSort()
	{
		$data = new Collection(array(5, 3, 1, 2, 4));
		$data->sort(function($a, $b)
		{
			if ($a === $b)
			{
		        return 0;
		    }
		    return ($a < $b) ? -1 : 1;
		});

		$this->assertEquals(range(1, 5), array_values($data->all()));
	}


	public function testSortBy()
	{
		$data = new Collection(array('taylor', 'dayle'));
		$data->sortBy(function($x) { return $x; });

		$this->assertEquals(array('dayle', 'taylor'), array_values($data->all()));
	}


	public function testReverse()
	{
		$data = new Collection(array('zaeed', 'alan'));
		$reversed = $data->reverse();

		$this->assertEquals(array('alan', 'zaeed'), array_values($reversed->all()));
	}


	public function testListsWithArrayAndObjectValues()
	{
		$data = new Collection(array((object) array('name' => 'taylor', 'email' => 'foo'), array('name' => 'dayle', 'email' => 'bar')));
		$this->assertEquals(array('taylor' => 'foo', 'dayle' => 'bar'), $data->lists('email', 'name'));
		$this->assertEquals(array('foo', 'bar'), $data->lists('email'));
	}


	public function testImplode()
	{
		$data = new Collection(array(array('name' => 'taylor', 'email' => 'foo'), array('name' => 'dayle', 'email' => 'bar')));
		$this->assertEquals('foobar', $data->implode('email'));
		$this->assertEquals('foo,bar', $data->implode('email', ','));
	}


	public function testTake()
	{
		$data = new Collection(array('taylor', 'dayle', 'shawn'));
		$data = $data->take(2);
		$this->assertEquals(array('taylor', 'dayle'), $data->all());
	}


	public function testTakeLast()
	{
		$data = new Collection(array('taylor', 'dayle', 'shawn'));
		$data = $data->take(-2);
		$this->assertEquals(array('dayle', 'shawn'), $data->all());
	}


	public function testTakeAll()
	{
		$data = new Collection(array('taylor', 'dayle', 'shawn'));
		$data = $data->take();
		$this->assertEquals(array('taylor', 'dayle', 'shawn'), $data->all());
	}


	public function testMakeMethod()
	{
		$collection = Collection::make('foo');
		$this->assertEquals(array('foo'), $collection->all());
	}

}
