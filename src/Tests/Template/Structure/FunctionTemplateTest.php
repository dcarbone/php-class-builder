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

use DCarbone\PHPClassBuilder\Enum\ScopeEnum;
use DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate;
use DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate;

/**
 * Class FunctionTemplateTest
 * @package DCarbone\PHPClassBuilder\Tests\Template\Structure
 */
class FunctionTemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @return FunctionTemplate
     */
    public function testCanConstructWithoutArguments()
    {
        $func = new FunctionTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\FunctionTemplate', $func);
        return $func;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getScope
     * @depends testCanConstructWithoutArguments
     * @param FunctionTemplate $func
     */
    function testFunctionHasDefaultScopeOfPublic(FunctionTemplate $func)
    {
        $this->assertEquals(ScopeEnum::_PUBLIC, $func->getScope());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::isStatic
     * @depends testCanConstructWithoutArguments
     * @param FunctionTemplate $func
     */
    function testFunctionNotStaticByDefault(FunctionTemplate $func)
    {
        $this->assertFalse($func->isStatic());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::isAbstract
     * @depends testCanConstructWithoutArguments
     * @param FunctionTemplate $func
     */
    function testFunctionNotAbstractByDefault(FunctionTemplate $func)
    {
        $this->assertFalse($func->isAbstract());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getName
     * @depends testCanConstructWithoutArguments
     * @return FunctionTemplate
     */
    public function testCanConstructWithName()
    {
        $func = new FunctionTemplate('test');
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\FunctionTemplate', $func);
        $this->assertEquals('test', $func->getName());
        return $func;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidFunctionName
     * @depends testCanConstructWithName
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidFunctionNameException
     */
    function testExceptionThrownWhenConstructingWithInvalidStringName()
    {
        new FunctionTemplate('1-2-iloveyou');
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidFunctionName
     * @depends testCanConstructWithName
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidFunctionNameException
     */
    function testExceptionThrownWhenConstructingWithNonStringName()
    {
        new FunctionTemplate(array());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getScope
     */
    function testCanConstructWithCustomScope()
    {
        $func = new FunctionTemplate(null, new ScopeEnum(ScopeEnum::_PROTECTED));
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\FunctionTemplate', $func);
        $this->assertEquals(ScopeEnum::_PROTECTED, $func->getScope());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::isStatic
     */
    function testCanConstructAsStatic()
    {
        $func = new FunctionTemplate(null, new ScopeEnum(ScopeEnum::_PUBLIC), true);
        $this->assertTrue($func->isStatic());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::isAbstract
     */
    function testCanConstructAsAbstract()
    {
        $func = new FunctionTemplate(null, new ScopeEnum(ScopeEnum::_PUBLIC), false, true);
        $this->assertTrue($func->isAbstract());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidFunctionName
     * @depends testCanConstructWithoutArguments
     */
    function testCanSetNamePostConstruct()
    {
        $func = new FunctionTemplate();
        $func->setName('test');
        $this->assertEquals('test', $func->getName());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidFunctionName
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidFunctionNameException
     * @depends testCanSetNamePostConstruct
     */
    function testExceptionThrownWhenSettingInvalidNameString()
    {
        $func = new FunctionTemplate();
        $func->setName('1234iloveyoumore');
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidFunctionName
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidFunctionNameException
     * @depends testCanSetNamePostConstruct
     */
    function testExceptionThrownWhenSettingNonStringName()
    {
        $func = new FunctionTemplate();
        $func->setName(array());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::setScope
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getScope
     * @depends testCanConstructWithoutArguments
     */
    function testCanSetScopePostConstruct()
    {
        $func = new FunctionTemplate();
        $func->setScope(new ScopeEnum(ScopeEnum::_PRIVATE));
        $this->assertEquals(ScopeEnum::_PRIVATE, $func->getScope());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::setStatic
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::isStatic
     * @depends testCanConstructWithoutArguments
     */
    function testCanSetAsStaticPostConstruct()
    {
        $func = new FunctionTemplate();
        $func->setStatic(true);
        $this->assertTrue($func->isStatic());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::setAbstract
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::isAbstract
     * @depends testCanConstructWithoutArguments
     */
    function testCanSetAsAbstractPostConstruct()
    {
        $func = new FunctionTemplate();
        $func->setAbstract(true);
        $this->assertTrue($func->isAbstract());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getParameters
     * @depends testCanConstructWithoutArguments
     */
    function testConstructsWithoutParameters()
    {
        $func = new FunctionTemplate();
        $this->assertCount(0, $func->getParameters());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::addParameter
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getParameters
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::hasParameter
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getParameter
     * @depends testCanConstructWithoutArguments
     */
    function testCanAddParameterWithValidName()
    {
        $func = new FunctionTemplate();
        $var = new VariableTemplate('test');
        $func->addParameter($var);
        $this->assertCount(1, $func->getParameters());
        $this->assertTrue($func->hasParameter('test'));
        $this->assertSame($var, $func->getParameter('test'));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getParameter
     * @depends testConstructsWithoutParameters
     */
    function testGettingInvalidParameterReturnsNull()
    {
        $func = new FunctionTemplate();
        $this->assertNull($func->getParameter('idonotexist'));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::addParameter
     * @depends testCanAddParameterWithValidName
     * @expectedException \DCarbone\PHPClassBuilder\Exception\MissingNameException
     */
    function testExceptionThrownWhenAddingParameterWithoutName()
    {
        $func = new FunctionTemplate();
        $func->addParameter(new VariableTemplate());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::createParameter
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::addParameter
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getParameter
     * @depends testCanAddParameterWithValidName
     */
    function testCanCreateParameter()
    {
        $func = new FunctionTemplate();
        $var = $func->createParameter('test');
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\VariableTemplate', $var);
        $this->assertSame($var, $func->getParameter('test'));
    }
}