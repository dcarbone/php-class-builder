<?php

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
 * Class VariableTemplateTest
 */
class VariableTemplateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::__construct
     * @return \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate
     */
    public function testCanConstructWithoutArguments()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\VariableTemplate', $variable);
        return $variable;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidVariableName
     * @return \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate
     */
    public function testCanConstructWithName()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate('testvar');
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\VariableTemplate', $variable);
        $this->assertEquals('testvar', $variable->getName());
        return $variable;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidVariableName
     * @covers \DCarbone\PHPClassBuilder\Template\AbstractTemplate::createInvalidVariableNameException
     * @covers \DCarbone\PHPClassBuilder\Template\AbstractTemplate::_determineExceptionValueOutput
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidVariableNameException
     */
    public function testExceptionThrownWhenConstructingWithInvalidStringName()
    {
        new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate(' 9111lololol');
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidVariableName
     * @depends testCanConstructWithoutArguments
     * @param \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate $variable
     */
    public function testCanSetNamePostConstruct(\DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate $variable)
    {
        $variable->setName('vartest');
        $this->assertEquals('vartest', $variable->getName());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getScope
     */
    public function testCanConstructWithScope()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate(
            null,
            new \DCarbone\PHPClassBuilder\Enum\ScopeEnum(
                \DCarbone\PHPClassBuilder\Enum\ScopeEnum::_PUBLIC
            )
        );
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\VariableTemplate', $variable);
        $scope = $variable->getScope();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Enum\\ScopeEnum', $scope);
        $this->assertEquals('public', (string)$scope);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setScope
     * @depends testCanConstructWithoutArguments
     * @param \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate $variable
     */
    public function testCanSetScopePostConstruct(\DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate $variable)
    {
        $variable->setScope(
            new \DCarbone\PHPClassBuilder\Enum\ScopeEnum(
                \DCarbone\PHPClassBuilder\Enum\ScopeEnum::_PRIVATE
            )
        );
        $scope = $variable->getScope();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Enum\\ScopeEnum', $scope);
        $this->assertEquals('private', (string)$scope);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::requiresGetter
     */
    public function testRequiresGetterByDefault()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate();
        $this->assertTrue($variable->requiresGetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::requiresSetter
     */
    public function testRequiresSetterByDefault()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate();
        $this->assertTrue($variable->requiresSetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::requiresGetter
     */
    public function testCanOverrideRequiresGetterWithConstructor()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate(null, null, false);
        $this->assertFalse($variable->requiresGetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::requiresSetter
     */
    public function testCanOverrideRequiresSetterWithConstructor()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate(null, null, true, false);
        $this->assertFalse($variable->requiresSetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setRequiresGetter
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::requiresGetter
     */
    public function testCanOverrideRequiresGetterWithSetter()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate();
        $this->assertTrue($variable->requiresGetter());
        $variable->setRequiresGetter(false);
        $this->assertFalse($variable->requiresGetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setRequiresSetter
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::requiresSetter
     */
    public function testCanOverrideRequiresSetterWithSetter()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate();
        $this->assertTrue($variable->requiresSetter());
        $variable->setRequiresSetter(false);
        $this->assertFalse($variable->requiresSetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::isStatic
     */
    public function testNotStaticByDefault()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate();
        $this->assertFalse($variable->isStatic());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::isStatic
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setStatic
     */
    public function testCanSetStatic()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate();
        $variable->setStatic(true);
        $this->assertTrue($variable->isStatic());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::isCollection
     */
    public function testNotCollectionByDefault()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate();
        $this->assertFalse($variable->isCollection());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::isCollection
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setCollection
     */
    public function testCanSetCollection()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate();
        $variable->setCollection(true);
        $this->assertTrue($variable->isCollection());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getPHPType
     */
    public function testPHPTypeMixedByDefault()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate();
        $this->assertEquals('mixed', $variable->getPHPType());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getPHPType
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setPHPType
     */
    public function testCanSetPHPType()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate();
        $this->assertEquals('mixed', $variable->getPHPType());
        $variable->setPHPType('string');
        $this->assertEquals('string', $variable->getPHPType());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getDefaultValueStatement
     */
    public function testHasNoDefaultValueByStatementDefault()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate();
        $this->assertNull($variable->getDefaultValueStatement());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setDefaultValueStatement
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getDefaultValueStatement
     */
    public function testCanSetDefaultValueStatement()
    {
        $variable = new \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate();
        $this->assertNull($variable->getDefaultValueStatement());
        $variable->setDefaultValueStatement('array()');
        $this->assertEquals('array()', $variable->getDefaultValueStatement());
    }
}