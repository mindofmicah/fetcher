<?php

require 'bootstrap.php';

class FetcherTest extends PHPUnit_Framework_TestCase
{

    public function testLimitWithInvalidParams()
    {
        $inputs = array(
            'apple',
            '1,4 asf',
            'asdf, 4',
            'aaa,4,2'
        );
        $fetcher = new Mock_Fetcher;
        foreach ($inputs as $input) {
            $fetcher->setLimit($input);
            $this->assertNull($fetcher->limit);
        }
    }

    public function testLimitSingleIndex()
    {
        $inputs = array(
            '4' => '4',
            '   4' => '4',
            '4      ' => '4'
        );
        $fetcher = new Mock_Fetcher();
        foreach ($inputs as $input => $expected) {
            $fetcher->setLimit($input);
            $this->assertEquals($expected, $fetcher->limit);
        }
    }

    public function testLimitDoubleIndex()
    {
        $inputs = array(
            '5,3' => '5, 3',
            '3, 2' => '3,2'
        );
        $fetcher = new Mock_Fetcher();
        foreach ($inputs as $input => $expected) {
            $fetcher->setLimit($input);
            $this->assertEquals($expected, $fetcher->limit);
        }
    }

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
        foreach ($expected as $input => $output) {
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

    public function testGetLimit()
    {
        $fetcher = new Mock_Fetcher();
        $this->assertNull($fetcher->getLimit());
        $fetcher->limit = '4';
        $this->assertEquals('4', $fetcher->getLimit());
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
        $this->assertInstanceOf('Fetcher', $fetcher->setLimit('1'));
        $this->assertInstanceOf('Fetcher', $fetcher->setLimit('in valid'));
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