<?php

/*
 * This file is a part of PHPAX-RS framework, released under terms of GPL-3.0
 * licence. Copyright (c) 2014, UnArt Slavičín, o.s. All rights reserved.
 */

namespace phpaxrs\common;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-08-06 at 10:12:34.
 */
class PathUtilTest extends \PHPUnit_Framework_TestCase {

    public function providerIs_valid() {
        return array(
            array('', false),
            array('/', true),
            array('a', false),
            array('/a', true),
            array('//', true),
            array('/§/', false),
        );
    }

    /**
     * @covers phpaxrs\common\PathUtil::is_valid
     * @dataProvider providerIs_valid
     */
    public function testIs_valid($path, $is_valid) {
        $this->assertEquals($is_valid, PathUtil::is_valid($path));
    }
    public function providerIs_valid_template() {
        return array(
            array('', false),
            array('/', true),
            array('a', false),
            array('/a', true),
            array('//', true),
            array('/§/', false),
            array('/a/{a2}', false),
            array('/a/{a/}a/', false),
            array('/a/{idD}-{uF}/{kg}/{rs}', true),
            array('/a/{a:\d+}', true),
            array('/a/{a:\d+.*}', false),
            array('/a/{a:([0-9a-z_@]+a)+}', true),
        );
    }

    /**
     * @covers phpaxrs\common\PathUtil::is_valid_template
     * @dataProvider providerIs_valid_template
     */
    public function testIs_valid_template($templ, $is_valid) {
        $this->assertEquals($is_valid, PathUtil::is_valid_template($templ));
    }
    
    public function providerNormalize() {
        return array(
            array('', '/'),
            array('/', '/'),
            array('a', '/a/'),
            array('/a', '/a/'),
            array('//', '/'),
        );
    }

    /**
     * @covers phpaxrs\common\PathUtil::normalize
     * @dataProvider providerNormalize
     */
    public function testNormalize($path, $npath) {
        $this->assertEquals($npath, PathUtil::normalize($path));
    }

    public function providerRelative() {
        return array(
            array('/freenetis-dev/api', '/freenetis-dev/api', '/'),
            array('/freenetis-dev/api/', '/freenetis-dev/api', '/'),
            array('/freenetis-dev/api/aa', '/freenetis-dev/api', '/aa/'),
            array('/freenetis-dev/api/aa/', '/freenetis-dev/api', '/aa/'),
            array('', '/a', NULL),
            array('/', '/a', NULL),
            array('/b', '/a', NULL),
            array('/aa', '/a', NULL),
            array('/', '/', '/'),
            array('/aaa', '/', '/aaa/'),
            array('/a/b/c/d/e', '/a/b/c/d', '/e/'),
        );
    }

    /**
     * @covers phpaxrs\common\PathUtil::relative
     * @dataProvider providerRelative
     */
    public function testRelative($apath, $ppath, $rpath) {
        $this->assertEquals($rpath, PathUtil::relative($ppath, $apath));
    }
    
    public function providerMatch() {
        return array(
            array('/', '/', TRUE, array()),
            array('/a', '/', FALSE, array()),
            array('/', '/a', FALSE, array()),
            array('/{id}', '/', FALSE, array()),
            array('/{id}', '/11', TRUE, array('11')),
            array('/aaa', '/aaa', TRUE, array()),
            array('/aaa/bb1', '/aaa/bb1', TRUE, array()),
            array('/aaa/{id}', '/aaa/1', TRUE, array('1')),
            array('/aaa/{id}', '/aaa/12', TRUE, array('12')),
            array('/aaa/{id}', '/aaa/a12', TRUE, array('a12')),
            array('/aaa/{id}', '/aaa/a12a', TRUE, array('a12a')),
            array('/aaa/{id}', '/aaa/a12a/', true, array('a12a')),
            array('/aaa/{id}', '/aaa/a12a/aa', FALSE, array()),
            array('/aaa/{id}/fd-{kg}', '/aaa/a12a/fd-12', TRUE, array('a12a', '12')),
            array('/aaa/{id}/fd-{kg}', '/aaa/a12a/fd12', FALSE, array()),
            array('/aaa/{id:[[:alnum:]]+}/fd-{kg:\d+}', '/aaa/a12a/fd-12', TRUE, array('a12a', '12')),
            array('/aaa/{id:\d+}/fd-{kg:\d+}', '/aaa/a12a/fd-12', FALSE, array()),
            array('/aaa/{id:\d+}/fd-{kg:\d+}', '/aaa/a12a/fd-12', FALSE, array()),
            array('/aaa/{id:[^a]+}/fd-{kg:\d+}', '/aaa/a12a/fd-12', FALSE, array()),
            array('/aaa/{id:a12}/fd-{kg:\d+}', '/aaa/a12a/fd-12', FALSE, array()),
        );
    }

    /**
     * @covers phpaxrs\common\PathUtil::match
     * @dataProvider providerMatch
     */
    public function testMatch($template, $path, $eresult, $eargs) {
        $args = PathUtil::match($template, $path);
        $this->assertEquals($eresult, $args !== FALSE);
        if ($eresult) { // do not test
            $this->assertEquals($eargs, $args);
        }
    }

}
