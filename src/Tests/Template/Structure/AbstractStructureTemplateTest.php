<?php namespace DCarbone\PHPClassBuilder\Tests\Template\Structure;

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
use DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate;

/**
 * Class AbstractStructureTemplateTest
 * @package DCarbone\PHPClassBuilder\Tests\Template\Structure
 */
class AbstractStructureTemplateTest extends \PHPUnit_Framework_TestCase
{
    private static $_class = '\\DCarbone\\PHPClassBuilder\\Template\\Structure\\AbstractStructureTemplate';

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\AbstractStructureTemplate::getDocBlockComment
     */
    public function testCanGetCommentTemplateWithoutSpecifyOne()
    {
        $stub = $this->getMockForAbstractClass(self::$_class);
        $comment = $stub->getDocBlockComment();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Comment\\DoubleStarCommentTemplate', $comment);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\AbstractStructureTemplate::setDocBlockComment
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\AbstractStructureTemplate::getDocBlockComment
     */
    public function testCanSetCommentTemplate()
    {
        $stub = $this->getMockForAbstractClass(self::$_class);
        $comment = new DoubleStarCommentTemplate();
        $stub->setDocBlockComment($comment);
        $stubComment = $stub->getDocBlockComment();
        $this->assertEquals($comment, $stubComment);
    }
}
