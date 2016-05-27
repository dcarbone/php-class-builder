<?php namespace DCarbone\PHPClassBuilder\Template\Structure;

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
use DCarbone\PHPClassBuilder\Utilities\NameUtils;

/**
 * Class MethodTemplate
 * @package DCarbone\PHPClassBuilder\Template\Method
 */
class MethodTemplate extends AbstractStructureTemplate
{
    const COMPILEOPT_INCLUDE_BODY = 0;

    /** @var string */
    private $_name = null;
    /** @var ScopeEnum */
    private $_scope;
    /** @var bool */
    private $_static = false;
    /** @var bool */
    private $_abstract = false;
    /** @var VariableTemplate[] */
    private $_parameters = array();
    /** @var string[] */
    private $_body = array();
    /** @var null|string */
    private $_returnValueType = null;
    /** @var null|string */
    private $_returnStatement = null;

    /**
     * Constructor
     *
     * @param string $name
     * @param ScopeEnum $scope
     * @param bool $static
     * @param bool $abstract
     */
    public function __construct($name = null, ScopeEnum $scope = null, $static = false, $abstract = false)
    {
        if (null !== $name)
            $this->setName($name);

        if (null === $scope)
            $this->_scope = new ScopeEnum(ScopeEnum::_PUBLIC);
        else
            $this->_scope = $scope;

        $this->_static = (bool)$static;
        $this->_abstract = (bool)$abstract;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        if (NameUtils::isValidFunctionName($name))
            $this->_name = $name;
        else
            throw $this->createInvalidMethodNameException($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param ScopeEnum $scope
     */
    public function setScope(ScopeEnum $scope)
    {
        $this->_scope = $scope;
    }

    /**
     * @return ScopeEnum
     */
    public function getScope()
    {
        return $this->_scope;
    }

    /**
     * @return \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate[]
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * @param VariableTemplate $parameter
     */
    public function addParameter(VariableTemplate $parameter)
    {
        $this->_parameters[$parameter->getName()] = $parameter;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasParameter($name)
    {
        return isset($this->_parameters[$name]);
    }

    /**
     * @param string $name
     * @return VariableTemplate|null
     */
    public function getParameter($name)
    {
        if (isset($this->_parameters[$name]))
            return $this->_parameters[$name];

        return null;
    }

    /**
     * @return null|string
     */
    public function getReturnValueType()
    {
        return $this->_returnValueType;
    }

    /**
     * @param null|string $returnValueType
     */
    public function setReturnValueType($returnValueType)
    {
        $this->_returnValueType = $returnValueType;
    }

    /**
     * @return null|string
     */
    public function getReturnStatement()
    {
        return $this->_returnStatement;
    }

    /**
     * @param null|string $returnStatement
     */
    public function setReturnStatement($returnStatement)
    {
        $this->_returnStatement = $returnStatement;
    }

    /**
     * @return string[]
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * @param string[] $body
     */
    public function setBody($body)
    {
        $this->_body = $body;
    }

    /**
     * @return boolean
     */
    public function isStatic()
    {
        return $this->_static;
    }

    /**
     * @param boolean $static
     */
    public function setStatic($static)
    {
        $this->_static = (bool)$static;
    }

    /**
     * @return boolean
     */
    public function isAbstract()
    {
        return $this->_abstract;
    }

    /**
     * @param boolean $abstract
     */
    public function setAbstract($abstract)
    {
        $this->_abstract = (bool)$abstract;
    }

    /**
     * @param array $args
     * @return string
     */
    public function compile(array $args = array())
    {
        // TODO: Implement compile() method.
    }

    public function getDefaultCompileArgs()
    {
        static $_defaults = array(
            self::COMPILEOPT_INCLUDE_BODY => true
        );

        return $_defaults;
    }

    /**
     * @param array $args
     * @return array
     */
    protected function parseCompileArgs(array $args)
    {
        $args = $args + $this->getDefaultCompileArgs();

        if (isset($args[self::COMPILEOPT_INCLUDE_BODY]) && is_bool($args[self::COMPILEOPT_INCLUDE_BODY]))
            return array($args[self::COMPILEOPT_INCLUDE_BODY]);

        throw $this->createInvalidCompileArgumentValueException(
            'MethodTemplate::COMPILEOPT_INCLUDE_BODY',
            'boolean value',
            isset($args[self::COMPILEOPT_INCLUDE_BODY]) ? $args[self::COMPILEOPT_INCLUDE_BODY] : 'UNDEFINED'
        );
    }
}