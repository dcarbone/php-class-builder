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
use DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate;
use DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate;

/**
 * Class FunctionTemplateTest
 * @package DCarbone\PHPClassBuilder\Tests\Template\Structure
 */
class FunctionTemplateTest extends \PHPUnit_Framework_TestCase
{
    protected static function generateTestFunctionName()
    {
        static $i = 0;
        return sprintf('_class_builder_function_test_%d', $i++);
    }

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
    public function testFunctionHasDefaultScopeOfPublic(FunctionTemplate $func)
    {
        $this->assertEquals(ScopeEnum::_PUBLIC, $func->getScope());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::isStatic
     * @depends testCanConstructWithoutArguments
     * @param FunctionTemplate $func
     */
    public function testFunctionNotStaticByDefault(FunctionTemplate $func)
    {
        $this->assertFalse($func->isStatic());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::isAbstract
     * @depends testCanConstructWithoutArguments
     * @param FunctionTemplate $func
     */
    public function testFunctionNotAbstractByDefault(FunctionTemplate $func)
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
    public function testExceptionThrownWhenConstructingWithInvalidStringName()
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
    public function testExceptionThrownWhenConstructingWithNonStringName()
    {
        new FunctionTemplate(array());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getScope
     */
    public function testCanConstructWithCustomScope()
    {
        $func = new FunctionTemplate(null, new ScopeEnum(ScopeEnum::_PROTECTED));
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\FunctionTemplate', $func);
        $this->assertEquals(ScopeEnum::_PROTECTED, $func->getScope());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::isStatic
     */
    public function testCanConstructAsStatic()
    {
        $func = new FunctionTemplate(null, new ScopeEnum(ScopeEnum::_PUBLIC), true);
        $this->assertTrue($func->isStatic());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::__construct
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::isAbstract
     */
    public function testCanConstructAsAbstract()
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
    public function testCanSetNamePostConstruct()
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
    public function testExceptionThrownWhenSettingInvalidNameString()
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
    public function testExceptionThrownWhenSettingNonStringName()
    {
        $func = new FunctionTemplate();
        $func->setName(array());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::setScope
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getScope
     * @depends testCanConstructWithoutArguments
     */
    public function testCanSetScopePostConstruct()
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
    public function testCanSetAsStaticPostConstruct()
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
    public function testCanSetAsAbstractPostConstruct()
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
    public function testConstructsWithoutParameters()
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
    public function testCanAddParameterWithValidName()
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
    public function testGettingInvalidParameterReturnsNull()
    {
        $func = new FunctionTemplate();
        $this->assertNull($func->getParameter('idonotexist'));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::addParameter
     * @depends testCanAddParameterWithValidName
     * @expectedException \DCarbone\PHPClassBuilder\Exception\MissingNameException
     */
    public function testExceptionThrownWhenAddingParameterWithoutName()
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
    public function testCanCreateParameter()
    {
        $func = new FunctionTemplate();
        $var = $func->createParameter('test');
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\VariableTemplate', $var);
        $this->assertSame($var, $func->getParameter('test'));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::createParameter
     * @depends testCanCreateParameter
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidVariableNameException
     */
    public function testExceptionThrownWhenCreatingParameterWithInvalidNameString()
    {
        $func = new FunctionTemplate();
        $func->createParameter('-123doesnotloveme');
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::setReturnValueType
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getReturnValueType
     * @depends testCanConstructWithoutArguments
     */
    public function testCanSetReturnValueType()
    {
        $func = new FunctionTemplate();
        $func->setReturnValueType('array');
        $this->assertEquals('array', $func->getReturnValueType());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::setReturnStatement
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getReturnStatement
     * @depends testCanConstructWithoutArguments
     */
    public function testCanSetReturnStatement()
    {
        $func = new FunctionTemplate();
        $func->setReturnStatement('$varname');
        $this->assertEquals('$varname', $func->getReturnStatement());
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getBodyParts
     * @depends testCanConstructWithoutArguments
     */
    public function testFunctionBodyEmptyOnConstruct()
    {
        $func = new FunctionTemplate();
        $parts = $func->getBodyParts();
        $this->assertInternalType('array', $parts);
        $this->assertCount(0, $parts);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::addBodyPart
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getBodyParts
     * @depends testFunctionBodyEmptyOnConstruct
     */
    public function testCanAddBodyPart()
    {
        $func = new FunctionTemplate();
        $func->addBodyPart('$hello = \'hello\';');
        $parts = $func->getBodyParts();
        $this->assertCount(1, $parts);
        $this->assertContains('$hello = \'hello\';', $parts);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::addBodyPart
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getBodyParts
     * @depends testFunctionBodyEmptyOnConstruct
     */
    public function testCanAddMultilineBodyPart()
    {
        $func = new FunctionTemplate();
        $func->addBodyPart("\$hello = 'hello';\n\$world = 'world';");
        $parts = $func->getBodyParts();
        $this->assertCount(1, $parts);
        $this->assertContains("\$hello = 'hello';\n\$world = 'world';", $parts);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::addBodyPart
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::setBodyParts
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getBodyParts
     * @depends testFunctionBodyEmptyOnConstruct
     */
    public function testCanOverwriteBody()
    {
        $func = new FunctionTemplate();
        $func->addBodyPart('$hello = \'hello\';');
        $parts = $func->getBodyParts();
        $this->assertCount(1, $parts);
        $func->setBodyParts(array('$world = \'world\';'));
        $parts = $func->getBodyParts();
        $this->assertCount(1, $parts);
        $this->assertContains('$world = \'world\';', $parts);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::getDefaultCompileOpts
     * @depends testCanConstructWithoutArguments
     */
    public function testHasCorrectDefaultCompileOpts()
    {
        $func = new FunctionTemplate();
        $defOpts = $func->getDefaultCompileOpts();
        $this->assertInternalType('array', $defOpts);
        $this->assertCount(3, $defOpts);
        $this->assertEquals(array(
            CompileOpt::COMPILE_TYPE => FunctionTemplate::COMPILETYPE_FUNCTION,
            CompileOpt::LEADING_SPACES => 0,
            CompileOpt::INC_COMMENT => true
        ), $defOpts);
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::_compileAsFunction
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::_buildDocBloc
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::_buildParameters
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::_buildBody
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::_buildReturnStatement
     * @depends testHasCorrectDefaultCompileOpts
     */
    public function testCanCompileAsBareFunction()
    {
        $funcName = self::generateTestFunctionName();
        $func = new FunctionTemplate($funcName);
        $func->addBodyPart('$hello = \'hello\';');
        $func->setReturnStatement('$hello');
        $func->setReturnValueType('string');
        $output = $func->compile();
        $this->assertEquals(<<<STRING
/**
 * @return string
 */
function {$funcName}()
{
    \$hello = 'hello';
    return \$hello;

}


STRING
            ,
            $output);
        $this->assertEquals('hello', eval(sprintf('%sreturn %s();', $output, $funcName)));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::compile
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::_compileAsFunction
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::_buildDocBloc
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::_buildParameters
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::_buildBody
     * @covers \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate::_buildReturnStatement
     * @depends testCanCompileAsBareFunction
     */
    public function testCanCompileAsBareFunctionWithParameters()
    {
        $funcName = self::generateTestFunctionName();
        $func = new FunctionTemplate($funcName);
        $func->addBodyPart('$hello = \'hello\';');
        $param = $func->createParameter('world');
        $param->setDefaultValueStatement('\'world\'');
        $param->setPHPType('string');
        $func->addBodyPart('$return = sprintf(\'%s %s\', $hello, $world);');
        $func->setReturnStatement('$return');
        $func->setReturnValueType('string');
        $output = $func->compile();
        $this->assertEquals(<<<STRING
/**
 * @param string \$world
 * @return string
 */
function {$funcName}(\$world = 'world')
{
    \$hello = 'hello';
    \$return = sprintf('%s %s', \$hello, \$world);
    return \$return;

}


STRING
            ,
            $output);
        $this->assertEquals('hello world', eval(sprintf('%sreturn %s();', $output, $funcName)));
    }
}