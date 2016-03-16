<?php namespace DCarbone\PHPClassBuilder\Definition\Structure;

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
use DCarbone\PHPClassBuilder\Enum\ScopeEnum;
use DCarbone\PHPClassBuilder\Utilities\NameUtils;

/**
 * Class VariableDefinition
 * @package DCarbone\PHPClassBuilder\Definition
 */
class VariableDefinition extends AbstractStructureDefinition
{
    /** @var string */
    private $_name;
    /** @var ScopeEnum */
    private $_scope;
    /** @var bool */
    private $_static = false;
    /** @var bool */
    private $_collection = false;

    /** @var string */
    private $_phpType = 'mixed';

    /** @var string */
    private $_defaultValueStatement = null;

    /** @var bool */
    private $_requiresGetter;
    /** @var bool */
    private $_requireSetter;

    /**
     * Constructor
     *
     * @param string|null $name
     * @param ScopeEnum $scope
     * @param bool $requiresGetter
     * @param bool $requiresSetter
     */
    public function __construct($name = null,
                                ScopeEnum $scope = null,
                                $requiresGetter = true,
                                $requiresSetter = true)
    {
        if (null !== $name)
            $this->setName($name);

        if (null === $scope)
            $this->_scope = new ScopeEnum(ScopeEnum::_PUBLIC);
        else
            $this->_scope = $scope;

        $this->_requiresGetter = (bool)$requiresGetter;
        $this->_requireSetter = (bool)$requiresSetter;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        if (NameUtils::isValidVariableName($name))
            $this->_name = $name;
        else
            throw $this->createInvalidVariableNameException($name);
    }

    /**
     * @return ScopeEnum
     */
    public function getScope()
    {
        return $this->_scope;
    }

    /**
     * @param ScopeEnum $scope
     */
    public function setScope(ScopeEnum $scope)
    {
        $this->_scope = $scope;
    }

    /**
     * @return boolean
     */
    public function isCollection()
    {
        return $this->_collection;
    }

    /**
     * @param boolean $collection
     */
    public function setCollection($collection = true)
    {
        $this->_collection = $collection;
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
    public function setStatic($static = true)
    {
        $this->_static = $static;
    }

    /**
     * @return string
     */
    public function getPHPType()
    {
        return $this->_phpType;
    }

    /**
     * @param string $phpType
     */
    public function setPHPType($phpType)
    {
        $this->_phpType = $phpType;
    }

    /**
     * @return string
     */
    public function getDefaultValueStatement()
    {
        return $this->_defaultValueStatement;
    }

    /**
     * @param string $defaultValueStatement
     */
    public function setDefaultValueStatement($defaultValueStatement)
    {
        $this->_defaultValueStatement = $defaultValueStatement;
    }

    /**
     * @return boolean
     */
    public function requiresGetter()
    {
        return $this->_requiresGetter;
    }

    /**
     * @param boolean $requiresGetter
     */
    public function setRequiresGetter($requiresGetter = true)
    {
        $this->_requiresGetter = $requiresGetter;
    }

    /**
     * @return boolean
     */
    public function requiresSetter()
    {
        return $this->_requireSetter;
    }

    /**
     * @param boolean $requireSetter
     */
    public function setRequiresSetter($requireSetter = true)
    {
        $this->_requireSetter = $requireSetter;
    }

    /**
     * @param bool|false $annotationOnly
     * @return DoubleStarCommentDefinition|string
     */
    public function getClassPropertyComment($annotationOnly = false)
    {
        $annotation = sprintf(
            '@var %s $%s%s',
            $this->getPHPType(),
            $this->getName(),
            $this->isCollection() ? '[]' : ''
        );

        if ($annotationOnly)
            return $annotation;

        $comment = new DoubleStarCommentDefinition();
        $comment->addLine($annotation);

        return $comment;
    }

    /**
     * @param array $args
     * @return string
     */
    public function compile(array $args = array())
    {
        list($compileType) = $this->parseCompileArgs($args);

        switch($compileType)
        {
            case 'classProperty':
                return $this->_compileAsClassProperty();
            case 'methodParameter':
                return $this->_compileAsMethodParameter();

            default:
                return '';
        }
    }

    /**
     * @param array $args
     * @return array
     */
    protected function parseCompileArgs(array $args)
    {
        static $compileTypes = array(
            'classProperty',
            'methodParameter',
        );

        if (isset($args['compileType']) && in_array($args['compileType'], $compileTypes, true))
            return array($args['compileType']);

        throw $this->createInvalidCompileArgumentValueException(
            'compileType',
            sprintf('[\'%s\']', implode('\', \'', $compileTypes)),
            isset($args['compileType']) ? $args['compileType'] : 'UNDEFINED'
        );
    }

    /**
     * @return string
     */
    private function _compileAsClassProperty()
    {
        $output = sprintf(
            '%s        %s',
            $this->getClassPropertyComment()->compile(array('leadingSpaces' => 8)),
            (string)$this->getScope()
        );

        if ($this->isStatic())
            $output = sprintf('%s static', $output);

        $output = sprintf('%s $%s', $output, $this->getName());

        if (null !== ($default = $this->getDefaultValueStatement()))
            $output = sprintf('%s = %s;', $output, $default);

        return $output;
    }

    /**
     * @return string
     */
    private function _compileAsMethodParameter()
    {
        $output = sprintf('$%s', $this->getName());

        if (null !== ($default = $this->getDefaultValueStatement()))
            $output = sprintf('%s = %s', $output, $default);

        return $output;
    }
}