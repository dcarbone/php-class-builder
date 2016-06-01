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
use DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate;

/**
 * Class ClassTemplateTest
 * @package DCarbone\PHPClassBuilder\Tests\Template\Structure
 */
class ClassTemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::__construct
     * @return \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate
     */
    public function testCanConstructWithoutArguments()
    {
        $class = new ClassTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\ClassTemplate', $class);
        return $class;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::getName
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::getNamespace
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::getProperties
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::getParent
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::isAbstract
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::getInterfaces
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::getFunctions
     * @depends testCanConstructWithoutArguments
     * @param ClassTemplate $classTemplate
     */
    public function testDefaultValues(ClassTemplate $classTemplate)
    {
        $this->assertNull($classTemplate->getName());
        $this->assertNull($classTemplate->getNamespace());
        $this->assertNull($classTemplate->getParent());
        $this->assertEmpty($classTemplate->getProperties());
        $this->assertFalse($classTemplate->isAbstract());
        $this->assertEmpty($classTemplate->getInterfaces());
        $this->assertEmpty($classTemplate->getFunctions());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidClassName
     */
    public function testCanSetValidClassNames()
    {
        $class = new ClassTemplate();
        $class->setName('MyGreatClass');
        $class->setName('_my_great_class');
        $class->setName('my_great_42_class');
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidClassName
     * @covers \DCarbone\PHPClassBuilder\Template\AbstractTemplate::createInvalidClassNameException
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidClassNameException
     */
    public function testExceptionThrownWhenPassingInvalidClassNameToConstructor()
    {
        new ClassTemplate('haha nope');
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::setNamespace
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidNamespaceName
     */
    public function testCanSetValidNamespaceName()
    {
        $class = new ClassTemplate();
        $class->setNamespace('Woot');
        $class->setNamespace('WOOT');
        $class->setNamespace('woot');
        $class->setNamespace('woot_woot');
        $class->setNamespace('woot42woot');
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::setNamespace
     * @covers \DCarbone\PHPClassBuilder\Template\AbstractTemplate::createInvalidNamespaceNameException
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidNamespaceNameException
     */
    public function testExceptionThrownWhenPassingInvalidNamespaceNameToConstructor()
    {
        new ClassTemplate(null, 42);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::setParent
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::getParent
     */
    public function testCanSetClassTemplateAsParent()
    {
        $child = new ClassTemplate('Child');
        $parent = new ClassTemplate('Parent');
        $child->setParent($parent);
        $this->assertEquals($parent, $child->getParent());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::setParent
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate::getParent
     */
    public function testCanSetStringAsParent()
    {
        $child = new ClassTemplate('Child');
        $parent = '\\SplObject';
        $child->setParent($parent);
        $this->assertEquals($parent, $child->getParent());
    }
}