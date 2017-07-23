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

use DCarbone\PHPClassBuilder\Utilities\NameUtils;

/**
 * Class FileTemplate
 * @package DCarbone\PHPClassBuilder\Template
 */
class FileTemplate extends AbstractTemplate {

    /** @var string */
    private $filename;

    /** @var string */
    private $namespace;

    /** @var \DCarbone\PHPClassBuilder\Template\TemplateInterface[] */
    private $items = [];

    /**
     * FileTemplate constructor.
     * @param $filename
     */
    public function __construct($filename, $namespace = null, array $items = []) {
        $this->filename = $filename;
        if (null !==- $namespace) {
            $this->setNamespace($namespace);
        }
        $this->setItems($items);
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename) {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace) {
        if (NameUtils::isValidNamespaceName($namespace)) {
            $this->namespace = $namespace;
        } else {
            throw $this->createInvalidNamespaceNameException($namespace);
        }
    }

    /**
     * @param \DCarbone\PHPClassBuilder\Template\TemplateInterface $template
     */
    public function addItem(TemplateInterface $template) {
        $this->items[] = $template;
    }

    /**
     * @param array $templates
     */
    public function setItems(array $templates) {
        $this->items = [];
        foreach($templates as $template) {
            $this->addItem($template);
        }
    }

    /**
     * @param array $opts
     * @return array
     */
    protected function parseCompileOpts(array $opts) {
        return [];
    }

    /**
     * @param array $opts
     * @return string
     */
    public function compile(array $opts = []) {
        $file = '<?php';

        if ($this->namespace) {
            $file = sprintf("%s namespace %s;", $file, $this->namespace);
        }

        $file .= "\n\n";

        foreach($this->items as $item) {
            $file = sprintf("%s%s\n", $file, $item->compile());
        }

        return $file;
    }

    public function getDefaultCompileOpts() {
        return [];
    }

    /**
     * @param string $outputPath
     * @return bool
     */
    public function writeToFile($outputPath) {
        if (is_dir($outputPath)) {
            $outputPath = sprintf(
                '%s/%s.php',
                rtrim(trim($outputPath), "/\\"),
                $this->getFilename()
            );

            return (bool)file_put_contents($outputPath, $this->compile());
        }

        throw $this->createInvalidOutputPathException($outputPath);
    }
}