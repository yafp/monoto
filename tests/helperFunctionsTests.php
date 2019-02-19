<?php

/**
 * Tests
 * Playground for unit tests
 * @link: https://github.com/yafp/monoto
 */
class Test extends phpUnitFrameworkTestCase
{
    public function testOddOrEvenToTrue()
    {
        $this->assertTrue( oddOrEven( 3 ) == true );
    }
}

?>
