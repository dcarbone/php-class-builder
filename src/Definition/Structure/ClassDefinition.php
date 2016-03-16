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

use DCarbone\PHPClassBuilder\Utilities\NameUtils;

/**
 * Class ClassDefinition
 * @package DCarbone\PHPClassBuilder\Definition
 */
class ClassDefinition extends AbstractStructureDefinition
{
    /** @var string */
    private $_name = null;
    /** @var string|ClassDefinition */
    private $_parent = null;
    /** @var string[]|InterfaceDefinition[] */
    private $_interfaces = array();
    /** @var bool */
    private $_abstract = false;
    /** @var MethodDefinition[] */
    private $_methods = array();
    /** @var VariableDefinition[] */
    private $_properties = array();
    /** @var null|string */
    private $_namespace;
    /** @var \DCarbone\PHPClassBuilder\Definition\Comment\AbstractCommentDefinition[] */
    private $_preDeclarationComments = array();

    /**
     * Constructor
     *
     * @param string|null $name
     * @param string|null $namespace
     * @param null|string|ClassDefinition $parent
     * @param bool|false $abstract
     */
    public function __construct($name = null, $namespace = null, $parent = null, $abstract = false)
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
        if (NameUtils::isValidClassName($name))
            $this->_name = $name;
        else
            throw $this->createInvalidClassNameException($name);
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
     * @return ClassDefinition|string
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * @param ClassDefinition|string $parent
     */
    public function setParent($parent)
    {
        $this->_parent = $parent;
    }

    /**
     * @return InterfaceDefinition[]|string[]
     */
    public function getInterfaces()
    {
        return $this->_interfaces;
    }

    /**
     * @param InterfaceDefinition|string $interface
     */
    public function addInterface($interface)
    {
        if ($interface instanceof InterfaceDefinition)
            $this->_interfaces[$interface->getName()] = $interface;
        else
            $this->_interfaces[$interface] = $interface;
    }

    /**
     * @return boolean
     */
    public function isAbstract()
    {
        return $this->_abstract;
    }

    public function markAbstract()
    {
        $this->_abstract = true;
    }

    /**
     * @return MethodDefinition[]
     */
    public function getMethods()
    {
        return $this->_methods;
    }

    /**
     * @param MethodDefinition $method
     */
    public function addMethod(MethodDefinition $method)
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
     * @return MethodDefinition|null
     */
    public function getMethod($name)
    {
        if (isset($this->_methods[$name]))
            return $this->_methods[$name];

        return null;
    }

    /**
     * @return VariableDefinition[]
     */
    public function getProperties()
    {
        return $this->_properties;
    }

    /**
     * @param VariableDefinition $property
     */
    public function addProperty(VariableDefinition $property)
    {
        $this->_properties[$property->getName()] = $property;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasProperty($name)
    {
        return isset($this->_properties[$name]);
    }

    /**
     * @param string $name
     * @return VariableDefinition|null
     */
    public function getProperty($name)
    {
        if (isset($this->_properties[$name]))
            return $this->_properties[$name];

        return null;
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
        if (null === $this->_name)
            throw $this->createInvalidClassNameException($this->_name);

        $ns = $this->getNamespace();
        if ('' === (string)$ns)
            $output = "<?php\n\n";
        else
            $output = sprintf("<?php namespace %s;\n\n", $ns);

        foreach($this->_preDeclarationComments as $comment)
        {
            $output = sprintf("%s\n%s\n", $output, $comment->compile());
        }

        $output = sprintf("%s\n%s", $output, $this->_compileUseStatements());

        if ("\n\n" !== substr($output, -2))
            $output = sprintf("%s\n", $output);

        if ($this->isAbstract())
            $output = sprintf('%sabstract ', $output);

        $output = sprintf('%sclass %s', $output, $this->getName());

        if ($parent = $this->getParent())
        {
            if ($parent instanceof ClassDefinition)
                $output = sprintf('%sextends %s', $parent->getName());
            else
                $output = sprintf('%sextends %s', $parent);
        }

        if (0 < count($this->_interfaces))
        {
            $interfaces = array();
            foreach($this->_interfaces as $interface)
            {
                if ($interface instanceof InterfaceDefinition)
                    $interfaces[] = $interface->getName();
                else
                    $interfaces[] = substr($interface, strrpos($interface, '\\'));
            }

            $output = sprintf('%s use %s', implode(', ', $interfaces));
        }

        $output = sprintf("%s\n{\n", $output);

        foreach($this->getProperties() as $property)
        {
            $output = sprintf('%s%s', $output, (string)$property);
        }

        foreach($this->getMethods() as $method)
        {
            $output = sprintf('%s%s', $output, (string)$method);
        }

        return sprintf("%s\n}", $output);
    }

    /**
     * @param array $args
     * @return array
     */
    protected function parseCompileArgs(array $args)
    {
        // Nothing to do here yet...
    }

    /**
     * @return string
     */
    private function _compileUseStatements()
    {
        $useStatement = '';

        $thisClassname = $this->getFullyQualifiedName();
        $thisNamespace = $this->getNamespace();

        $usedClasses = array();

        if ($this->_parent)
        {
            if ($this->_parent instanceof ClassDefinition)
                $usedClasses[] = $this->_parent->getFullyQualifiedName();
            else
                $usedClasses[] = $this->_parent;
        }

        if (count($this->_interfaces) > 0)
        {
            foreach($this->_interfaces as $interface)
            {
                if ($interface instanceof InterfaceDefinition)
                    $usedClasses[] = $interface->getFullyQualifiedName();
                else
                    $usedClasses[] = $interface;
            }
        }

        $usedClasses = array_count_values($usedClasses);
        ksort($usedClasses);

        foreach($usedClasses as $usedClass=>$timesImported)
        {
            // Don't use yourself, dog...
            if ($usedClass === $thisClassname)
                continue;

            // If this class is already in the same namespace as this one...
            $remainder = str_replace(array($thisNamespace, '\\'), '', $usedClass);
            if (basename($usedClass) === $remainder)
                continue;

            $useStatement = sprintf("%suse %s;\n", $useStatement, ltrim($usedClass, "\\"));
        }

        return $useStatement;
    }
}