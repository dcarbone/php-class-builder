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
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractCommentTemplateTest
 * @package DCarbone\PHPClassBuilder\Tests\Template\Comment
 */
class AbstractCommentTemplateTest extends TestCase {
    private static $_className = '\\DCarbone\\PHPClassBuilder\\Template\\Comment\\AbstractCommentTemplate';

    public function testCanConstructWithoutText() {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $lines = $stub->getlines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(0, $lines);
    }

    public function testCanConstructWithSingleLineText() {
        $stub = $this
            ->getMockBuilder(self::$_className)
            ->setConstructorArgs(array('single line comment'))
            ->getMockForAbstractClass();

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(1, $lines);
        $this->assertEquals(array('single line comment'), $lines);
    }

    public function testCanConstructWithMultiLineText() {
        $stub = $this
            ->getMockBuilder(self::$_className)
            ->setConstructorArgs(array("multi\nline\ncomment"))
            ->getMockForAbstractClass();

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(3, $lines);
        $this->assertEquals(array('multi', 'line', 'comment'), $lines);
    }

    public function testCanClearOutLines() {
        $stub = $this
            ->getMockBuilder(self::$_className)
            ->setConstructorArgs(array("multi\nline\ncomment"))
            ->getMockForAbstractClass();

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(3, $lines);
        $this->assertEquals(array('multi', 'line', 'comment'), $lines);

        $stub->clear();

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(0, $lines);
    }

    public function testCanAddLineAfterConstruct() {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $stub->addLine('single added line');

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(1, $lines);
        $this->assertEquals(array('single added line'), $lines);
    }

    public function testCanAddMultilineStringAfterConstruct() {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $stub->addLine("double\nline comment");

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(2, $lines);
        $this->assertEquals(array('double', 'line comment'), $lines);
    }

    public function testCanOverrideLinesAfterConstruct() {
        $stub = $this
            ->getMockBuilder(self::$_className)
            ->setConstructorArgs(array("multiline\ncomment"))
            ->getMockForAbstractClass();

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(2, $lines);
        $this->assertEquals(array('multiline', 'comment'), $lines);

        $stub->setLines(array("better\ncomment"));

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(2, $lines);
        $this->assertEquals(array('better', 'comment'), $lines);
    }

    public function testCanAddIntegerAsLine() {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $stub->addLine(9001);

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(1, $lines);
        $this->assertEquals(array('9001'), $lines);
    }

    public function testCanAddFloatAsLine() {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $stub->addLine(90.01);

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(1, $lines);
        $this->assertEquals(array('90.01'), $lines);
    }

    public function testCanAddNullAsEmptyLine() {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $stub->addLine(null);

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(1, $lines);
        $this->assertEquals(array(''), $lines);
    }

    public function testCanAddBooleanAsLine() {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $stub->addLine(true);

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(1, $lines);
        $this->assertEquals(array('TRUE'), $lines);
    }

    /**
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCommentLineArgumentException
     */
    public function testExceptionThrownWhenAddingNonScalarCommentLine()
    {
        $this
            ->getMockBuilder(self::$_className)
            ->setConstructorArgs(array(new \stdClass()))
            ->getMockForAbstractClass();
    }

    public function testImplementsIterator() {
        $stub = $this
            ->getMockBuilder(self::$_className)
            ->setConstructorArgs(array("multiline\nmultipass"))
            ->getMockForAbstractClass();

        $this->assertTrue($stub->valid());
        $this->assertEquals(0, $stub->key());
        $this->assertEquals('multiline', $stub->current());
        $stub->next();
        $this->assertTrue($stub->valid());
        $this->assertEquals('multipass', $stub->current());
        $this->assertEquals(1, $stub->key());
        $stub->next();
        $this->assertFalse($stub->valid());
        $stub->rewind();
        $this->assertTrue($stub->valid());
        $this->assertEquals(0, $stub->key());
        $this->assertEquals('multiline', $stub->current());
    }

    public function testCanTestForLineInComment() {
        $stub = $this
            ->getMockBuilder(self::$_className)
            ->setConstructorArgs(array("multiline\nmultipass"))
            ->getMockForAbstractClass();

        $this->assertTrue($stub->hasLine('multiline'));
    }

    public function testCanGetIndexOfLine() {
        $stub = $this
            ->getMockBuilder(self::$_className)
            ->setConstructorArgs(array("multiline\nmultipass"))
            ->getMockForAbstractClass();

        $this->assertEquals(1, $stub->getLineIndex('multipass'));
    }

    public function testCanRemoveLineByIndex() {
        $stub = $this
            ->getMockBuilder(self::$_className)
            ->setConstructorArgs(array("multiline\nmultipass"))
            ->getMockForAbstractClass();

        $this->assertCount(2, $stub);
        $stub->removeLineByIndex(0);
        $this->assertCount(1, $stub);
        $this->assertEquals(0, $stub->getLineIndex('multipass'));
    }

    public function testCanRemoveLineByValue() {
        $stub = $this
            ->getMockBuilder(self::$_className)
            ->setConstructorArgs(array("multiline\nmultipass"))
            ->getMockForAbstractClass();

        $this->assertCount(2, $stub);
        $stub->removeLineByValue('multipass');
        $this->assertCount(1, $stub);
        $this->assertEquals(0, $stub->getLineIndex('multiline'));
    }
}