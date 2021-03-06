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
use DCarbone\PHPClassBuilder\Template\Comment\SlashCommentTemplate;
use PHPUnit\Framework\TestCase;

/**
 * Class SlashCommentTemplateTest
 */
class SlashCommentTemplateTest extends TestCase {
    public function testCanConstructCommentWithoutText() {
        $comment = new SlashCommentTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Comment\\SlashCommentTemplate', $comment);
    }

    /**
     * @depends testCanConstructCommentWithoutText
     */
    public function testNoOutputWhenEmptyByDefault() {
        $comment = new SlashCommentTemplate();
        $this->assertEmpty($comment->compile());
    }

    /**
     * @depends testCanConstructCommentWithoutText
     */
    public function testCanGetEmptyComment() {
        $comment = new SlashCommentTemplate();
        $this->assertEmpty($comment->compile(array(CompileOpt::OUTPUT_BLANK_COMMENT => true)));
    }

    /**
     * @depends testCanConstructCommentWithoutText
     */
    public function testCanAddLine() {
        $comment = new SlashCommentTemplate();
        $comment->addLine('super great comment');
        $this->assertEquals("    // super great comment\n", $comment->compile());
    }
}
