<?php namespace DCarbone\PHPClassBuilder\Tests\Utilities;

use DCarbone\PHPClassBuilder\Utilities\NameUtils;

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
 * Class NameUtilsTest
 * @package DCarbone\PHPClassBuilder\Tests\Utilities
 */
class NameUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidVariableName
     */
    public function testReturnTrueWithValidVariableNames()
    {
        $this->assertTrue(NameUtils::isValidVariableName('varname'));
        $this->assertTrue(NameUtils::isValidVariableName('VARNAME'));
        $this->assertTrue(NameUtils::isValidVariableName('var_name'));
        $this->assertTrue(NameUtils::isValidVariableName('VarName'));
        $this->assertTrue(NameUtils::isValidVariableName('varName'));
        $this->assertTrue(NameUtils::isValidVariableName('_varname'));
        $this->assertTrue(NameUtils::isValidVariableName('_varName'));
        $this->assertTrue(NameUtils::isValidVariableName('_var_name'));
        $this->assertTrue(NameUtils::isValidVariableName('var2Name'));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidVariableName
     */
    public function testReturnFalseWithInvalidVariableNames()
    {
        $this->assertFalse(NameUtils::isValidVariableName('91notvalid'));
        $this->assertFalse(NameUtils::isValidVariableName('nope nope'));
        $this->assertFalse(NameUtils::isValidVariableName(''));
        $this->assertFalse(NameUtils::isValidVariableName(true));
        $this->assertFalse(NameUtils::isValidVariableName(null));
        $this->assertFalse(NameUtils::isValidVariableName(12345));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidFunctionName
     */
    public function testReturnTrueWithValidFunctionNames()
    {
        $this->assertTrue(NameUtils::isValidFunctionName('getshorty'));
        $this->assertTrue(NameUtils::isValidFunctionName('getShorty'));
        $this->assertTrue(NameUtils::isValidFunctionName('GetShorty'));
        $this->assertTrue(NameUtils::isValidFunctionName('get_shorty'));
        $this->assertTrue(NameUtils::isValidFunctionName('_getShorty'));
        $this->assertTrue(NameUtils::isValidFunctionName('_get_shorty'));
        $this->assertTrue(NameUtils::isValidFunctionName('__getShorty'));
        $this->assertTrue(NameUtils::isValidFunctionName('Face2Space'));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidFunctionName
     */
    public function testReturnFalseWithInvalidFunctionNames()
    {
        $this->assertFalse(NameUtils::isValidFunctionName('91notvalid'));
        $this->assertFalse(NameUtils::isValidFunctionName('nope nope'));
        $this->assertFalse(NameUtils::isValidFunctionName(''));
        $this->assertFalse(NameUtils::isValidFunctionName(true));
        $this->assertFalse(NameUtils::isValidFunctionName(null));
        $this->assertFalse(NameUtils::isValidFunctionName(12345));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidClassName
     */
    public function testReturnTrueWithValidClassNames()
    {
        $this->assertTrue(NameUtils::isValidClassName('MyAwesomeClass'));
        $this->assertTrue(NameUtils::isValidClassName('myawesomeclass'));
        $this->assertTrue(NameUtils::isValidClassName('my_awesome_class'));
        $this->assertTrue(NameUtils::isValidClassName('_my_awesome_class'));
        $this->assertTrue(NameUtils::isValidClassName('my2awesome3class4'));
    }

    /**
     * @covers \DCarbone\PHPClassBuilder\Utilities\NameUtils::isValidClassName
     */
    public function testReturnFalseWithInvalidClassNames()
    {
        $this->assertFalse(NameUtils::isValidClassName('91notvalid'));
        $this->assertFalse(NameUtils::isValidClassName('nope nope'));
        $this->assertFalse(NameUtils::isValidClassName(''));
        $this->assertFalse(NameUtils::isValidClassName(true));
        $this->assertFalse(NameUtils::isValidClassName(null));
        $this->assertFalse(NameUtils::isValidClassName(12345));
    }
}