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
use PHPUnit\Framework\TestCase;

/**
 * Class FunctionTemplateTest
 * @package DCarbone\PHPClassBuilder\Tests\Template\Structure
 */
class FunctionTemplateTest extends TestCase {
    /**
     * @return string
     */
    protected static function generateTestFunctionName() {
        static $i = 0;
        return sprintf('_class_builder_function_test_%d', $i++);
    }

    /**
     * @return FunctionTemplate
     */
    public function testCanConstructWithoutArguments() {
        $func = new FunctionTemplate();
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\FunctionTemplate', $func);
        return $func;
    }

    /**
     * @depends testCanConstructWithoutArguments
     * @param FunctionTemplate $func
     */
    public function testFunctionHasDefaultScopeOfPublic(FunctionTemplate $func) {
        $this->assertEquals(ScopeEnum::_PUBLIC, $func->getScope());
    }

    /**
     * @depends testCanConstructWithoutArguments
     * @param FunctionTemplate $func
     */
    public function testFunctionNotStaticByDefault(FunctionTemplate $func) {
        $this->assertFalse($func->isStatic());
    }

    /**
     * @depends testCanConstructWithoutArguments
     * @param FunctionTemplate $func
     */
    public function testFunctionNotAbstractByDefault(FunctionTemplate $func) {
        $this->assertFalse($func->isAbstract());
    }

    /**
     * @depends testCanConstructWithoutArguments
     * @return FunctionTemplate
     */
    public function testCanConstructWithName() {
        $func = new FunctionTemplate('test');
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\FunctionTemplate', $func);
        $this->assertEquals('test', $func->getName());
        return $func;
    }

    /**
     * @depends testCanConstructWithName
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidFunctionNameException
     */
    public function testExceptionThrownWhenConstructingWithInvalidStringName() {
        new FunctionTemplate('1-2-iloveyou');
    }

    /**
     * @depends testCanConstructWithName
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidFunctionNameException
     */
    public function testExceptionThrownWhenConstructingWithNonStringName() {
        new FunctionTemplate(array());
    }

    public function testCanConstructWithCustomScope() {
        $func = new FunctionTemplate(null, new ScopeEnum(ScopeEnum::_PROTECTED));
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\FunctionTemplate', $func);
        $this->assertEquals(ScopeEnum::_PROTECTED, $func->getScope());
    }

    public function testCanConstructAsStatic() {
        $func = new FunctionTemplate(null, new ScopeEnum(ScopeEnum::_PUBLIC), true);
        $this->assertTrue($func->isStatic());
    }

