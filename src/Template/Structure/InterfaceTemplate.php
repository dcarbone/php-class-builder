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

use DCarbone\PHPClassBuilder\Template\Comment;
use DCarbone\PHPClassBuilder\Utilities\NameUtils;

/**
 * Class InterfaceTemplate
 * @package DCarbone\PHPClassBuilder\Template
 */
class InterfaceTemplate extends AbstractStructureTemplate
{
    /** @var string */
    private $_name = null;
    /** @var string[]|InterfaceTemplate[] */
    private $_interfaces = array();
    /** @var array */
    private $_methods = array();
    /** @var null|string */
    private $_namespace;
    /** @var \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate[] */
    private $_preDeclarationComments = array();

    /**
     * Constructor
     *
     * @param string|null $name
     * @param string|null $namespace
     */
    public function __construct($name = null, $namespace = null)
    {
        if (null !== $name)
            $this->setName($name);

        if (null !== $namespace)
            $this->setNamespace($namespace);
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
        $this->_name = $name;
    }

    /**
     * @return InterfaceTemplate[]|string[]
     */
    public function getInterfaces()
    {
        return $this->_interfaces;
    }

    /**
     * @param InterfaceTemplate[]|string[] $interfaces
     */
    public function setInterfaces($interfaces)
    {
        $this->_interfaces = $interfaces;
    }

    /**
     * @return MethodTemplate[]
     */
    public function getMethods()
    {
        return $this->_methods;
    }

    /**
     * @param MethodTemplate $method
     */
    public function addMethod(MethodTemplate $method)
    {
        $this->_methods[$method->getName()] = $method;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasMethod($name)
    {
        return isset($this->_methods[$name]);
    }

    /**
     * @param string $name
     * @return MethodTemplate|null
     */
    public function getMethod($name)
    {
        if (isset($this->_methods[$name]))
            return $this->_methods[$name];

        return null;
    }

    /**
     * @return null|string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }

    /**
     * @param null|string $namespace
     */
    public function setNamespace($namespace)
    {
        if (NameUtils::isValidNSName($namespace))
            $this->_namespace = $namespace;
        else
            throw $this->createInvalidNamespaceNameException($namespace);
    }

    /**
     * @return Comment\AbstractCommentTemplate[]
     */
    public function getPreDeclarationComments()
    {
        return $this->_preDeclarationComments;
    }

    /**
     * @param Comment\AbstractCommentTemplate[] $preDeclarationComments
     */
    public function setPreDeclarationComments($preDeclarationComments)
    {
        $this->_preDeclarationComments = $preDeclarationComments;
    }

    /**
     * @param bool $includeLeadingSlash
     * @return string
     */
    public function getFullyQualifiedName($includeLeadingSlash = false)
    {
        if ($this->_namespace)
        {
            return sprintf(
                '%s%s\\%s',
                $includeLeadingSlash ? '\\' : '',
                $this->_namespace,
                $this->_name);
        }

        return sprintf(
            '%s%s',
            $includeLeadingSlash ? '\\' : '',
            $this->_name);
    }

    /**
     * @return string
     */
    public function getUseStatement()
    {
        if ($this->_namespace)
            return sprintf('use %s\\%s;', $this->_namespace, $this->_name);

        return sprintf('use %s;', $this->_name);
    }

    /**
     * @param array $args
     * @return string
     */
    public function compile(array $args = array())
    {
        // TODO: Implement compile() method.
    }

    /**
     * @param array $args
     * @return array
     */
    protected function parseCompileArgs(array $args)
    {
        // Nothing to do here yet...
    }
}