<?php namespace DCarbone\PHPClassBuilder\Template;

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

use DCarbone\PHPClassBuilder\Template\Comment\AbstractCommentTemplate;
use DCarbone\PHPClassBuilder\Template\Structure\ClassTemplate;

/**
 * Class FileTemplate
 * @package DCarbone\PHPClassBuilder\Template
 */
class FileTemplate extends AbstractTemplate
{
    /** @var AbstractCommentTemplate[] */
    private $_beforeClassComments = array();
    /** @var ClassTemplate */
    private $_class = null;
    /** @var AbstractCommentTemplate[] */
    private $_afterClassComments = array();

    /**
     * @param ClassTemplate $classTemplate
     */
    public function setClass(ClassTemplate $classTemplate)
    {
        $this->_class = $classTemplate;
        $this->_class->setFile($this);
    }

    /**
     * @return ClassTemplate
     */
    public function getClass()
    {
        return $this->_class;
    }

    /**
     * @param AbstractCommentTemplate $comment
     */
    public function addBeforeClassComment(AbstractCommentTemplate $comment)
    {
        $this->_beforeClassComments[] = $comment;
    }

    /**
     * @param array $comments
     */
    public function setBeforeClassComments(array $comments)
    {
        $this->_beforeClassComments = array();
        foreach($comments as $comment)
        {
            $this->addBeforeClassComment($comment);
        }
    }
    
    public function clearBeforeClassComments()
    {
        $this->_beforeClassComments = array();
    }

    /**
     * @return Comment\AbstractCommentTemplate[]
     */
    public function getBeforeClassComments()
    {
        return $this->_beforeClassComments;
    }

    /**
     * @param AbstractCommentTemplate $comment
     */
    public function addAfterClassComment(AbstractCommentTemplate $comment)
    {
        $this->_afterClassComments[] = $comment;
    }

    /**
     * @param array $comments
     */
    public function setAfterClassComments(array $comments)
    {
        $this->_afterClassComments = array();
        foreach($comments as $comment)
        {
            $this->addAfterClassComment($comment);
        }
    }

    public function clearAfterClassComments()
    {
        $this->_afterClassComments = array();
    }

    /**
     * @return Comment\AbstractCommentTemplate[]
     */
    public function getAfterClassComments()
    {
        return $this->_afterClassComments;
    }

    /**
     * @param array $args
     * @return string
     */
    public function compile(array $args = array())
    {
        $file = '<?php';

        $class = $this->getClass();

        if ($ns = $class->getNamespace())
            $file = sprintf("%s namespace %s;\n\n", $file, $ns);
        else
            $file = sprintf("%s\n\n", $file);

        foreach($this->_beforeClassComments as $comment)
        {
            $file = sprintf("%s%s\n", $file, $comment->compile(array('leadingSpaces' => 0)));
        }

        $file = sprintf("%s\n%s\n", $file, $class->compile(array('inFile' => true)));

        foreach($this->_afterClassComments as $comment)
        {
            $file = sprintf("%s%s\n", $file, $comment->compile(array('leadingSpaces' => 0)));
        }

        return $file;
    }

    /**
     * @param string$outputPath
     * @return bool
     */
    public function writeToFile($outputPath)
    {
        if (is_dir($outputPath))
        {
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
     * @param array $args
     * @return array
     */
    protected function parseCompileArgs(array $args)
    {
        // Nothing to do here yet.
    }
}