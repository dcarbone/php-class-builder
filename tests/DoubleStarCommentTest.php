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
 * Class DoubleStarCommentTest
 */
class DoubleStarCommentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition
     */
    public function testCanConstructComment()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Definition\\Comment\\DoubleStarCommentDefinition', $comment);
        return $comment;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::compile
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::parseCompileArgs
     * @depends testCanConstructComment
     * @param \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition $comment
     */
    public function testCanGetEmptyComment(\DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition $comment)
    {
        $output = $comment->compile();
        $this->assertEquals("    /**\n     */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::compile
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::addLine
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::parseStringInput
     */
    public function testCanAddLine()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition();
        $comment->addLine('hey, i\'m a comment!');
        $output = $comment->compile();
        $this->assertEquals("    /**\n     * hey, i'm a comment!\n     */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::compile
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::addLine
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::parseStringInput
     * @return \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition
     */
    public function testCanAddMultilineComment()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition();
        $comment->addLine("this crazy comment\nextends across 2 lines!");
        $output = $comment->compile();
        $this->assertEquals("    /**\n     * this crazy comment\n     * extends across 2 lines!\n     */\n", $output);
        return $comment;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::compile
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::addLine
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::parseStringInput
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::parseCompileArgs
     * @depends testCanAddMultilineComment
     * @param \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition $comment
     */
    public function testCanSetCustomLeadingSpaces(\DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition $comment)
    {
        $output = $comment->compile(array('leadingSpaces' => 3));
        $this->assertEquals("   /**\n    * this crazy comment\n    * extends across 2 lines!\n    */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::parseCompileArgs
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileArgumentValueException
     */
    public function testExceptionThrownWhenPassingInvalidLeadingSpacesValue()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition();
        $comment->compile(array('leadingSpaces' => 'sandwiches'));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::usesBang
     */
    public function testDoesNotUseBangByDefault()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition();
        $this->assertFalse($comment->usesBang());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::compile
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::usesBang
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition::setUseBang
     */
    public function testCanUseBang()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition();
        $this->assertFalse($comment->usesBang());
        $comment->setUseBang(true);
        $this->assertTrue($comment->usesBang());
        $output = $comment->compile();
        $this->assertEquals("    /**!\n     */\n", $output);
    }
}