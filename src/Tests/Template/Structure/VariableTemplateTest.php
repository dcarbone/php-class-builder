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
use DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate;

/**
 * Class VariableTemplateTest
 */
class VariableTemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::__construct
     * @return VariableTemplate
     */
    public function testCanConstructWithoutArguments()
    {
        $variable = new VariableTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\VariableTemplate', $variable);
        return $variable;
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidVariableName
     * @return VariableTemplate
     */
    public function testCanConstructWithName()
    {
        $variable = new VariableTemplate('testvar');
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
        new VariableTemplate(' 9111lololol');
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setName
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getName
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidVariableName
     * @depends testCanConstructWithoutArguments
     * @param VariableTemplate $variable
     */
    public function testCanSetNamePostConstruct(VariableTemplate $variable)
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
        $variable = new VariableTemplate(
            null,
            new ScopeEnum(
                ScopeEnum::_PUBLIC
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
     * @param VariableTemplate $variable
     */
    public function testCanSetScopePostConstruct(VariableTemplate $variable)
    {
        $variable->setScope(new ScopeEnum(ScopeEnum::_PRIVATE));
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
        $variable = new VariableTemplate();
        $this->assertTrue($variable->requiresGetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::requiresSetter
     */
    public function testRequiresSetterByDefault()
    {
        $variable = new VariableTemplate();
        $this->assertTrue($variable->requiresSetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::requiresGetter
     */
    public function testCanOverrideRequiresGetterWithConstructor()
    {
        $variable = new VariableTemplate(null, null, false);
        $this->assertFalse($variable->requiresGetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::requiresSetter
     */
    public function testCanOverrideRequiresSetterWithConstructor()
    {
        $variable = new VariableTemplate(null, null, true, false);
        $this->assertFalse($variable->requiresSetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setRequiresGetter
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::requiresGetter
     */
    public function testCanOverrideRequiresGetterWithSetter()
    {
        $variable = new VariableTemplate();
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
        $variable = new VariableTemplate();
        $this->assertTrue($variable->requiresSetter());
        $variable->setRequiresSetter(false);
        $this->assertFalse($variable->requiresSetter());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::isStatic
     */
    public function testNotStaticByDefault()
    {
        $variable = new VariableTemplate();
        $this->assertFalse($variable->isStatic());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::isStatic
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setStatic
     */
    public function testCanSetStatic()
    {
        $variable = new VariableTemplate();
        $variable->setStatic(true);
        $this->assertTrue($variable->isStatic());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::isCollection
     */
    public function testNotCollectionByDefault()
    {
        $variable = new VariableTemplate();
        $this->assertFalse($variable->isCollection());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::isCollection
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setCollection
     */
    public function testCanSetCollection()
    {
        $variable = new VariableTemplate();
        $variable->setCollection(true);
        $this->assertTrue($variable->isCollection());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getPHPType
     */
    public function testPHPTypeMixedByDefault()
    {
        $variable = new VariableTemplate();
        $this->assertEquals('mixed', $variable->getPHPType());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getPHPType
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setPHPType
     */
    public function testCanSetPHPType()
    {
        $variable = new VariableTemplate();
        $this->assertEquals('mixed', $variable->getPHPType());
        $variable->setPHPType('string');
        $this->assertEquals('string', $variable->getPHPType());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getDefaultValueStatement
     */
    public function testHasNoDefaultValueByStatementDefault()
    {
        $variable = new VariableTemplate();
        $this->assertNull($variable->getDefaultValueStatement());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::setDefaultValueStatement
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getDefaultValueStatement
     */
    public function testCanSetDefaultValueStatement()
    {
        $variable = new VariableTemplate();
        $this->assertNull($variable->getDefaultValueStatement());
        $variable->setDefaultValueStatement('array()');
        $this->assertEquals('array()', $variable->getDefaultValueStatement());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getClassPropertyComment
     */
    public function testCanGetClassPropertyComment()
    {
        $variable = new VariableTemplate('testvar');
        $comment = $variable->getClassPropertyComment();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Comment\\DoubleStarCommentTemplate', $comment);
        $this->assertEquals("    /**\n     * @var mixed\n     */\n", $comment->compile());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getMethodParameterComment
     */
    public function testCanGetMethodParameterComment()
    {
        $variable = new VariableTemplate('testvar');
        $comment = $variable->getMethodParameterComment();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Comment\\DoubleStarCommentTemplate', $comment);
        $this->assertEquals("    /**\n     * @param mixed \$testvar\n     */\n", $comment->compile());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getClassPropertyComment
     */
    public function testCanGetClassPropertyAnnotation()
    {
        $variable = new VariableTemplate('testvar');
        $annotation = $variable->getClassPropertyComment(true);
        $this->assertInternalType('string', $annotation);
        $this->assertEquals('@var mixed', $annotation);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getMethodParameterComment
     */
    public function testCanGetMethodParameterAnnotation()
    {
        $variable = new VariableTemplate('testvar');
        $annotation = $variable->getMethodParameterComment(true);
        $this->assertInternalType('string', $annotation);
        $this->assertEquals('@param mixed $testvar', $annotation);
    }
}