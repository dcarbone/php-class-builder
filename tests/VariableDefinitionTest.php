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
 * Class VariableDefinitionTest
 */
class VariableDefinitionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::__construct
     * @return \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition
     */
    public function testCanConstructWithoutArguments()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Definition\\Structure\\VariableDefinition', $variable);
        return $variable;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::__construct
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::setName
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::getName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidVariableName
     * @return \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition
     */
    public function testCanConstructWithName()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition('testvar');
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Definition\\Structure\\VariableDefinition', $variable);
        $this->assertEquals('testvar', $variable->getName());
        return $variable;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::__construct
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::setName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidVariableName
     * @covers \DCarbone\PHPClassBuilder\Definition\AbstractDefinition::createInvalidVariableNameException
     * @covers \DCarbone\PHPClassBuilder\Definition\AbstractDefinition::_determineExceptionValueOutput
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidVariableNameException
     */
    public function testExceptionThrownWhenConstructingWithInvalidStringName()
    {
        new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition(' 9111lololol');
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::setName
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::getName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidVariableName
     * @depends testCanConstructWithoutArguments
     * @param \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition $variable
     */
    public function testCanSetNamePostConstruct(\DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition $variable)
    {
        $variable->setName('vartest');
        $this->assertEquals('vartest', $variable->getName());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::__construct
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::getScope
     */
    public function testCanConstructWithScope()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition(
            null,
            new \DCarbone\PHPClassBuilder\Enum\ScopeEnum(
                \DCarbone\PHPClassBuilder\Enum\ScopeEnum::_PUBLIC
            )
        );
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Definition\\Structure\\VariableDefinition', $variable);
        $scope = $variable->getScope();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Enum\\ScopeEnum', $scope);
        $this->assertEquals('public', (string)$scope);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::setScope
     * @depends testCanConstructWithoutArguments
     * @param \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition $variable
     */
    public function testCanSetScopePostConstruct(\DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition $variable)
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
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::__construct
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::requiresGetter
     */
    public function testRequiresGetterByDefault()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition();
        $this->assertTrue($variable->requiresGetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::__construct
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::requiresSetter
     */
    public function testRequiresSetterByDefault()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition();
        $this->assertTrue($variable->requiresSetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::__construct
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::requiresGetter
     */
    public function testCanOverrideRequiresGetterWithConstructor()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition(null, null, false);
        $this->assertFalse($variable->requiresGetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::__construct
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::requiresSetter
     */
    public function testCanOverrideRequiresSetterWithConstructor()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition(null, null, true, false);
        $this->assertFalse($variable->requiresSetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::setRequiresGetter
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::requiresGetter
     */
    public function testCanOverrideRequiresGetterWithSetter()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition();
        $this->assertTrue($variable->requiresGetter());
        $variable->setRequiresGetter(false);
        $this->assertFalse($variable->requiresGetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::setRequiresSetter
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::requiresSetter
     */
    public function testCanOverrideRequiresSetterWithSetter()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition();
        $this->assertTrue($variable->requiresSetter());
        $variable->setRequiresSetter(false);
        $this->assertFalse($variable->requiresSetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::isStatic
     */
    public function testNotStaticByDefault()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition();
        $this->assertFalse($variable->isStatic());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::isStatic
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::setStatic
     */
    public function testCanSetStatic()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition();
        $variable->setStatic(true);
        $this->assertTrue($variable->isStatic());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::isCollection
     */
    public function testNotCollectionByDefault()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition();
        $this->assertFalse($variable->isCollection());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::isCollection
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::setCollection
     */
    public function testCanSetCollection()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition();
        $variable->setCollection(true);
        $this->assertTrue($variable->isCollection());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::getPHPType
     */
    public function testPHPTypeMixedByDefault()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition();
        $this->assertEquals('mixed', $variable->getPHPType());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::getPHPType
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::setPHPType
     */
    public function testCanSetPHPType()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition();
        $this->assertEquals('mixed', $variable->getPHPType());
        $variable->setPHPType('string');
        $this->assertEquals('string', $variable->getPHPType());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::getDefaultValueStatement
     */
    public function testHasNoDefaultValueByStatementDefault()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition();
        $this->assertNull($variable->getDefaultValueStatement());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::setDefaultValueStatement
     * @covers \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition::getDefaultValueStatement
     */
    public function testCanSetDefaultValueStatement()
    {
        $variable = new \DCarbone\PHPClassBuilder\Definition\Structure\VariableDefinition();
        $this->assertNull($variable->getDefaultValueStatement());
        $variable->setDefaultValueStatement('array()');
        $this->assertEquals('array()', $variable->getDefaultValueStatement());
    }
}