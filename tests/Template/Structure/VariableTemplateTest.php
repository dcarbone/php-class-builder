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
use PHPUnit\Framework\TestCase;

/**
 * Class VariableTemplateTest
 */
class VariableTemplateTest extends TestCase {
    /**
     * @return VariableTemplate
     */
    public function testCanConstructWithoutArguments() {
        $variable = new VariableTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\VariableTemplate', $variable);
        return $variable;
    }

    /**
     * @return VariableTemplate
     */
    public function testCanConstructWithName() {
        $variable = new VariableTemplate('testvar');
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\VariableTemplate', $variable);
        $this->assertEquals('testvar', $variable->getName());
        return $variable;
    }

    /**
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidVariableNameException
     */
    public function testExceptionThrownWhenConstructingWithInvalidStringName() {
        new VariableTemplate(' 9111lololol');
    }

    /**
     * @depends testCanConstructWithoutArguments
     * @param VariableTemplate $variable
     */
    public function testCanSetNamePostConstruct(VariableTemplate $variable) {
        $variable->setName('vartest');
        $this->assertEquals('vartest', $variable->getName());
    }

    public function testCanConstructWithScope() {
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
     * @depends testCanConstructWithoutArguments
     * @param VariableTemplate $variable
     */
    public function testCanSetScopePostConstruct(VariableTemplate $variable) {
        $variable->setScope(new ScopeEnum(ScopeEnum::_PRIVATE));
        $scope = $variable->getScope();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Enum\\ScopeEnum', $scope);
        $this->assertEquals('private', (string)$scope);
    }

    public function testRequiresGetterByDefault() {
        $variable = new VariableTemplate();
        $this->assertTrue($variable->requiresGetter());
    }

    public function testRequiresSetterByDefault() {
        $variable = new VariableTemplate();
        $this->assertTrue($variable->requiresSetter());
    }

    public function testCanOverrideRequiresGetterWithConstructor() {
        $variable = new VariableTemplate(null, null, false);
        $this->assertFalse($variable->requiresGetter());
    }

    public function testCanOverrideRequiresSetterWithConstructor() {
        $variable = new VariableTemplate(null, null, true, false);
        $this->assertFalse($variable->requiresSetter());
    }

    public function testCanOverrideRequiresGetterWithSetter() {
        $variable = new VariableTemplate();
        $this->assertTrue($variable->requiresGetter());
        $variable->setRequiresGetter(false);
        $this->assertFalse($variable->requiresGetter());
    }

    public function testCanOverrideRequiresSetterWithSetter() {
        $variable = new VariableTemplate();
        $this->assertTrue($variable->requiresSetter());
        $variable->setRequiresSetter(false);
        $this->assertFalse($variable->requiresSetter());
    }

    public function testNotStaticByDefault() {
        $variable = new VariableTemplate();
        $this->assertFalse($variable->isStatic());
    }

    public function testCanSetStatic() {
        $variable = new VariableTemplate();
        $variable->setStatic(true);
        $this->assertTrue($variable->isStatic());
    }

    public function testNotCollectionByDefault() {
        $variable = new VariableTemplate();
        $this->assertFalse($variable->isCollection());
    }

    public function testCanSetCollection() {
        $variable = new VariableTemplate();
        $variable->setCollection(true);
        $this->assertTrue($variable->isCollection());
    }

    public function testPHPTypeMixedByDefault() {
        $variable = new VariableTemplate();
        $this->assertEquals('mixed', $variable->getPHPType());
    }

    public function testCanSetPHPType() {
        $variable = new VariableTemplate();
        $this->assertEquals('mixed', $variable->getPHPType());
        $variable->setPHPType('string');
        $this->assertEquals('string', $variable->getPHPType());
    }

    public function testHasNoDefaultValueByStatementDefault() {
        $variable = new VariableTemplate();
        $this->assertNull($variable->getDefaultValueStatement());
    }

    public function testCanSetDefaultValueStatement() {
        $variable = new VariableTemplate();
        $this->assertNull($variable->getDefaultValueStatement());
        $variable->setDefaultValueStatement('array()');
        $this->assertEquals('array()', $variable->getDefaultValueStatement());
    }

    public function testCanGetVariableClassPropertyAnnotation() {
        $variable = new VariableTemplate('testvar');
        $this->assertEquals('@var mixed', $variable->getClassPropertyAnnotation());
    }

    public function testCanGetVariableMethodParameterAnnotation() {
        $variable = new VariableTemplate('testvar');
        $this->assertEquals('@param mixed $testvar', $variable->getFunctionParameterAnnotation());
    }

    public function testCanGetDefaultCompileOpts() {
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

    public function testCanCompileAsVariable() {
        $variable = new VariableTemplate('testvar');

        $this->assertEquals(
            "        /**\n         * @var mixed\n         */\n        \$testvar;\n",
            $variable->compile()
        );
    }

    /**
     * @depends testCanCompileAsVariable
     * @expectedException \DCarbone\PHPClassBuilder\Exception\MissingNameException
     */
    public function testExceptionThrownWhenCompilingWithoutSettingName() {
        $variable = new VariableTemplate();
        $variable->compile();
    }

    public function testCanCompileAsVariableWithoutComment() {
        $variable = new VariableTemplate('testvar');

        $this->assertEquals(
            "        \$testvar;\n",
            $variable->compile(array(CompileOpt::INC_COMMENT => false))
        );
    }

    public function testCanCompileAsClassProperty() {
        $variable = new VariableTemplate('testvar');

        $this->assertEquals(
            "        /**\n         * @var mixed\n         */\n        public \$testvar;\n",
            $variable->compile(array(CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY))
        );
    }

    public function testCanCompileAsStaticClassProperty() {
        $variable = new VariableTemplate('testvar');
        $variable->setStatic();
        $this->assertEquals(
            "        /**\n         * @var mixed\n         */\n        public static \$testvar;\n",
            $variable->compile(array(CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY))
        );
    }

    public function testCanCompileAsClassPropertyWithDefaultValue() {
        $variable = new VariableTemplate('testvar');
        $variable->setDefaultValueStatement('array()');
        $variable->setPHPType('array');

        $this->assertEquals(
            "        /**\n         * @var array\n         */\n        public \$testvar = array();\n",
            $variable->compile(array(CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY))
        );
    }

    public function testCanCompileAsClassPropertyWithoutComment() {
        $variable = new VariableTemplate('testvar');

        $this->assertEquals(
            "        public \$testvar;\n",
            $variable->compile(array(
                CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY,
                CompileOpt::INC_COMMENT => false
            ))
        );
    }

    public function testCanCompileAsClassVarWithCustomLeadingSpaces() {
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
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    public function testExceptionThrownWhenPassingInvalidCompileTypeOption() {
        $variable = new VariableTemplate('testvar');
        $variable->compile(array(CompileOpt::COMPILE_TYPE => 'thursday'));
    }

    /**
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    public function testExceptionThrownWhenInvalidIncludeCommentArgPassed() {
        $variable = new VariableTemplate('testvar');
        $variable->compile(array(
            CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY,
            CompileOpt::INC_COMMENT => 'sandwiches'
        ));
    }

    /**
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    public function testExceptionThrownWhenInvalidLeadingSpacesArgPassed() {
        $variable = new VariableTemplate('testvar');
        $variable->compile(array(
            CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY,
            CompileOpt::LEADING_SPACES => 'sandwich'
        ));
    }

    public function testCanCompileAsMethodParameter() {
        $variable = new VariableTemplate('testvar');
        $this->assertEquals('$testvar', $variable->compile(array(
            CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PARAMETER
        )));
    }

    public function testCanIncludeDefaultValue() {
        $variable = new VariableTemplate('testvar');
        $variable->setDefaultValueStatement('"stringvalue"');
        $this->assertEquals(
            'public $testvar = "stringvalue";' . "\n",
            $variable->compile(array(
                CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY,
                CompileOpt::INC_DEFAULT_VALUE => true,
                CompileOpt::INC_COMMENT => false,
                CompileOpt::LEADING_SPACES => 0
            ))
        );
    }

    /**
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    public function testExceptionThrownWhenPassingInvalidIncludeDefaultValueArg() {
        $variable = new VariableTemplate('testvar');
        $variable->compile(array(
            CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PROPERTY,
            CompileOpt::INC_DEFAULT_VALUE => 'sandwiches'
        ));
    }

    public function testCanManuallyDefineVarDocBlockLine() {
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