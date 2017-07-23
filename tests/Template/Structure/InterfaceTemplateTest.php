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

use DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate;
use PHPUnit\Framework\TestCase;

/**
 * Class InterfaceTemplateTest
 * @package DCarbone\PHPClassBuilder\Tests\Template\Structure
 */
class InterfaceTemplateTest extends TestCase {
    /**
     * @return string
     */
    protected static function generateTestInterfaceName() {
        static $i = 0;
        return sprintf('_class_builder_interface_test_%d', $i++);
    }

    public function testCanConstructWithoutArguments() {
        $int = new InterfaceTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\InterfaceTemplate', $int);
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testDefaultValues() {
        $int = new InterfaceTemplate();
        $this->assertNull($int->getName());
        $interfaces = $int->getParentInterfaces();
        $this->assertInternalType('array', $interfaces);
        $this->assertCount(0, $interfaces);
        $methods = $int->getFunctions();
        $this->assertInternalType('array', $methods);
        $this->assertCount(0, $methods);
        $this->assertNull($int->getNamespace());
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testCanConstructWithValidNameArgument() {
        $intName = self::generateTestInterfaceName();
        $int = new InterfaceTemplate($intName);
        $this->assertEquals($intName, $int->getName());
    }

    /**
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidInterfaceNameException
     * @depends testCanConstructWithValidNameArgument
     */
    public function testExceptionThrownWhenConstructingWithInvalidStringName() {
        new InterfaceTemplate('#woot');
    }

    /**
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidInterfaceNameException
     * @depends testCanConstructWithValidNameArgument
     */
    public function testExceptionThrownWhenConstructingWithInvalidNameType() {
        new InterfaceTemplate(true);
    }

    /**
     * @depends testCanConstructWithValidNameArgument
     */
    public function testCanConstructWithValidNamespaceArgument() {
        $ns = 'MyAwesome\\NS';
        $int = new InterfaceTemplate(null, $ns);
        $this->assertEquals($ns, $int->getNamespace());
    }
}