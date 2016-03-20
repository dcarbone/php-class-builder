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
 * Class SlashCommentTemplateTest
 */
class SlashCommentTemplateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return \DCarbone\PHPClassBuilder\Template\Comment\SlashCommentTemplate
     */
    public function testCanConstructComment()
    {
        $comment = new \DCarbone\PHPClassBuilder\Template\Comment\SlashCommentTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Comment\\SlashCommentTemplate', $comment);
        return $comment;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SlashCommentTemplate::compile
     */
    public function testCanGetEmptyComment()
    {
        $comment = new \DCarbone\PHPClassBuilder\Template\Comment\SlashCommentTemplate();
        $this->assertEquals('', $comment->compile());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SlashCommentTemplate::addLine
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\SlashCommentTemplate::compile
     */
    public function testCanAddLine()
    {
        $comment = new \DCarbone\PHPClassBuilder\Template\Comment\SlashCommentTemplate();
        $comment->addLine('super great comment');
        $this->assertEquals("    // super great comment\n", $comment->compile());
    }
}
