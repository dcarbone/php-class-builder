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
use DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate;

/**
 * Class DoubleStarCommentTemplateTest
 */
class DoubleStarCommentTemplateTest extends \PHPUnit_Framework_TestCase
{
    public function testCanConstructCommentWithoutText()
    {
        $comment = new DoubleStarCommentTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Comment\\DoubleStarCommentTemplate', $comment);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::parseCompileOpts
     * @depends testCanConstructCommentWithoutText
     */
    public function testNoOutputWhenEmptyByDefault()
    {
        $comment = new DoubleStarCommentTemplate();
        $this->assertEmpty($comment->compile());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::parseCompileOpts
     * @depends testCanConstructCommentWithoutText
     */
    public function testCanGetEmptyComment()
    {
        $comment = new DoubleStarCommentTemplate();
        $output = $comment->compile(array(CompileOpt::OUTPUT_BLANK_COMMENT => true));
        $this->assertEquals("    /**\n     */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::compile
     */
    public function testCanAddLine()
    {
        $comment = new DoubleStarCommentTemplate();
        $comment->addLine('hey, i\'m a comment!');
        $output = $comment->compile();
        $this->assertEquals("    /**\n     * hey, i'm a comment!\n     */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::compile
     * @return DoubleStarCommentTemplate
     */
    public function testCanAddMultilineComment()
    {
        $comment = new DoubleStarCommentTemplate();
        $comment->addLine("this crazy comment\nextends across 2 lines!");
        $output = $comment->compile();
        $this->assertEquals("    /**\n     * this crazy comment\n     * extends across 2 lines!\n     */\n", $output);
        return $comment;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::__construct
     */
    public function testCanConstructCommentWithText()
    {
        $comment = new DoubleStarCommentTemplate("really cool\ncomment");
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Comment\\DoubleStarCommentTemplate', $comment);
        $this->assertEquals("    /**\n     * really cool\n     * comment\n     */\n", $comment->compile());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::addLine
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::parseStringInput
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::parseCompileOpts
     * @depends testCanAddMultilineComment
     * @param DoubleStarCommentTemplate $comment
     */
    public function testCanSetCustomLeadingSpaces(DoubleStarCommentTemplate $comment)
    {
        $output = $comment->compile(array(CompileOpt::LEADING_SPACES => 3));
        $this->assertEquals("   /**\n    * this crazy comment\n    * extends across 2 lines!\n    */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::parseCompileOpts
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    public function testExceptionThrownWhenPassingInvalidLeadingSpacesValue()
    {
        $comment = new DoubleStarCommentTemplate();
        $comment->compile(array(CompileOpt::LEADING_SPACES => 'sandwiches'));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::usesBang
     */
    public function testDoesNotUseBangByDefault()
    {
        $comment = new DoubleStarCommentTemplate();
        $this->assertFalse($comment->usesBang());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::usesBang
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::setUseBang
     */
    public function testCanUseBang()
    {
        $comment = new DoubleStarCommentTemplate('i\'ma comment!');
        $this->assertFalse($comment->usesBang());
        $comment->setUseBang(true);
        $this->assertTrue($comment->usesBang());
        $output = $comment->compile();
        $this->assertEquals("    /**!\n     * i'ma comment!\n     */\n", $output);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::onSingleLine
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::setSingleLineOutput
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate::compile
     */
    public function testCanGetSingleLineOutput()
    {
        $comment = new DoubleStarCommentTemplate('@var mixed $woot');
        $comment->setSingleLineOutput(true);
        $this->assertTrue($comment->onSingleLine());
        $this->assertEquals("    /** @var mixed \$woot */\n", $comment->compile());
    }
}