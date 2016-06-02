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
use DCarbone\PHPClassBuilder\Enum\ScopeEnum;
use DCarbone\PHPClassBuilder\Utilities\NameUtils;

/**
 * Class MethodTemplate
 * @package DCarbone\PHPClassBuilder\Template\Method
 */
class FunctionTemplate extends AbstractStructureTemplate
{
    const COMPILETYPE_CLASSMETHOD = 0;
    const COMPILETYPE_FUNCTION = 1;

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
    private $_bodyParts = array();
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
     * @throws \DCarbone\PHPClassBuilder\Exception\InvalidFunctionNameException
     */
    public function setName($name)
    {
        if (NameUtils::isValidFunctionName($name))
            $this->_name = $name;
        else
            throw $this->createInvalidFunctionNameException($name);
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
     * @throws \DCarbone\PHPClassBuilder\Exception\MissingNameException
     */
    public function addParameter(VariableTemplate $parameter)
    {
        $name = $parameter->getName();
        if (null === $name)
            throw $this->createMissingNameException('Function parameters must have a name prior to adding them');

        $this->_parameters[$name] = $parameter;
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
     * @param string $name
     * @return VariableTemplate|null
     */
    public function createParameter($name)
    {
        $this->addParameter(new VariableTemplate($name));
        return $this->getParameter($name);
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
    public function getBodyParts()
    {
        return $this->_bodyParts;
    }

    /**
     * @param string[] $body
     */
    public function setBodyParts(array $body)
    {
        $this->_bodyParts = array();
        foreach($body as $line)
        {
            $this->addBodyPart($line);
        }
    }

    /**
     * @param string $part
     * @throws \DCarbone\PHPClassBuilder\Exception\InvalidFunctionBodyPartArgumentException
     */
    public function addBodyPart($part)
    {
        if (!is_string($part))
            throw $this->createInvalidFunctionBodyPartArgumentException($part);
            
        $this->_bodyParts[] = $part;
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
     * @param array $opts
     * @return string
     */
    public function compile(array $opts = array())
    {
        list(
            $type,
            $leadingSpaces,
            $includeComment) = $this->parseCompileOpts($opts);

        switch($type)
        {
            case self::COMPILETYPE_FUNCTION:
                return $this->_compileAsFunction($leadingSpaces, $includeComment);
            case self::COMPILETYPE_CLASSMETHOD:
                return $this->_compileAsClassMethod($leadingSpaces, $includeComment);

            // TODO: Should not be reachable, but do something?
            default: return '';
        }
    }

    /**
     * @return array
     */
    public function getDefaultCompileOpts()
    {
        static $_defaults = array(
            CompileOpt::COMPILE_TYPE => self::COMPILETYPE_FUNCTION,
            CompileOpt::LEADING_SPACES => 0,
            CompileOpt::INC_COMMENT => true
        );

        return $_defaults;
    }

    /**
     * @param array $opts
     * @return array
     * @throws \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    protected function parseCompileOpts(array $opts)
    {
        $opts = $opts + $this->getDefaultCompileOpts();

        $compiled = array();

        switch($opts[CompileOpt::COMPILE_TYPE])
        {
            case self::COMPILETYPE_FUNCTION:
            case self::COMPILETYPE_CLASSMETHOD:
                $compiled[] = $opts[CompileOpt::COMPILE_TYPE];
                break;
            default:
                throw $this->createInvalidCompileOptionValueException(
                    'CompileOpt::COMPILETYPE',
                    'FunctionTemplate::COMPILETYPE_CLASSMETHOD or FunctionTemplate::COMPILETYPE_FUNCTION',
                    $opts[CompileOpt::COMPILE_TYPE]
                );
        }

        if (is_int($opts[CompileOpt::LEADING_SPACES]) && $opts[CompileOpt::LEADING_SPACES] >= 0)
        {
            $compiled[] = $opts[CompileOpt::LEADING_SPACES];
        }
        else
        {
            throw $this->createInvalidCompileOptionValueException(
                'CompileOpt::LEADING_SPACES',
                'Integer >= 0',
                $opts[CompileOpt::LEADING_SPACES]
            );
        }

        if (is_bool($opts[CompileOpt::INC_COMMENT]))
        {
            $compiled[] = $opts[CompileOpt::INC_COMMENT];
        }
        else
        {
            throw $this->createInvalidCompileOptionValueException(
                'CompileOpt::INC_COMMENT',
                'Boolean value (defaults to TRUE)',
                $opts[CompileOpt::INC_COMMENT]
            );
        }

        return $compiled;
    }

    /**
     * @param int $leadingSpaces
     * @param bool $includeComment
     * @return string
     */
    private function _compileAsFunction($leadingSpaces, $includeComment)
    {
        $spaces = str_repeat(' ', $leadingSpaces);

        if ($includeComment)
        {
            $output = sprintf(
                '%s%s',
                $this->_buildDocBloc()->compile(array(CompileOpt::LEADING_SPACES => $leadingSpaces)),
                $spaces
            );
        }
        else
        {
            $output = $spaces;
        }

        return sprintf(
            "%sfunction %s(%s)\n%s{\n%s\n%s}\n\n",
            $output,
            $this->getName(),
            $this->_buildParameters(),
            $spaces,
            $this->_buildBody($leadingSpaces),
            $spaces
        );
    }

    /**
     * @param int $leadingSpaces
     * @param bool $includeComment
     * @return string
     */
    private function _compileAsClassMethod($leadingSpaces, $includeComment)
    {
        $spaces = str_repeat(' ', $leadingSpaces);

        if ($includeComment)
        {
            $output = sprintf(
                '%s%s',
                $this->_buildDocBloc()->compile(array(CompileOpt::LEADING_SPACES => $leadingSpaces)),
                $spaces
            );
        }
        else
        {
            $output = $spaces;
        }

       if ($this->isAbstract())
            return sprintf('%sabstract %s function %s(%s);', $output, $this->getScope(), $this->getName(), $this->_buildParameters());

        $output = sprintf('%s%s ', $output, $this->getScope());

        if ($this->isStatic())
            $output = sprintf('%sstatic ', $output);

        return sprintf(
            "%sfunction %s(%s)\n%s{\n%s\n%s}\n\n",
            $output,
            $this->getName(),
            $this->_buildParameters(),
            $spaces,
            $this->_buildBody($leadingSpaces),
            $spaces
        );
    }

    /**
     * @return \DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate
     */
    private function _buildDocBloc()
    {
        $comment = $this->getDocBlockComment();

        $addReturn = true;

        foreach($this->getParameters() as $name=>$parameter)
        {
            $addParam = true;
            foreach($comment->getLines() as $line)
            {
                if (preg_match(sprintf('{@param.+\$%s}S', $parameter->getName()), $line))
                {
                    $addParam = false;
                    break;
                }
            }

            if ($addParam)
                $comment->addLine($parameter->getMethodParameterAnnotation());
        }

        foreach($comment->getLines() as $line)
        {
            if (preg_match('{@return}S', $line))
                $addReturn = false;
        }

        if ($addReturn && null !== ($retType = $this->getReturnValueType()))
            $comment->addLine(sprintf('@return %s', $retType));

        return $comment;
    }

    /**
     * @return string
     */
    private function _buildParameters()
    {
        $params = array();
        foreach($this->getParameters() as $name=>$parameter)
        {
            $params[] = $parameter->compile(array(CompileOpt::COMPILE_TYPE => VariableTemplate::COMPILETYPE_PARAMETER));
        }
        return implode(', ', $params);
    }

    /**
     * @param int $leadingSpaces
     * @return string
     */
    private function _buildBody($leadingSpaces)
    {
        $spaces = str_repeat(' ', $leadingSpaces + 4);
        $output = '';
        foreach($this->getBodyParts() as $line)
        {
            $output = sprintf("%s%s%s\n", $output, $spaces, $line);
        }
        return sprintf('%s%s', $output, $this->_buildReturnStatement($spaces));
    }

    /**
     * @param int $leadingSpaces
     * @return string
     */
    private function _buildReturnStatement($leadingSpaces)
    {
        if (is_string($this->_returnStatement))
            return sprintf("%sreturn %s;\n", $leadingSpaces, $this->_returnStatement);

        return '';
    }
}