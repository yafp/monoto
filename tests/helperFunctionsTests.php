<?php
/**
 * Tests
 *
 * @package Monoto
 * @author yafp
 * @see https://github.com/yafp/monoto
 * @link https://github.com/yafp/monoto
 */

use PHPunit\Framework\TestCase;


/**
 * Tests
 * Playground for unit tests
 *
 * @package Monoto
 * @version 0.1
 * @link https://github.com/yafp/monoto
 *
 */
class Test extends TestCase
{
    public function testOddOrEvenToTrue()
    {
        $this->assertTrue( oddOrEven( 3 ) == true );
    }
}

?>