    public function testCanConstructAsAbstract() {
        $func = new FunctionTemplate(null, new ScopeEnum(ScopeEnum::_PUBLIC), false, true);
        $this->assertTrue($func->isAbstract());
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testCanSetNamePostConstruct() {
        $func = new FunctionTemplate();
        $func->setName('test');
        $this->assertEquals('test', $func->getName());
    }

    /**
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidFunctionNameException
     * @depends testCanSetNamePostConstruct
     */
    public function testExceptionThrownWhenSettingInvalidNameString() {
        $func = new FunctionTemplate();
        $func->setName('1234iloveyoumore');
    }

    /**
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidFunctionNameException
     * @depends testCanSetNamePostConstruct
     */
    public function testExceptionThrownWhenSettingNonStringName() {
        $func = new FunctionTemplate();
        $func->setName(array());
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testCanSetScopePostConstruct() {
        $func = new FunctionTemplate();
        $func->setScope(new ScopeEnum(ScopeEnum::_PRIVATE));
        $this->assertEquals(ScopeEnum::_PRIVATE, $func->getScope());
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testCanSetAsStaticPostConstruct() {
        $func = new FunctionTemplate();
        $func->setStatic(true);
        $this->assertTrue($func->isStatic());
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testCanSetAsAbstractPostConstruct() {
        $func = new FunctionTemplate();
        $func->setAbstract(true);
        $this->assertTrue($func->isAbstract());
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testConstructsWithoutParameters() {
        $func = new FunctionTemplate();
        $this->assertCount(0, $func->getParameters());
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testCanAddParameterWithValidName() {
        $func = new FunctionTemplate();
        $var = new VariableTemplate('test');
        $func->addParameter($var);
        $this->assertCount(1, $func->getParameters());
        $this->assertTrue($func->hasParameter('test'));
        $this->assertSame($var, $func->getParameter('test'));
    }

    /**
     * @depends testConstructsWithoutParameters
     */
    public function testGettingInvalidParameterReturnsNull() {
        $func = new FunctionTemplate();
        $this->assertNull($func->getParameter('idonotexist'));
    }

    /**
     * @depends testCanAddParameterWithValidName
     * @expectedException \DCarbone\PHPClassBuilder\Exception\MissingNameException
     */
    public function testExceptionThrownWhenAddingParameterWithoutName() {
        $func = new FunctionTemplate();
        $func->addParameter(new VariableTemplate());
    }

    /**
     * @depends testCanAddParameterWithValidName
     */
    public function testCanCreateParameter() {
        $func = new FunctionTemplate();
        $var = $func->createParameter('test');
        $this->assertInstanceOf('\\DCarbone\\PHPClassBuilder\\Template\\Structure\\VariableTemplate', $var);
        $this->assertSame($var, $func->getParameter('test'));
    }

    /**
     * @depends testCanCreateParameter
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidVariableNameException
     */
    public function testExceptionThrownWhenCreatingParameterWithInvalidNameString() {
        $func = new FunctionTemplate();
        $func->createParameter('-123doesnotloveme');
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testCanSetReturnValueType() {
        $func = new FunctionTemplate();
        $func->setReturnValueType('array');
        $this->assertEquals('array', $func->getReturnValueType());
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testCanSetReturnStatement() {
        $func = new FunctionTemplate();
        $func->setReturnStatement('$varname');
        $this->assertEquals('$varname', $func->getReturnStatement());
    }

    /**
     * @depends testCanConstructWithoutArguments
     */
    public function testFunctionBodyEmptyOnConstruct() {
        $func = new FunctionTemplate();
        $parts = $func->getBodyParts();
        $this->assertInternalType('array', $parts);
        $this->assertCount(0, $parts);
    }

    /**
     * @depends testFunctionBodyEmptyOnConstruct
     */
    public function testCanAddBodyPart() {
        $func = new FunctionTemplate();
        $func->addBodyPart('$hello = \'hello\';');
        $parts = $func->getBodyParts();
        $this->assertCount(1, $parts);
        $this->assertContains('$hello = \'hello\';', $parts);
    }

    /**
     * @depends testFunctionBodyEmptyOnConstruct
     */
    public function testCanAddMultilineBodyPart() {
        $func = new FunctionTemplate();
        $func->addBodyPart("\$hello = 'hello';\n\$world = 'world';");
        $parts = $func->getBodyParts();
        $this->assertCount(1, $parts);
        $this->assertContains("\$hello = 'hello';\n\$world = 'world';", $parts);
    }

    /**
     * @depends testFunctionBodyEmptyOnConstruct
     */
    public function testCanOverwriteBody() {
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
     * @depends testCanConstructWithoutArguments
     */
    public function testHasCorrectDefaultCompileOpts() {
        $func = new FunctionTemplate();
        $defOpts = $func->getDefaultCompileOpts();
        $this->assertInternalType('array', $defOpts);
        $this->assertCount(4, $defOpts);
        $this->assertEquals(array(
            CompileOpt::COMPILE_TYPE => FunctionTemplate::COMPILETYPE_FUNCTION,
            CompileOpt::LEADING_SPACES => 0,
            CompileOpt::INC_COMMENT => true,
            CompileOpt::INC_BODY => true
        ), $defOpts);
    }

    /**
     * @depends testHasCorrectDefaultCompileOpts
     */
    public function testCanCompileAsBareFunction() {
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
     * @depends testCanCompileAsBareFunction
     */
    public function testCanCompileAsBareFunctionWithParameters() {
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

    /**
     * @depends testCanCompileAsBareFunction
     */
    public function testCanCompileAsBareFunctionWithoutComment() {
        $funcName = self::generateTestFunctionName();
        $func = new FunctionTemplate($funcName);
        $func->addBodyPart('$hello = \'hello\';');
        $func->setReturnStatement('$hello');
        $func->setReturnValueType('string');
        $output = $func->compile(array(CompileOpt::INC_COMMENT => false));
        $this->assertEquals(<<<STRING
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
     * @depends testCanCompileAsBareFunction
     */
    public function testCanCompileAsBareFunctionWithPrecedingSpaces() {
        $funcName = self::generateTestFunctionName();
        $func = new FunctionTemplate($funcName);
        $func->addBodyPart('$hello = \'hello\';');
        $func->setReturnStatement('$hello');
        $func->setReturnValueType('string');
        $output = $func->compile(array(CompileOpt::LEADING_SPACES => 4));
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
     * @depends testCanCompileAsBareFunction
     */
    public function testCanAddCustomParamCommentAnnotation() {
        $funcName = self::generateTestFunctionName();
        $func = new FunctionTemplate($funcName);
        $func->addBodyPart('$hello = \'hello\';');
        $param = $func->createParameter('world');
        $param->setDefaultValueStatement('\'world\'');
        $param->setPHPType('string');
        $func->addBodyPart('$return = sprintf(\'%s %s\', $hello, $world);');
        $func->setReturnStatement('$return');
        $func->setReturnValueType('string');
        $func->getDocBlockComment()->addLine('@param string $world I would give...');
        $output = $func->compile();
        $this->assertEquals(<<<STRING
/**
 * @param string \$world I would give...
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

    /**
     * @depends testCanCompileAsBareFunction
     * @expectedException \DCarbone\PHPClassBuilder\Exception\MissingNameException
     */
    public function testExceptionThrownWhenCompilingWithoutSettingName() {
        $func = new FunctionTemplate();
        $func->compile();
    }

    /**
     * @depends testCanCompileAsBareFunction
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    public function testExceptionThrownWhenCompilingWithInvalidIncludeCommentValue() {
        $func = new FunctionTemplate('asdf');
        $func->compile(array(CompileOpt::INC_COMMENT => 'sandwiches'));
    }

    /**
     * @depends testCanCompileAsBareFunction
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    public function testExceptionThrownWhenCompilingWithInvalidLeadingSpacesValue() {
        $func = new FunctionTemplate('asdfw');
        $func->compile(array(CompileOpt::LEADING_SPACES => 'sehciwdnas'));
    }

    /**
     * @depends testCanCompileAsBareFunction
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    public function testExceptionThrownWhenCompilingWithInvalidCompileType() {
        $func = new FunctionTemplate('asfasf');
        $func->compile(array(CompileOpt::COMPILE_TYPE => 'noooooooope'));
    }

    /**
     * @depends testCanCompileAsBareFunction
     */
    public function testCanCreateNonReturningFunction() {
        $funcName = self::generateTestFunctionName();
        $func = new FunctionTemplate($funcName);
        $func->addBodyPart('echo \'woot!\';');
        $output = $func->compile();
        $this->assertEquals(<<<PHP
function {$funcName}()
{
    echo 'woot!';

}


PHP
            , $output);
    }

    /**
     * @depends testCanCompileAsBareFunction
     */
    public function testCanCompileAsClassMethod() {
        $funcName = self::generateTestFunctionName();
        $func = new FunctionTemplate($funcName);
        $func->addBodyPart('echo \'woot!\';');
        $output = $func->compile(array(CompileOpt::COMPILE_TYPE => FunctionTemplate::COMPILETYPE_CLASSMETHOD));
        $this->assertEquals(<<<PHP
public function {$funcName}()
{
    echo 'woot!';

}


PHP
            , $output);
    }

    /**
     * @depends testCanCompileAsClassMethod
     */
    public function testCanCompileAsAbstractClassMethod() {
        $funcName = self::generateTestFunctionName();
        $func = new FunctionTemplate($funcName);
        $func->addBodyPart('echo \'woot!\';');
        $func->setAbstract(true);
        $output = $func->compile(array(CompileOpt::COMPILE_TYPE => FunctionTemplate::COMPILETYPE_CLASSMETHOD));
        $this->assertEquals(<<<PHP
abstract public function {$funcName}();


PHP
            , $output);
    }

    /**
     * @depends testCanCompileAsClassMethod
     */
    public function testCanCompileAsStaticMethod() {
        $funcName = self::generateTestFunctionName();
        $func = new FunctionTemplate($funcName);
        $func->addBodyPart('echo \'woot!\';');
        $func->setStatic(true);
        $output = $func->compile(array(CompileOpt::COMPILE_TYPE => FunctionTemplate::COMPILETYPE_CLASSMETHOD));
        $this->assertEquals(<<<PHP
static public function {$funcName}()
{
    echo 'woot!';

}


PHP
            , $output);
    }

    /**
     * @depends testCanCompileAsClassMethod
     */
    public function testCanCompileAsClassMethodWithAppropriateDefaultSpacing() {
        $funcName = self::generateTestFunctionName();
        $func = new FunctionTemplate($funcName);
        $func->addBodyPart('echo \'woot!\';');
        $output = $func->compile(array(
            CompileOpt::COMPILE_TYPE => FunctionTemplate::COMPILETYPE_CLASSMETHOD,
            CompileOpt::LEADING_SPACES => 4,
            CompileOpt::INC_COMMENT => false,
        ));
        $this->assertEquals(<<<PHP
    public function {$funcName}()
    {
        echo 'woot!';

    }


PHP
            , $output);
    }

    /**
     * @depends testCanCompileAsBareFunction
     */
    public function testCanOutputFunctionWithoutBody() {
        $funcName = self::generateTestFunctionName();
        $func = new FunctionTemplate($funcName);
        $func->addBodyPart('echo \'woot!\';');
        $output = $func->compile(array(
            CompileOpt::INC_BODY => false
        ));
        $this->assertEquals(<<<PHP
function {$funcName}();


PHP
            , $output);
    }

    /**
     * @expectedException \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     * @depends testCanOutputFunctionWithoutBody
     */
    public function testExceptionThrownWhenUsingInvalidIncludeBodyCompileOption() {
        $funcName = self::generateTestFunctionName();
        $func = new FunctionTemplate($funcName);
        $func->compile(array(CompileOpt::INC_BODY => 'woot!'));
    }
}