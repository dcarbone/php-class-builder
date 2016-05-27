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

use DCarbone\PHPClassBuilder\Enum\CompileOpt;
use DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate;
use DCarbone\PHPClassBuilder\Enum\ScopeEnum;
use DCarbone\PHPClassBuilder\Utilities\NameUtils;

/**
 * Class VariableTemplate
 * @package DCarbone\PHPClassBuilder\Template
 */
class VariableTemplate extends AbstractStructureTemplate
{
    // Compile template as bare variable
    const COMPILETYPE_VARIABLE = 1;
    // Compile template as method parameter
    const COMPILETYPE_METHOD = 2;
    // Compile template as class property
    const COMPILETYPE_PROPERTY = 3;

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
     * @return string
     */
    public function getClassPropertyAnnotation()
    {
        return sprintf(
            '@var %s%s',
            $this->getPHPType(),
            $this->isCollection() ? '[]' : ''
        );
    }

    /**
     * @return string
     */
    public function getMethodParameterAnnotation()
    {
        return sprintf(
            '@param %s $%s%s',
            $this->getPHPType(),
            $this->getName(),
            $this->isCollection() ? '[]' : ''
        );
    }

    /**
     * @param array $args
     * @return string
     */
    public function compile(array $args = array())
    {
        list(
            $type,
            $includeComment,
            $leadingSpaces,
            $includeDefaultValue) = $this->parseCompileArgs($args);

        switch($type)
        {
            case self::COMPILETYPE_VARIABLE:
                return $this->_compileAsVariable($includeComment, $leadingSpaces, $includeDefaultValue);
            case self::COMPILETYPE_PROPERTY:
                return $this->_compileAsClassProperty($includeComment, $leadingSpaces, $includeDefaultValue);
            case self::COMPILETYPE_METHOD:
                return $this->_compileAsMethodParameter($includeDefaultValue);

            // TODO: Should not be reachable, but do something?
            default: return '';
        }
    }

    /**
     * @return array
     */
    public function getDefaultCompileArgs()
    {
        static $_defaults = array(
            CompileOpt::COMPILE_TYPE => self::COMPILETYPE_VARIABLE,
            CompileOpt::INC_COMMENT => true,
            CompileOpt::LEADING_SPACES => 8,
            CompileOpt::INC_DEFAULT_VALUE => true
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

        $compiled = array();

        switch($args[CompileOpt::COMPILE_TYPE])
        {
            case self::COMPILETYPE_VARIABLE:
            case self::COMPILETYPE_METHOD:
            case self::COMPILETYPE_PROPERTY:
                $compiled[] = $args[CompileOpt::COMPILE_TYPE];
                break;

            default:
                throw $this->createInvalidCompileArgumentValueException(
                    'CompileOpt::COMPILE_TYPE',
                    'VariableTemplate::COMPILETYPE_VARIABLE, VariableTemplate::COMPILETYPE_METHOD, VariableTemplate::COMPILETYPE_PROPERTY',
                    $args[CompileOpt::COMPILE_TYPE]
                );
        }

        if (isset($args[CompileOpt::INC_COMMENT]))
        {
            if (is_bool($args[CompileOpt::INC_COMMENT]))
            {
                $compiled[] = $args[CompileOpt::INC_COMMENT];
            }
            else
            {
                throw $this->createInvalidCompileArgumentValueException(
                    'CompileOpt::INC_COMMENT',
                    'Boolean value (defaults to TRUE)',
                    $args[CompileOpt::INC_COMMENT]
                );
            }
        }

        if (isset($args[CompileOpt::LEADING_SPACES]))
        {
            if (is_int($args[CompileOpt::LEADING_SPACES]) && $args[CompileOpt::LEADING_SPACES] >= 0)
            {
                $compiled[] = $args[CompileOpt::LEADING_SPACES];
            }
            else
            {
                throw $this->createInvalidCompileArgumentValueException(
                    'CompileOpt::LEADING_SPACES',
                    'Integer >= 0',
                    $args[CompileOpt::LEADING_SPACES]
                );
            }
        }

        if (isset($args[CompileOpt::INC_DEFAULT_VALUE]))
        {
            if (is_bool($args[CompileOpt::INC_DEFAULT_VALUE]))
            {
                $compiled[] = $args[CompileOpt::INC_DEFAULT_VALUE];
            }
            else
            {
                throw $this->createInvalidCompileArgumentValueException(
                    'CompileOpt::INC_DEFAULT_VALUE',
                    'Boolean value (defaults to TRUE)',
                    $args[CompileOpt::INC_DEFAULT_VALUE]
                );
            }
        }
        else
        {
            $compiled[] = true;
        }

        return $compiled;
    }

    /**
     * @param bool $includeComment
     * @param int $leadingSpaces
     * @param bool $includeDefaultValue
     * @return string
     */
    private function _compileAsVariable($includeComment, $leadingSpaces, $includeDefaultValue)
    {
        $spaces = str_repeat(' ', $leadingSpaces);

        if ($includeComment)
        {
            $output = sprintf(
                '%s%s',
                $this->_compileClassPropertyDocBlockComment()->compile(array(CompileOpt::LEADING_SPACES => $leadingSpaces)),
                $spaces
            );
        }
        else
        {
            $output = $spaces;
        }

        $output = sprintf('%s $%s', $output, (string)$this->getName());

        if ($includeDefaultValue && null !== ($default = $this->getDefaultValueStatement()))
            $output = sprintf("%s = %s;\n", $output, $default);
        else
            $output = sprintf("%s;\n", $output);

        return $output;
    }

    /**
     * @param bool $includeComment
     * @param int $leadingSpaces
     * @param bool $includeDefaultValue
     * @return string
     */
    private function _compileAsClassProperty($includeComment, $leadingSpaces, $includeDefaultValue)
    {
        $spaces = str_repeat(' ', $leadingSpaces);

        if ($includeComment)
        {
            $output = sprintf(
                '%s%s%s',
                $this->_compileClassPropertyDocBlockComment()->compile(array(CompileOpt::LEADING_SPACES => $leadingSpaces)),
                $spaces,
                (string)$this->getScope()
            );
        }
        else
        {
            $output = sprintf('%s%s', $spaces, (string)$this->getScope());
        }

        if ($this->isStatic())
            $output = sprintf('%s static', $output);

        $output = sprintf('%s $%s', $output, $this->getName());

        if ($includeDefaultValue && null !== ($default = $this->getDefaultValueStatement()))
            $output = sprintf("%s = %s;\n", $output, $default);
        else
            $output = sprintf("%s;\n", $output);

        return $output;
    }

    /**
     * @param bool $includeDefaultValue
     * @return string
     */
    private function _compileAsMethodParameter($includeDefaultValue)
    {
        $output = sprintf('$%s', $this->getName());

        if ($includeDefaultValue && null !== ($default = $this->getDefaultValueStatement()))
            $output = sprintf('%s = %s', $output, $default);

        return $output;
    }

    /**
     * @return DoubleStarCommentTemplate
     */
    private function _compileClassPropertyDocBlockComment()
    {
        $comment = $this->getDocBlockComment();
        $addAnnotation = true;
        foreach($comment->getLines() as $line)
        {
            if (0 === strpos(trim($line), '@var'))
            {
                $addAnnotation = false;
                break;
            }
        }

        if ($addAnnotation)
            $comment->addLine($this->getClassPropertyAnnotation());

        return $comment;
    }
}