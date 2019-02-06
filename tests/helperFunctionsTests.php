<?php
require 'inc/helperFunctions.php';

class HelperFunctionsTests extends PHPUnit_Framework_TestCase
{
    public function test_translateString()
    {
        $this->assertTrue( translateString( "foo" ) == true );
    }
}

?>
