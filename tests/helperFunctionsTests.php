<?php
/**
 * Tests
 *
 * @package monoto
 * @author yafp
 * @link https://github.com/yafp/monoto
 *
 */

use PHPunit\Framework\TestCase;


/**
 * Tests
 * Playground for unit tests
 *
 * @package monoto
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
