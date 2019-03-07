<?php

/**
 * Tests
 * Playground for unit tests
 *
 * @version 0.1
 * @link https://github.com/yafp/monoto
 * @todo add some real tests
 */

use PHPunit\Framework\TestCase;

class Test extends TestCase 
//class Test extends PHPUnit_Framework_TestCase
{
    public function testOddOrEvenToTrue()
    {
        $this->assertTrue( oddOrEven( 3 ) == true );
    }
}

?>
