<?php namespace DCarbone\PHPClassBuilder\Template;

/*
 * Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)
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

use DCarbone\PHPClassBuilder\Exception\CommentLineIndexNotFoundException;
use DCarbone\PHPClassBuilder\Exception\FilePartNotFoundException;
use DCarbone\PHPClassBuilder\Exception\InvalidClassNameException;
use DCarbone\PHPClassBuilder\Exception\InvalidCommentLineArgumentException;
use DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException;
use DCarbone\PHPClassBuilder\Exception\InvalidFilePartException;
use DCarbone\PHPClassBuilder\Exception\InvalidFunctionBodyPartArgumentException;
use DCarbone\PHPClassBuilder\Exception\InvalidFunctionNameException;
use DCarbone\PHPClassBuilder\Exception\InvalidInterfaceFunctionScopeException;
use DCarbone\PHPClassBuilder\Exception\InvalidInterfaceNameException;
use DCarbone\PHPClassBuilder\Exception\InvalidInterfaceParentArgumentException;
use DCarbone\PHPClassBuilder\Exception\InvalidNamespaceNameException;
use DCarbone\PHPClassBuilder\Exception\InvalidOutputPathException;
use DCarbone\PHPClassBuilder\Exception\InvalidVariableNameException;
use DCarbone\PHPClassBuilder\Exception\MissingNameException;
use DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate;

/**
 * Class AbstractTemplate
 * @package DCarbone\PHPClassBuilder\Template
 */
abstract class AbstractTemplate implements TemplateInterface {

    /**
     * TODO: All templates MUST be able to compile with no options
     *
     * @return string
     */
    public function __toString() {
        return $this->compile();
    }

    /**
     * @param mixed $name
     * @return InvalidClassNameException
     */
    protected function createInvalidClassNameException($name) {
        return new InvalidClassNameException(sprintf(
            '%s - Specified class name "%s" is not valid.  Please see http://php.net/manual/en/language.oop5.basic.php for more information.',
            get_class($this),
            $this->_determineExceptionValueOutput($name)
        ));
    }

    /**
     * @param mixed $name
     * @return InvalidInterfaceNameException
     */
    protected function createInvalidInterfaceNameException($name) {
        return new InvalidInterfaceNameException(sprintf(
            '%s - Specified interface name "%s" is not valid.  Please see http://php.net/manual/en/language.oop5.basic.php for more information.',
            get_class($this),
            $this->_determineExceptionValueOutput($name)
        ));
    }

    /**
     * @param mixed $namespace
     * @return InvalidNamespaceNameException
     */
    protected function createInvalidNamespaceNameException($namespace) {
        return new InvalidNamespaceNameException(sprintf(
            '%s - Specified namespace "%s" is not valid.  Please see http://php.net/manual/en/language.oop5.basic.php for more information',
            get_class($this),
            $this->_determineExceptionValueOutput($namespace)
        ));
    }

    /**
     * @param string $name
     * @return \DCarbone\PHPClassBuilder\Exception\InvalidFunctionNameException
     */
    protected function createInvalidFunctionNameException($name) {
        return new InvalidFunctionNameException(sprintf(
            '%s - Specified method name "%s" is not valid.  Please see http://php.net/manual/en/language.oop5.basic.php for more information',
            get_class($this),
            $this->_determineExceptionValueOutput($name)
        ));
    }

    /**
     * @param string $name
     * @return \DCarbone\PHPClassBuilder\Exception\InvalidVariableNameException
     */
    protected function createInvalidVariableNameException($name) {
        return new InvalidVariableNameException(sprintf(
            '%s - Specified variable name "%s" is not valid.  Please see http://php.net/manual/en/language.oop5.basic.php for more information',
            get_class($this),
            $this->_determineExceptionValueOutput($name)
        ));
    }

    /**
     * @param string $context
     * @return \DCarbone\PHPClassBuilder\Exception\MissingNameException
     */
    protected function createMissingNameException($context) {
        return new MissingNameException(sprintf(
            '%s - %s',
            get_class($this),
            $context
        ));
    }

    /**
     * @param string $arg
     * @param string $expectedStatement
     * @param mixed $actualValue
     * @return \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    protected function createInvalidCompileOptionValueException($arg, $expectedStatement, $actualValue) {
        return new InvalidCompileOptionValueException(sprintf(
            '%s - Specified invalid value "%s" for compile argument "%s". Expected: %s',
            get_class($this),
            $this->_determineExceptionValueOutput($actualValue),
            $arg,
            $expectedStatement
        ));
    }

    /**
     * @param mixed $sought
     * @return \OutOfBoundsException
     */
    protected function createFilePartNotFoundException($sought) {
        return new FilePartNotFoundException(sprintf(
            '%s - Specified invalid offset "%s".',
            get_class($this),
            $this->_determineExceptionValueOutput($sought)
        ));
    }

    /**
     * @param mixed $part
     * @return InvalidFilePartException
     */
    protected function createInvalidFilePartException($part) {
        return new InvalidFilePartException(sprintf(
            '%s - Files may only contain Comments and Structures, attempted to add "%s".',
            get_class($this),
            $this->_determineExceptionValueOutput($part)
        ));
    }

    /**
     * @param mixed $path
     * @return InvalidOutputPathException
     */
    protected function createInvalidOutputPathException($path) {
        return new InvalidOutputPathException(sprintf(
            '%s - Specified output path "%s" does not appear to be a valid filepath.',
            get_class($path),
            $this->_determineExceptionValueOutput($path)
        ));
    }

    /**
     * @param mixed $line
     * @return InvalidCommentLineArgumentException
     */
    protected function createInvalidCommentLineArgumentException($line) {
        return new InvalidCommentLineArgumentException(sprintf(
            '%s - Comment lines must be scalar types, %s seen.',
            get_class($this),
            $this->_determineExceptionValueOutput($line)
        ));
    }

    /**
     * @param mixed $offset
     * @return CommentLineIndexNotFoundException
     */
    protected function createCommentLineIndexNotFoundException($offset) {
        return new CommentLineIndexNotFoundException(sprintf(
            '%s - Comment has no line at index "%s"',
            get_class($this),
            $this->_determineExceptionValueOutput($offset)
        ));
    }

    /**
     * @param mixed $line
     * @return InvalidFunctionBodyPartArgumentException
     */
    protected function createInvalidFunctionBodyPartArgumentException($line) {
        return new InvalidFunctionBodyPartArgumentException(sprintf(
            '%s - Function body lines must be strings, %s seen.',
            get_class($this),
            $this->_determineExceptionValueOutput($line)
        ));
    }

    /**
     * @param mixed $argument
     * @return InvalidInterfaceParentArgumentException
     */
    protected function createInvalidInterfaceParentArgumentException($argument) {
        return new InvalidInterfaceParentArgumentException(sprintf(
            '%s - Interface parent arguments must either be a string or an instance of InterfaceTemplate, %s seen.',
            get_class($this),
            $this->_determineExceptionValueOutput($argument)
        ));
    }

    /**
     * @param FunctionTemplate $function
     * @return InvalidInterfaceFunctionScopeException
     */
    protected function createInvalidInterfaceFunctionScopeException(FunctionTemplate $function) {
        return new InvalidInterfaceFunctionScopeException(sprintf(
            '%s - Interface functions must be public, added function %s has scope of %s.',
            get_class($this),
            $this->_determineExceptionValueOutput($function->getName()),
            (string)$function->getScope()
        ));
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function _determineExceptionValueOutput($value) {
        switch (gettype($value)) {
            case 'string':
                return $value;

            case 'integer':
            case 'double':
                return (string)$value;

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