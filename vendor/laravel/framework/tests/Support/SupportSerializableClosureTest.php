<?php

use Illuminate\Support\SerializableClosure as S;

class SupportSerializableClosureTest extends PHPUnit_Framework_TestCase {

	public function testClosureCanBeSerializedAndRebuilt()
	{
		$f = new S(function() { return 'hello'; });
		$serialized = serialize($f);
		$unserialized = unserialize($serialized);

		$this->assertEquals('hello', $unserialized());
	}


	public function testClosureCanBeSerializedAndRebuiltAndInheritState()
	{
		$a = 1;
		$b = 1;
		$f = new S(function($i) use ($a, $b)
		{
			return $a + $b + $i;
		});
		$serialized = serialize($f);
		$unserialized = unserialize($serialized);

		$this->assertEquals(3, $unserialized(1));
	}

}