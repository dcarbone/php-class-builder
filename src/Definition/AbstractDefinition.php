<?php namespace DCarbone\PHPClassBuilder\Definition;

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

use DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition;
use DCarbone\PHPClassBuilder\Exception\InvalidClassNameException;
use DCarbone\PHPClassBuilder\Exception\InvalidCompileArgumentValueException;
use DCarbone\PHPClassBuilder\Exception\InvalidMethodNameException;
use DCarbone\PHPClassBuilder\Exception\InvalidNamespaceNameException;
use DCarbone\PHPClassBuilder\Exception\InvalidVariableNameException;

/**
 * Class AbstractDefinition
 * @package DCarbone\PHPClassBuilder\Definition
 */
abstract class AbstractDefinition
{
    /**
     * TODO: This method of passing arguments is flawed and could become cumbersome quickly.
     *
     * @param array $args
     * @return string
     */
    abstract public function compile(array $args = array());

    /**
     * @param array $args
     * @return array
     */
    abstract protected function parseCompileArgs(array $args);

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->compile();
    }

    /**
     * @param mixed $name
     * @return InvalidClassNameException
     */
    protected function createInvalidClassNameException($name)
    {
        return new InvalidClassNameException(sprintf(
            '%s - Specified class name "%s" is not valid.  Please see http://php.net/manual/en/language.oop5.basic.php for more information.',
            get_class($this),
            $this->_determineExceptionValueOutput($name)
        ));
    }

    /**
     * @param mixed $namespace
     * @return InvalidNamespaceNameException
     */
    protected function createInvalidNamespaceNameException($namespace)
    {
        return new InvalidNamespaceNameException(sprintf(
            '%s - Specified namespace "%s" is not valid.  Please see http://php.net/manual/en/language.oop5.basic.php for more information',
            get_class($this),
            $this->_determineExceptionValueOutput($namespace)
        ));
    }

    /**
     * @param mixed $name
     * @return InvalidMethodNameException
     */
    protected function createInvalidMethodNameException($name)
    {
        return new InvalidMethodNameException(sprintf(
            '%s - Specified method name "%s" is not valid.  Please see http://php.net/manual/en/language.oop5.basic.php for more information',
            get_class($this),
            $this->_determineExceptionValueOutput($name)
        ));
    }

    /**
     * @param mixed $name
     * @return InvalidMethodNameException
     */
    protected function createInvalidVariableNameException($name)
    {
        return new InvalidVariableNameException(sprintf(
            '%s - Specified variable name "%s" is not valid.  Please see http://php.net/manual/en/language.oop5.basic.php for more information',
            get_class($this),
            $this->_determineExceptionValueOutput($name)
        ));
    }

    /**
     * @param string $arg
     * @param string $expectedStatement
     * @param mixed $actualValue
     * @return InvalidCompileArgumentValueException
     */
    protected function createInvalidCompileArgumentValueException($arg, $expectedStatement, $actualValue)
    {
        return new InvalidCompileArgumentValueException(sprintf(
            '%s - Specified invalid value "%s" for compile argument "%s". Expected: %s',
            get_class($this),
            $this->_determineExceptionValueOutput($actualValue),
            $arg,
            $expectedStatement
        ));
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function _determineExceptionValueOutput($value)
    {
        switch(gettype($value))
        {
            case 'integer':
            case 'double':
            case 'string':
                return $value;

            case 'boolean':
                return $value ? '(boolean)TRUE' : '(boolean)FALSE';

            case 'null':
                return 'NULL';

            case 'array':
                return 'array';

            case 'object':
                return get_class($value);

            case 'resource':
                return get_resource_type($value);

            default:
                return 'UNKNOWN';
        }
    }
}