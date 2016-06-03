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

use DCarbone\PHPClassBuilder\Enum\CompileOpt;
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
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getClassPropertyAnnotation
     */
    public function testCanGetVariableClassPropertyAnnotation()
    {
        $variable = new VariableTemplate('testvar');
        $this->assertEquals('@var mixed', $variable->getClassPropertyAnnotation());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getFunctionParameterAnnotation
     */
    public function testCanGetVariableMethodParameterAnnotation()
    {
        $variable = new VariableTemplate('testvar');
        $this->assertEquals('@param mixed $testvar', $variable->getFunctionParameterAnnotation());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::getDefaultCompileOpts
     */
    public function testCanGetDefaultCompileOpts()
    {
        $variable = new VariableTemplate('testvar');
        $this->assertTrue(method_exists($variable, 'getDefaultCompileOpts'));
        $opts = $variable->getDefaultCompileOpts();
        $this->assertInternalType('array', $opts);
        $this->assertEquals(
            array(
                CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_VARIABLE,
                CompileOpt::INC_COMMENT => true,
                CompileOpt::LEADING_SPACES => 8,
                CompileOpt::INC_DEFAULT_VALUE => true
            ),
            $opts
        );
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::parseCompileOpts
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::_buildDocBloc
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::_compileAsVariable
     */
    public function testCanCompileAsVariable()
    {
        $variable = new VariableTemplate('testvar');

        $this->assertEquals(
            "        /**\n         * @var mixed\n         */\n        \$testvar;\n",
            $variable->compile()
        );
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::compile
     * @depends testCanCompileAsVariable
     * @expectedException \DCarbone\PHPClassBuilder\Exception\MissingNameException
     */
    public function testExceptionThrownWhenCompilingWithoutSettingName()
    {
        $variable = new VariableTemplate();
        $variable->compile();
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::parseCompileOpts
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::_compileAsVariable
     */
    public function testCanCompileAsVariableWithoutComment()
    {
        $variable = new VariableTemplate('testvar');

        $this->assertEquals(
            "        \$testvar;\n",
            $variable->compile(array(CompileOpt::INC_COMMENT => false))
        );
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::parseCompileOpts
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::_compileAsClassProperty
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::_buildDocBloc
     */
    public function testCanCompileAsClassProperty()
    {
        $variable = new VariableTemplate('testvar');

        $this->assertEquals(
            "        /**\n         * @var mixed\n         */\n        public \$testvar;\n",
            $variable->compile(array(CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY))
        );
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::parseCompileOpts
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::_compileAsClassProperty
     */
    public function testCanCompileAsStaticClassProperty()
    {
        $variable = new VariableTemplate('testvar');
        $variable->setStatic();
        $this->assertEquals(
            "        /**\n         * @var mixed\n         */\n        public static \$testvar;\n",
            $variable->compile(array(CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY))
        );
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::parseCompileOpts
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::_compileAsClassProperty
     */
    public function testCanCompileAsClassPropertyWithDefaultValue()
    {
        $variable = new VariableTemplate('testvar');
        $variable->setDefaultValueStatement('array()');
        $variable->setPHPType('array');

        $this->assertEquals(
            "        /**\n         * @var array\n         */\n        public \$testvar = array();\n",
            $variable->compile(array(CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY))
        );
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::parseCompileOpts
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::_compileAsClassProperty
     */
    public function testCanCompileAsClassPropertyWithoutComment()
    {
        $variable = new VariableTemplate('testvar');

        $this->assertEquals(
            "        public \$testvar;\n",
            $variable->compile(array(
                CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY,
                CompileOpt::INC_COMMENT => false
            ))
        );
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::parseCompileOpts
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::_compileAsClassProperty
     */
    public function testCanCompileAsClassVarWithCustomLeadingSpaces()
    {
        $variable = new VariableTemplate('testvar');

        $this->assertEquals(
            "/**\n * @var mixed\n */\npublic \$testvar;\n",
            $variable->compile(array(
                CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY,
                CompileOpt::LEADING_SPACES => 0
            ))
        );
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::parseCompileOpts
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    public function testExceptionThrownWhenPassingInvalidCompileTypeOption()
    {
        $variable = new VariableTemplate('testvar');
        $variable->compile(array(CompileOpt::COMPILE_TYPE => 'thursday'));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::parseCompileOpts
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::_compileAsClassProperty
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    public function testExceptionThrownWhenInvalidIncludeCommentArgPassed()
    {
        $variable = new VariableTemplate('testvar');
        $variable->compile(array(
            CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY,
            CompileOpt::INC_COMMENT => 'sandwiches'
        ));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::parseCompileOpts
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::_compileAsClassProperty
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    public function testExceptionThrownWhenInvalidLeadingSpacesArgPassed()
    {
        $variable = new VariableTemplate('testvar');
        $variable->compile(array(
            CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY,
            CompileOpt::LEADING_SPACES => 'sandwich'
        ));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::parseCompileOpts
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::_compileAsMethodParameter
     */
    public function testCanCompileAsMethodParameter()
    {
        $variable = new VariableTemplate('testvar');
        $this->assertEquals('$testvar', $variable->compile(array(
            CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PARAMETER
        )));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::parseCompileOpts
     */
    public function testCanIncludeDefaultValue()
    {
        $variable = new VariableTemplate('testvar');
        $variable->setDefaultValueStatement('"stringvalue"');
        $this->assertEquals(
            'public $testvar = "stringvalue";'."\n",
            $variable->compile(array(
                CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY,
                CompileOpt::INC_DEFAULT_VALUE => true,
                CompileOpt::INC_COMMENT => false,
                CompileOpt::LEADING_SPACES => 0
            ))
        );
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::parseCompileOpts
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    public function testExceptionThrownWhenPassingInvalidIncludeDefaultValueArg()
    {
        $variable = new VariableTemplate('testvar');
        $variable->compile(array(
            CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY,
            CompileOpt::INC_DEFAULT_VALUE => 'sandwiches'
        ));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate::_buildDocBloc
     */
    public function testCanManuallyDefineVarDocBlockLine()
    {
        $variable = new VariableTemplate('testvar');
        $variable->getDocBlockComment()->addLine('@var string');
        $this->assertEquals(
            "/**\n * @var string\n */\npublic \$testvar;\n",
            $variable->compile(array(
                CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY,
                CompileOpt::LEADING_SPACES => 0
            ))
        );
    }
}