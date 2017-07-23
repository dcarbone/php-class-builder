<?php namespace DCarbone\PHPClassBuilder\Template\Structure;

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

use DCarbone\PHPClassBuilder\Enum\ScopeEnum;
use DCarbone\PHPClassBuilder\Utilities\NameUtils;

/**
 * Class InterfaceTemplate
 * @package DCarbone\PHPClassBuilder\Template
 */
class InterfaceTemplate extends AbstractStructureTemplate {
    /** @var string */
    private $_name = null;
    /** @var string[]|InterfaceTemplate[] */
    private $_parentInterfaces = array();
    /** @var array */
    private $_functions = array();
    /** @var null|string */
    private $_namespace = null;

    /**
     * Constructor
     *
     * @param string|null $name
     * @param string|null $namespace
     */
    public function __construct($name = null, $namespace = null) {
        if (null !== $name) {
            $this->setName($name);
        }

        if (null !== $namespace) {
            $this->setNamespace($namespace);
        }
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        if (NameUtils::isValidInterfaceName($name)) {
            $this->_name = $name;
        } else {
            throw $this->createInvalidInterfaceNameException($name);
        }
    }

    /**
     * @return InterfaceTemplate[]|string[]
     */
    public function getParentInterfaces() {
        return $this->_parentInterfaces;
    }

    /**
     * @param InterfaceTemplate|string $interface
     */
    public function addParentInterface($interface) {
        if (is_string($interface)) {
            $this->_parentInterfaces[$interface] = $interface;
        } else {
            if ($interface instanceof InterfaceTemplate) {
                $name = $interface->getName();
                if (null === $name) {
                    throw $this->createMissingNameException('Must define interface name prior to adding to another interface.');
                }

                $this->_parentInterfaces[$name] = $interface;
            } else {
                throw $this->createInvalidInterfaceParentArgumentException($interface);
            }
        }
    }

    /**
     * @param InterfaceTemplate[]|string[] $interfaces
     */
    public function setParentInterfaces(array $interfaces) {
        $this->_parentInterfaces = array();
        foreach ($interfaces as $interface) {
            $this->addParentInterface($interface);
        }
    }

    /**
     * @return FunctionTemplate[]
     */
    public function getFunctions() {
        return $this->_functions;
    }

    /**
     * @param FunctionTemplate $function
     */
    public function addFunction(FunctionTemplate $function) {
        if (ScopeEnum::_PUBLIC === (string)$function->getScope()) {
            $this->_functions[$function->getName()] = $function;
        } else {
            throw $this->createInvalidInterfaceFunctionScopeException($function);
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasFunction($name) {
        return isset($this->_functions[$name]);
    }

    /**
     * @param string $name
     * @return FunctionTemplate|null
     */
    public function getFunction($name) {
        if (isset($this->_functions[$name])) {
            return $this->_functions[$name];
        }

        return null;
    }

    /**
     * @param FunctionTemplate[] $functions
     */
    public function setFunctions(array $functions) {
        $this->_functions = array();
        foreach ($functions as $function) {
            $this->addFunction($function);
        }
    }

    /**
     * @return null|string
     */
    public function getNamespace() {
        return $this->_namespace;
    }

    /**
     * @param null|string $namespace
     */
    public function setNamespace($namespace) {
        if (NameUtils::isValidNamespaceName($namespace)) {
            $this->_namespace = $namespace;
        } else {
            throw $this->createInvalidNamespaceNameException($namespace);
        }
    }

    /**
     * @param bool $includeLeadingSlash
     * @return string
     */
    public function getFullyQualifiedName($includeLeadingSlash = false) {
        if ($this->_namespace) {
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
    public function getUseStatement() {
        if ($this->_namespace) {
            return sprintf('use %s\\%s;', $this->_namespace, $this->_name);
        }

        return sprintf('use %s;', $this->_name);
    }

    /**
     * @param array $opts
     * @return string
     */
    public function compile(array $opts = array()) {
        // TODO: Implement compile() method.
    }

    /**
     * @return array
     */
    public function getDefaultCompileOpts() {
        // TODO: Implement getDefaultCompileArgs() method.
    }

    /**
     * @param array $opts
     * @return array
     */
    protected function parseCompileOpts(array $opts) {
        // Nothing to do here yet...
    }
}