<?php

/*
 * This file is a part of PHPAX-RS framework, released under terms of GPL-3.0
 * licence. Copyright (c) 2014, UnArt Slavičín, o.s. All rights reserved.
 */

namespace phpaxrs\http;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-08-05 at 09:40:23.
 */
class URLTest extends \PHPUnit_Framework_TestCase {

    /*
     * @var URL
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new URL('http://admin:jadmin@google.com:80/a/b/c2?o=1#s');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    }
    
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testNoUrl()
    {
        new URL();
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyUrl()
    {
        new URL('');
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testMallformedUrl()
    {
        new URL('http::://aaa/');
    }

    /**
     * @covers URL::get_scheme
     */
    public function testGet_scheme() {
        $this->assertEquals('http', $this->object->get_scheme());
    }

    /**
     * @covers URL::get_host
     */
    public function testGet_host() {
        $this->assertEquals('google.com', $this->object->get_host());
    }

    /**
     * @covers URL::get_port
     */
    public function testGet_port() {
        $this->assertEquals('80', $this->object->get_port());
    }

    /**
     * @covers URL::get_user
     */
    public function testGet_user() {
        $this->assertEquals('admin', $this->object->get_user());
    }

    /**
     * @covers URL::get_password
     */
    public function testGet_password() {
        $this->assertEquals('jadmin', $this->object->get_password());
    }

    /**
     * @covers URL::get_path
     */
    public function testGet_path() {
        $this->assertEquals('/a/b/c2', $this->object->get_path());
    }

    /**
     * @covers URL::get_query_string
     */
    public function testGet_query_string() {
        $this->assertEquals('o=1', $this->object->get_query_string());
    }

    /**
     * @covers URL::get_fragment
     */
    public function testGet_fragment() {
        $this->assertEquals('s', $this->object->get_fragment());
    }

}
