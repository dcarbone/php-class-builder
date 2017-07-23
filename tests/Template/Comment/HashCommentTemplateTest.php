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
use DCarbone\PHPClassBuilder\Template\Comment\HashCommentTemplate;
use PHPUnit\Framework\TestCase;

/**
 * Class HashCommentTemplateTest
 */
class HashCommentTemplateTest extends TestCase {
    public function testCanConstructCommentWithoutText() {
        $comment = new HashCommentTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Comment\\HashCommentTemplate', $comment);
    }

    /**
     * @depends testCanConstructCommentWithoutText
     */
    public function testNoOutputWhenEmptyByDefault() {
        $comment = new HashCommentTemplate();
        $this->assertEmpty($comment->compile());
    }

    /**
     * @depends testCanConstructCommentWithoutText
     */
    public function testCanGetEmptyComment() {
        $comment = new HashCommentTemplate();
        $this->assertEmpty($comment->compile(array(CompileOpt::OUTPUT_BLANK_COMMENT => true)));
    }

    /**
     * @depends testCanConstructCommentWithoutText
     */
    public function testCanAddLineToComment() {
        $comment = new HashCommentTemplate();
        $comment->addLine('woop woop');
        $this->assertEquals("    # woop woop\n", $comment->compile());
    }

    /**
     * @depends testCanConstructCommentWithoutText
     */
    public function testCanAddMultipleLines() {
        $comment = new HashCommentTemplate();
        $comment->addLines(array('quiet line', 'LOUD LINE'));
        $this->assertEquals("    # quiet line\n    # LOUD LINE\n", $comment->compile());
    }

    /**
     * @depends testCanConstructCommentWithoutText
     */
    public function testCanAddEmptyLine() {
        $comment = new HashCommentTemplate();
        $comment->addEmptyLine();
        $this->assertEquals("    # \n", $comment->compile());
    }

    /**
     * @depends testCanConstructCommentWithoutText
     */
    public function testCanGetLinesArray() {
        $comment = new HashCommentTemplate();
        $lines = array(
            'neato line',
            'super neato line'
        );
        $comment->addLines($lines);
        $this->assertEquals($lines, $comment->getLines());
    }

    /**
     * @depends testCanConstructCommentWithoutText
     */
    public function testCountReturnsZeroWhenEmpty() {
        $comment = new HashCommentTemplate();
        $this->assertEquals(0, count($comment));
    }

    /**
     * @depends testCanConstructCommentWithoutText
     */
    public function testCanGetCorrectCount() {
        $comment = new HashCommentTemplate();
        $comment->addLines(array(
            1,
            2,
            3,
            4,
            5
        ));
        $this->assertEquals(5, count($comment));
    }
}
