<?php  namespace DCarbone\PHPClassBuilder\Tests\Template\Comment;

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
 * Class AbstractCommentTemplateTest
 * @package DCarbone\PHPClassBuilder\Tests\Template\Comment
 */
class AbstractCommentTemplateTest extends \PHPUnit_Framework_TestCase
{
    private static $_className = '\\DCarbone\\PHPClassBuilder\\Template\\Comment\\AbstractCommentTemplate';

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::getLines
     */
    public function testCanConstructWithoutText()
    {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $lines = $stub->getlines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(0, $lines);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::getLines
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::addLine
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::testStringForNewlines
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::parseStringInput
     */
    public function testCanConstructWithSingleLineText()
    {
        $stub = $this
            ->getMockBuilder(self::$_className)
            ->setConstructorArgs(array('single line comment'))
            ->getMockForAbstractClass();

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(1, $lines);
        $this->assertEquals(array('single line comment'), $lines);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::getLines
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::addLine
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::testStringForNewlines
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::parseStringInput
     */
    public function testCanConstructWithMultiLineText()
    {
        $stub = $this
            ->getMockBuilder(self::$_className)
            ->setConstructorArgs(array("multi\nline\ncomment"))
            ->getMockForAbstractClass();

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(3, $lines);
        $this->assertEquals(array('multi', 'line', 'comment'), $lines);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::clear
     */
    public function testCanClearOutLines()
    {
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

    public function testCanAddLineAfterConstruct()
    {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $stub->addLine('single added line');

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(1, $lines);
        $this->assertEquals(array('single added line'), $lines);
    }

    public function testCanAddMultilineStringAfterConstruct()
    {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $stub->addLine("double\nline comment");

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(2, $lines);
        $this->assertEquals(array('double', 'line comment'), $lines);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::setLines
     */
    public function testCanOverrideLinesAfterConstruct()
    {
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

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::addLine
     */
    public function testCanAddIntegerAsLine()
    {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $stub->addLine(9001);

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(1, $lines);
        $this->assertEquals(array('9001'), $lines);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::addLine
     */
    public function testCanAddFloatAsLine()
    {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $stub->addLine(90.01);

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(1, $lines);
        $this->assertEquals(array('90.01'), $lines);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::addLine
     */
    public function testCanAddNullAsEmptyLine()
    {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $stub->addLine(null);

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(1, $lines);
        $this->assertEquals(array(''), $lines);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::addLine
     */
    public function testCanAddBooleanAsLine()
    {
        $stub = $this->getMockForAbstractClass(self::$_className);

        $stub->addLine(true);

        $lines = $stub->getLines();

        $this->assertInternalType('array', $lines);
        $this->assertCount(1, $lines);
        $this->assertEquals(array('TRUE'), $lines);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate::addLine
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCommentLineArgumentException
     */
    public function testExceptionThrownWhenAddingNonScalarCommentLine()
    {
        $this
            ->getMockBuilder(self::$_className)
            ->setConstructorArgs(array(new \stdClass()))
            ->getMockForAbstractClass();
    }
}
