<?php

/*
 * This file is a part of PHPAX-RS framework, released under terms of GPL-3.0
 * licence. Copyright (c) 2014, UnArt, o.s. All rights reserved.
 */

namespace phpaxrs\common;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-08-05 at 16:40:21.
 */
class DocCommentWrapperTest extends \PHPUnit_Framework_TestCase {

    public function parseProvider() {
        return array(
            array(
                "/** @GET\n"
                . " *\n"
                . " *\n"
                . " * @Consumes(/abs/{id:[0-9]+})\n"
                . " * @Consumes(/uhk)\n"
                . " */",
                array(
                    'GET' => array(),
                    'Consumes' => array('/abs/{id:[0-9]+}', '/uhk')
                )
            )
        );
    }
    
    /**
     * @covers phpaxrs\common\DocCommentWrapper::parse
     * @dataProvider parseProvider
     */
    public function testParse($doc_comment, $expected) {
        $this->assertEquals($expected, DocCommentWrapper::parse($doc_comment));
    }

}