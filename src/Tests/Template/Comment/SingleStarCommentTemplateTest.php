<?php namespace DCarbone\PHPClassBuilder\Tests\Template\Comment;

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

use DCarbone\PHPClassBuilder\Enum\CompileOpt;
use DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate;

/**
 * Class SingleStarCommentTest
 */
class SingleStarCommentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return SingleStarCommentTemplate
     */
    public function testCanConstructComment()
    {
        $comment = new SingleStarCommentTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Comment\\SingleStarCommentTemplate', $comment);
        return $comment;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::parseCompileOpts
     * @depends testCanConstructComment
     * @param SingleStarCommentTemplate $comment
     */
    public function testCanGetEmptyComment(SingleStarCommentTemplate $comment)
    {
        $output = $comment->compile();
        $this->assertEquals("    /*\n     */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::addLine
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::parseStringInput
     */
    public function testCanAddLine()
    {
        $comment = new SingleStarCommentTemplate();
        $comment->addLine('hey, i\'m a comment!');
        $output = $comment->compile();
        $this->assertEquals("    /*\n     * hey, i'm a comment!\n     */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::addLine
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::parseStringInput
     * @return SingleStarCommentTemplate
     */
    public function testCanAddMultilineComment()
    {
        $comment = new SingleStarCommentTemplate();
        $comment->addLine("this crazy comment\nextends across 2 lines!");
        $output = $comment->compile();
        $this->assertEquals("    /*\n     * this crazy comment\n     * extends across 2 lines!\n     */\n", $output);
        return $comment;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::addLine
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::parseStringInput
     * @depends testCanAddMultilineComment
     * @param SingleStarCommentTemplate $comment
     */
    public function testCanSetCustomLeadingSpaces(SingleStarCommentTemplate $comment)
    {
        $output = $comment->compile(array(CompileOpt::LEADING_SPACES => 3));
        $this->assertEquals("   /*\n    * this crazy comment\n    * extends across 2 lines!\n    */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::usesBang
     */
    public function testDoesNotUseBangByDefault()
    {
        $comment = new SingleStarCommentTemplate();
        $this->assertFalse($comment->usesBang());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::usesBang
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::setUseBang
     */
    public function testCanUseBang()
    {
        $comment = new SingleStarCommentTemplate();
        $this->assertFalse($comment->usesBang());
        $comment->setUseBang(true);
        $this->assertTrue($comment->usesBang());
        $output = $comment->compile();
        $this->assertEquals("    /*!\n     */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::onSingleLine
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::setSingleLineOutput
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SingleStarCommentTemplate::compile
     */
    public function testCanGetSingleLineOutput()
    {
        $comment = new SingleStarCommentTemplate('@var mixed $woot');
        $comment->setSingleLineOutput(true);
        $this->assertTrue($comment->onSingleLine());
        $this->assertEquals("    /* @var mixed \$woot */\n", $comment->compile());
    }
}