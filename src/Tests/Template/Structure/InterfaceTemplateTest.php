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

/**
 * Class InterfaceTemplateTest
 * @package DCarbone\PHPClassBuilder\Tests\Template\Structure
 */
class InterfaceTemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return string
     */
    protected static function generateTestInterfaceName()
    {
        static $i = 0;
        return sprintf('_class_builder_interface_test_%d', $i++);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::__construct
     */
    public function testCanConstructWithoutArguments()
    {
        $int = new InterfaceTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\InterfaceTemplate', $int);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::getName
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::getNamespace
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::getParentInterfaces
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::getFunctions
     * @depends testCanConstructWithoutArguments
     */
    public function testDefaultValues()
    {
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
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::setNamespace
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::getNamespace
     * 
     * @depends testCanConstructWithoutArguments
     */
    public function testCanConstructWithValidNameArgument()
    {
        $intName = self::generateTestInterfaceName();
        $int = new InterfaceTemplate($intName);
        $this->assertEquals($intName, $int->getName());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidInterfaceName
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidInterfaceNameException
     * @depends testCanConstructWithValidNameArgument
     */
    public function testExceptionThrownWhenConstructingWithInvalidStringName()
    {
        new InterfaceTemplate('#woot');
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidInterfaceName
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidInterfaceNameException
     * @depends testCanConstructWithValidNameArgument
     */
    public function testExceptionThrownWhenConstructingWithInvalidNameType()
    {
        new InterfaceTemplate(true);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidNamespaceName
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate::setNamespace
     * @depends testCanConstructWithValidNameArgument
     */
    public function testCanConstructWithValidNamespaceArgument()
    {
        $ns = 'MyAwesome\\NS';
        $int = new InterfaceTemplate(null, $ns);
        $this->assertEquals($ns, $int->getNamespace());
    }
}