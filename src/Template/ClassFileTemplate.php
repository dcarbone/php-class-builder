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

use DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate;
use DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate;

/**
 * Class FileTemplate
 * @package DCarbone\PHPClassBuilder\Template
 */
class ClassFileTemplate extends AbstractTemplate {
    /** @var string */
    private $filename;

    /** @var \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate[] */
    private $preClassComments = [];
    /** @var \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate */
    private $class = null;
    /** @var \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate[] */
    private $postClassComments = [];

    /**
     * @param \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate $classTemplate
     */
    public function setClass(ClassTemplate $classTemplate) {
        $this->class = $classTemplate;
        $this->class->setFile($this);
    }

    /**
     * @return \DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * @param \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate $comment
     */
    public function addPreClassComment(AbstractCommentTemplate $comment) {
        $this->preClassComments[] = $comment;
    }

    /**
     * @param array $comments
     */
    public function setPreClassComments(array $comments) {
        $this->preClassComments = [];
        foreach ($comments as $comment) {
            $this->addPreClassComment($comment);
        }
    }

    public function clearBeforeClassComments() {
        $this->preClassComments = [];
    }

    /**
     * @return \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate[]
     */
    public function getPreClassComments() {
        return $this->preClassComments;
    }

    /**
     * @param \DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate $comment
     */
    public function addPostClassComment(AbstractCommentTemplate $comment) {
        $this->postClassComments[] = $comment;
    }

    /**
     * @param array $comments
     */
    public function setPostClassComments(array $comments) {
        $this->postClassComments = [];
        foreach ($comments as $comment) {
            $this->addPostClassComment($comment);
        }
    }

    public function clearAfterClassComments() {
        $this->postClassComments = [];
    }

    /**
     * @return Comment\AbstractCommentTemplate[]
     */
    public function getPostClassComments() {
        return $this->postClassComments;
    }

    /**
     * Allows you to override the name of the file that will be created.  Defaults to class name.
     *
     * @param string $filename
     */
    public function setFilename($filename) {
        $this->filename = $filename;
    }

    /**
     * @param array $opts
     * @return string
     */
    public function compile(array $opts = []) {
        $file = '<?php';

        if ($class = $this->getClass()) {
            if ($ns = $class->getNamespace()) {
                $file = sprintf("%s namespace %s;\n\n", $file, $ns);
            } else {
                $file = sprintf("%s\n\n", $file);
            }
        }

        foreach ($this->preClassComments as $comment) {
            $file = sprintf("%s%s\n", $file, $comment->compile(array('leadingSpaces' => 0)));
        }

        if ($class) {
            $file = sprintf("%s\n%s\n", $file, $class->compile(array('inFile' => true)));
        }

        foreach ($this->postClassComments as $comment) {
            $file = sprintf("%s%s\n", $file, $comment->compile(array('leadingSpaces' => 0)));
        }

        return $file;
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
                $this->getClass()->getName()
            );

            return (bool)file_put_contents($outputPath, $this->compile());
        }

        throw $this->createInvalidOutputPathException($outputPath);
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
     * @throws \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    protected function parseCompileOpts(array $opts) {
        return [];
    }
}