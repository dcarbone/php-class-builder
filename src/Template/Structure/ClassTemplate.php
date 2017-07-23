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
use DCarbone\PHPClassBuilder\Template\ClassFileTemplate;
use DCarbone\PHPClassBuilder\Utilities\NameUtils;

/**
 * Class ClassTemplate
 * @package DCarbone\PHPClassBuilder\Template
 */
class ClassTemplate extends AbstractStructureTemplate {
    /** @var string */
    private $name = null;
    /** @var string|\DCarbone\PHPClassBuilder\Template\ClassFileTemplate */
    private $parent = null;
    /** @var \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate[] */
    private $interfaces = [];
    /** @var bool */
    private $abstract = false;
    /** @var bool */
    private $final = false;
    /** @var \DCarbone\PHPClassBuilder\Template\Structure\FunctionTemplate[]*/
    private $functions = [];
    /** @var \DCarbone\PHPClassBuilder\Template\Structure\VariableTemplate[] */
    private $properties = array();
    /** @var null|string */
    private $namespace = null;

    /** @var \DCarbone\PHPClassBuilder\Template\ClassFileTemplate */
    private $file = null;

    /**
     * Constructor
     *
     * @param string|null $name
     * @param string|null $namespace
     * @param bool|false $abstract
     * @param bool $final
     */
    public function __construct($name = null, $namespace = null, $abstract = false, $final = false) {
        if (null !== $name) {
            $this->setName($name);
        }

        if (null !== $namespace) {
            $this->setNamespace($namespace);
        }

        $this->abstract = (bool)$abstract;
        $this->final = (bool)$final;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        if (NameUtils::isValidClassName($name)) {
            $this->name = $name;
        } else {
            throw $this->createInvalidClassNameException($name);
        }
    }

    /**
     * @return null|string
     */
    public function getNamespace() {
        return $this->namespace;
    }

    /**
     * @param null|string $namespace
     */
    public function setNamespace($namespace) {
        if (NameUtils::isValidNamespaceName($namespace)) {
            $this->namespace = $namespace;
        } else {
            throw $this->createInvalidNamespaceNameException($namespace);
        }
    }

    /**
     * @return ClassTemplate|string
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @param ClassTemplate|string $parent
     */
    public function setParent($parent) {
        $this->parent = $parent;
    }

    /**
     * @return \DCarbone\PHPClassBuilder\Template\Structure\InterfaceTemplate[]
     */
    public function getInterfaces() {
        return $this->interfaces;
    }

    /**
     * @param InterfaceTemplate|string $interface
     */
    public function addInterface($interface) {
        if ($interface instanceof InterfaceTemplate) {
            $this->interfaces[$interface->getName()] = $interface;
        } else {
            $this->interfaces[$interface] = $interface;
        }
    }

    /**
     * @return boolean
     */
    public function isAbstract() {
        return $this->abstract;
    }

    public function markAbstract() {
        $this->abstract = true;
    }

    /**
     * @return FunctionTemplate[]
     */
    public function getFunctions() {
        return $this->functions;
    }

