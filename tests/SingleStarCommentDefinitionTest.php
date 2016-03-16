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
 * Class SingleStarCommentTest
 */
class SingleStarCommentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition
     */
    public function testCanConstructComment()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Definition\\Comment\\SingleStarCommentDefinition', $comment);
        return $comment;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::compile
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::parseCompileArgs
     * @depends testCanConstructComment
     * @param \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition $comment
     */
    public function testCanGetEmptyComment(\DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition $comment)
    {
        $output = $comment->compile();
        $this->assertEquals("    /*\n     */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::compile
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::addLine
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::parseStringInput
     */
    public function testCanAddLine()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition();
        $comment->addLine('hey, i\'m a comment!');
        $output = $comment->compile();
        $this->assertEquals("    /*\n     * hey, i'm a comment!\n     */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::compile
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::addLine
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::parseStringInput
     * @return \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition
     */
    public function testCanAddMultilineComment()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition();
        $comment->addLine("this crazy comment\nextends across 2 lines!");
        $output = $comment->compile();
        $this->assertEquals("    /*\n     * this crazy comment\n     * extends across 2 lines!\n     */\n", $output);
        return $comment;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::compile
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::addLine
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::parseStringInput
     * @depends testCanAddMultilineComment
     * @param \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition $comment
     */
    public function testCanSetCustomLeadingSpaces(\DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition $comment)
    {
        $output = $comment->compile(array('leadingSpaces' => 3));
        $this->assertEquals("   /*\n    * this crazy comment\n    * extends across 2 lines!\n    */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::usesBang
     */
    public function testDoesNotUseBangByDefault()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition();
        $this->assertFalse($comment->usesBang());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::compile
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::usesBang
     * @covers \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition::setUseBang
     */
    public function testCanUseBang()
    {
        $comment = new \DCarbone\PHPClassBuilder\Definition\Comment\SingleStarCommentDefinition();
        $this->assertFalse($comment->usesBang());
        $comment->setUseBang(true);
        $this->assertTrue($comment->usesBang());
        $output = $comment->compile();
        $this->assertEquals("    /*!\n     */\n", $output);
    }
}