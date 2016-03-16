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
 * Class HashCommentDefinitionTest
 */
class HashCommentDefinitionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition
     */
    public function testCanConstructComment()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Definition\\Comment\\HashCommentDefinition', $comment);
        return $comment;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition::compile
     */
    public function testCanGetEmptyComment()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition();
        $this->assertEmpty($comment->compile());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition::compile
     */
    public function testCanAddLineToComment()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition();
        $comment->addLine('woop woop');
        $this->assertEquals("    # woop woop\n", $comment->compile());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition::addLines
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition::compile
     */
    public function testCanAddMultipleLines()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition();
        $comment->addLines(array('quiet line', 'LOUD LINE'));
        $this->assertEquals("    # quiet line\n    # LOUD LINE\n", $comment->compile());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition::compile
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition::addEmptyLine
     */
    public function testCanAddEmptyLine()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition();
        $comment->addEmptyLine();
        $this->assertEquals("    # \n", $comment->compile());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition::getLines
     */
    public function testCanGetLinesArray()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition();
        $lines = array(
            'neato line',
            'super neato line'
        );
        $comment->addLines($lines);
        $this->assertEquals($lines, $comment->getLines());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition::count
     */
    public function testCountReturnsZeroWhenEmpty()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition();
        $this->assertEquals(0, count($comment));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition::count
     */
    public function testCanGetCorrectCount()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\HashCommentDefinition();
        $comment->addLines(array(
            1,2,3,4,5
        ));
        $this->assertEquals(5, count($comment));
    }
}
