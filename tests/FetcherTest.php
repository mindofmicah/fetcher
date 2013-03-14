<?php

require 'bootstrap.php';

class FetcherTest extends PHPUnit_Framework_TestCase
{
    public function testSetOrderBySingleColumn()
    {
        $fetcher = new Mock_Fetcher();
        $fetcher->setOrderBy('id');
        $this->assertEquals('id', $fetcher->orderBy);
    }
    public function testSetOrderBySingleColumnWithDirection()
    {
        $expected = array(
            'id ASC' => 'id ASC',
            'name desc' => 'name DESC'
        );
        
        $fetcher = new Mock_Fetcher();
        foreach ($expected as $input=>$output) {
            $fetcher->setOrderBy($input);
            $this->assertEquals($output, $fetcher->orderBy);
        }
    }
    
    public function testSetOrderByMultipleColumns()
    {
        $expected = array(
            'id, title' => 'id, title',
            'id desc, title Asc' => 'id DESC, title ASC'
        );
        
        $fetcher = new Mock_Fetcher();
        foreach ($expected as $input => $output) {
            $fetcher->setOrderBy($input);
            $this->assertEquals($output, $fetcher->orderBy);
        }
    }
    
    public function testSetOrderByBadParameters()
    {
        $inputs = array(
            'id asd',
            '',
            'id ASC, review taco',
            'id ASC review DESC'
        );
        
        $fetcher = new Mock_Fetcher();
        foreach ($inputs as $input) {
            $fetcher->setOrderBy($input);
            $this->assertNull($fetcher->orderBy);
        }
    }
    
    public function testGetOrderBy()
    {
        $fetcher = new Mock_Fetcher();
        
        $this->assertNull($fetcher->getOrderBy());
        $fetcher->orderBy = 'id DESC';
        $this->assertEquals('id DESC', $fetcher->getOrderBy());
    }
    
    public function testChainability()
    {
        $fetcher = new Mock_Fetcher();
        $this->assertInstanceOf('Fetcher', $fetcher->setOrderBy('valid'));
        $this->assertInstanceOf('Fetcher', $fetcher->setOrderBy('in valid'));
    }
}

class Mock_Fetcher extends Fetcher
{
    public function __get($param)
            {
        return $this->$param;
            }
            public function __set($param, $val)
                {
                $this->$param = $val;
                }
}