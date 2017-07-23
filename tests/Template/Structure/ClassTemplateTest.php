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
use PHPUnit\Framework\TestCase;

/**
 * Class ClassTemplateTest
 * @package DCarbone\PHPClassBuilder\Tests\Template\Structure
 */
class ClassTemplateTest extends TestCase {
    public function testCanConstructWithoutArguments() {
        $class = new ClassTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\ClassTemplate', $class);
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testDefaultValues() {
        $class = new ClassTemplate();
        $this->assertNull($class->getName());
        $this->assertNull($class->getNamespace());
        $this->assertNull($class->getParent());
        $this->assertEmpty($class->getProperties());
        $this->assertFalse($class->isAbstract());
        $this->assertEmpty($class->getInterfaces());
        $this->assertEmpty($class->getFunctions());
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testCanSetValidClassNames() {
        $class = new ClassTemplate();
        $class->setName('MyGreatClass');
        $this->assertEquals('MyGreatClass', $class->getName());
        $class->setName('_my_great_class');
        $this->assertEquals('_my_great_class', $class->getName());
        $class->setName('my_great_42_class');
        $this->assertEquals('my_great_42_class', $class->getName());
    }

    /**
     * @depends testCanConstructWithoutArguments
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidClassNameException
     */
    public function testExceptionThrownWhenPassingInvalidClassNameToConstructor() {
        new ClassTemplate('haha nope');
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testCanSetValidNamespaceName() {
        $class = new ClassTemplate();
        $class->setNamespace('Woot');
        $this->assertEquals('Woot', $class->getNamespace());
        $class->setNamespace('WOOT');
        $this->assertEquals('WOOT', $class->getNamespace());
        $class->setNamespace('woot');
        $this->assertEquals('woot', $class->getNamespace());
        $class->setNamespace('woot_woot');
        $this->assertEquals('woot_woot', $class->getNamespace());
        $class->setNamespace('woot42woot');
        $this->assertEquals('woot42woot', $class->getNamespace());
    }

    /**
     * @depends testCanConstructWithoutArguments
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidNamespaceNameException
     */
    public function testExceptionThrownWhenPassingInvalidNamespaceNameToConstructor() {
        new ClassTemplate(null, 42);
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testCanSetClassTemplateAsParent() {
        $child = new ClassTemplate('Child');
        $parent = new ClassTemplate('Parent');
        $child->setParent($parent);
        $this->assertSame($parent, $child->getParent());
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testCanSetStringAsParent() {
        $child = new ClassTemplate('Child');
        $parent = '\\SplObject';
        $child->setParent($parent);
        $this->assertEquals($parent, $child->getParent());
    }
}