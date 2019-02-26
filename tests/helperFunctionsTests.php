<?php

/**
 * Tests
 * Playground for unit tests
 * @version 0.1
 * @see https://github.com/yafp/monoto
 * @link https://github.com/yafp/monoto
 */
class Test extends phpUnitFrameworkTestCase
{
    public function testOddOrEvenToTrue()
    {
        $this->assertTrue( oddOrEven( 3 ) == true );
    }
}

?>
