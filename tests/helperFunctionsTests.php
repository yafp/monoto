<?php

/**
 * Tests
 * Playground for unit tests
 * @see https://github.com/yafp/monoto
 * @link https://github.com/yafp/monoto
 * @version 0.1
 */
class Test extends phpUnitFrameworkTestCase
{
    public function testOddOrEvenToTrue()
    {
        $this->assertTrue( oddOrEven( 3 ) == true );
    }
}

?>
