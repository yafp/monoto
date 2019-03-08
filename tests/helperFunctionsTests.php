<?php


use PHPunit\Framework\TestCase;


/**
 * Tests
 * Playground for unit tests
 *
 * @version 0.1
 * @link https://github.com/yafp/monoto
 * @todo add some real tests
 */
class Test extends TestCase
{
    public function testOddOrEvenToTrue()
    {
        $this->assertTrue( oddOrEven( 3 ) == true );
    }
}

?>