    /**
     * @param FunctionTemplate $function
     */
    public function addFunction(FunctionTemplate $function) {
        $this->functions[$function->getName()] = $function;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasFunction($name) {
        return isset($this->functions[$name]);
    }

    /**
     * @param string $name
     * @return FunctionTemplate|null
     */
    public function getFunction($name) {
        if (isset($this->functions[$name])) {
            return $this->functions[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @param ScopeEnum $scope
     * @param bool $static
     * @param bool $abstract
     * @return FunctionTemplate
     */
    public function createFunction($name, ScopeEnum $scope = null, $static = false, $abstract = false) {
        if (null == $scope) {
            $scope = new ScopeEnum(ScopeEnum::_PUBLIC);
        }

        $this->addFunction(new FunctionTemplate($name, $scope, $static, $abstract));
        return $this->getFunction($name);
    }

    /**
     * @return VariableTemplate[]
     */
    public function getProperties() {
        return $this->properties;
    }

    /**
     * @param VariableTemplate $property
     */
    public function addProperty(VariableTemplate $property) {
        $this->properties[$property->getName()] = $property;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasProperty($name) {
        return isset($this->properties[$name]);
    }

    /**
     * @param string $name
     * @return VariableTemplate|null
     */
    public function getProperty($name) {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @param ScopeEnum $scope
     * @param bool $requireGetter
     * @param bool $requireSetter
     * @return VariableTemplate|null
     */
    public function createProperty($name, ScopeEnum $scope = null, $requireGetter = true, $requireSetter = true) {
        if (null === $scope) {
            $scope = new ScopeEnum(ScopeEnum::_PUBLIC);
        }

        $this->addProperty(new VariableTemplate($name, $scope, $requireGetter, $requireSetter));
        return $this->getProperty($name);
    }

    /**
     * @return ClassFileTemplate
     */
    public function getFile() {
        if (null === $this->file) {
            $this->file = new ClassFileTemplate($this->getName());
            $this->file->setClass($this);
        }

        return $this->file;
    }

    /**
     * @return bool
     */
    public function inFile() {
        return isset($this->file);
    }

    /**
     * @param ClassFileTemplate $file
     */
    public function setFile(ClassFileTemplate $file) {
        $this->file = $file;
    }

    /**
     * @param bool $includeLeadingSlash
     * @return string
     */
    public function getFullyQualifiedName($includeLeadingSlash = false) {
        if ($this->namespace) {
            return sprintf(
                '%s%s\\%s',
                $includeLeadingSlash ? '\\' : '',
                $this->namespace,
                $this->name);
        }

        return sprintf(
            '%s%s',
            $includeLeadingSlash ? '\\' : '',
            $this->name);
    }

    /**
     * @return string
     */
    public function getUseStatement() {
        if ($this->namespace) {
            return sprintf('use %s\\%s;', $this->namespace, $this->name);
        }

        return sprintf('use %s;', $this->name);
    }

    /**
     * @param array $opts
     * @return string
     */
    public function compile(array $opts = array()) {
        if (null === $this->name) {
            throw $this->createInvalidClassNameException($this->name);
        }

        if ($this->inFile()) {
            $output = '';
        } else {
            $ns = $this->getNamespace();
            if ('' === (string)$ns) {
                $output = "<?php\n\n";
            } else {
                $output = sprintf("<?php namespace %s;\n\n", $ns);
            }
        }

        $output = sprintf("%s\n%s", $output, $this->_compileUseStatements());

        if ("\n\n" !== substr($output, -2)) {
            $output = sprintf("%s\n", $output);
        }

        if ($this->isAbstract()) {
            $output = sprintf('%sabstract ', $output);
        }

        $output = sprintf('%sclass %s', $output, $this->getName());

        if ($parent = $this->getParent()) {
            if ($parent instanceof ClassTemplate) {
                $output = sprintf('%sextends %s', $parent->getName());
            } else {
                $output = sprintf('%sextends %s', $parent);
            }
        }

        if (0 < count($this->interfaces)) {
            $interfaces = array();
            foreach ($this->interfaces as $interface) {
                if ($interface instanceof InterfaceTemplate) {
                    $interfaces[] = $interface->getName();
                } else {
                    $interfaces[] = substr($interface, strrpos($interface, '\\'));
                }
            }

            $output = sprintf('%s use %s', implode(', ', $interfaces));
        }

        $output = sprintf("%s\n{\n", $output);

        foreach ($this->getProperties() as $property) {
            $output = sprintf('%s%s', $output, (string)$property);
        }

        foreach ($this->getFunctions() as $method) {
            $output = sprintf('%s%s', $output, (string)$method);
        }

        return sprintf("%s\n}", $output);
    }

    /**
     * @return array
     */
    public function getDefaultCompileOpts() {
        return array();
    }

    /**
     * @param array $opts
     * @return array
     */
    protected function parseCompileOpts(array $opts) {
        return array();
    }

    /**
     * @return string
     */
    private function _compileUseStatements() {
        $useStatement = '';

        $thisClassname = $this->getFullyQualifiedName();
        $thisNamespace = $this->getNamespace();

        $usedClasses = array();

        if ($this->parent) {
            if ($this->parent instanceof ClassTemplate) {
                $usedClasses[] = $this->parent->getFullyQualifiedName();
            } else {
                $usedClasses[] = $this->parent;
            }
        }

        if (count($this->interfaces) > 0) {
            foreach ($this->interfaces as $interface) {
                if ($interface instanceof InterfaceTemplate) {
                    $usedClasses[] = $interface->getFullyQualifiedName();
                } else {
                    $usedClasses[] = $interface;
                }
            }
        }

        $usedClasses = array_count_values($usedClasses);
        ksort($usedClasses);

        foreach ($usedClasses as $usedClass => $timesImported) {
            // Don't use yourself, dog...
            if ($usedClass === $thisClassname) {
                continue;
            }

            // If this class is already in the same namespace as this one...
            $remainder = str_replace(array($thisNamespace, '\\'), '', $usedClass);
            if (basename($usedClass) === $remainder) {
                continue;
            }

            $useStatement = sprintf("%suse %s;\n", $useStatement, ltrim($usedClass, "\\"));
        }

        return $useStatement;
    }
}