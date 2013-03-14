<?php

require 'bootstrap.php';

class FetcherTest extends PHPUnit_Framework_TestCase
{

    public function testFailure()
    {
        $this->fail('just looking');
    }

}
