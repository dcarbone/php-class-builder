<?php

/*
 * Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Class HashCommentTemplateTest
 */
class HashCommentTemplateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate
     */
    public function testCanConstructComment()
    {
        $comment = new \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Comment\\HashCommentTemplate', $comment);
        return $comment;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate::compile
     */
    public function testCanGetEmptyComment()
    {
        $comment = new \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate();
        $this->assertEmpty($comment->compile());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate::compile
     */
    public function testCanAddLineToComment()
    {
        $comment = new \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate();
        $comment->addLine('woop woop');
        $this->assertEquals("    # woop woop\n", $comment->compile());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate::addLines
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate::compile
     */
    public function testCanAddMultipleLines()
    {
        $comment = new \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate();
        $comment->addLines(array('quiet line', 'LOUD LINE'));
        $this->assertEquals("    # quiet line\n    # LOUD LINE\n", $comment->compile());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate::addEmptyLine
     */
    public function testCanAddEmptyLine()
    {
        $comment = new \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate();
        $comment->addEmptyLine();
        $this->assertEquals("    # \n", $comment->compile());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate::getLines
     */
    public function testCanGetLinesArray()
    {
        $comment = new \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate();
        $lines = array(
            'neato line',
            'super neato line'
        );
        $comment->addLines($lines);
        $this->assertEquals($lines, $comment->getLines());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate::count
     */
    public function testCountReturnsZeroWhenEmpty()
    {
        $comment = new \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate();
        $this->assertEquals(0, count($comment));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate::count
     */
    public function testCanGetCorrectCount()
    {
        $comment = new \DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate();
        $comment->addLines(array(
            1,2,3,4,5
        ));
        $this->assertEquals(5, count($comment));
    }
}
