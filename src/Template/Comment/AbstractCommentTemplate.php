<?php namespace DCarbone\PHPClassBuilder\Template\Comment;

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
use DCarbone\PHPClassBuilder\Template\AbstractTemplate;

/**
 * Class AbstractCommentTemplate
 * @package DCarbone\PHPClassBuilder\Template\Comment
 */
abstract class AbstractCommentTemplate extends AbstractTemplate implements \Countable, \Iterator
{
    const NEWLINE_TEST = '{(\r\n)|\n|\r}S';

    /** @var string[] */
    private $_lines = array();

    /**
     * Constructor
     *
     * @param string $text
     */
    public function __construct($text = null)
    {
        if (null !== $text)
            $this->addLine($text);
    }

    /**
     * @param string|bool|int|double|null $line
     * @throws \DCarbone\PHPClassBuilder\Exception\InvalidCommentLineArgumentException
     */
    public function addLine($line = '')
    {
        if (null === $line)
        {
            $this->_lines[] = '';
        }
        else
        {
            switch(gettype($line))
            {
                case 'string':
                    if ($this->testStringForNewlines($line))
                        $this->addLines($this->parseStringInput($line));
                    else
                        $this->_lines[] = $line;
                    break;

                case 'integer':
                case 'double':
                    $this->_lines[] = (string)$line;
                    break;

                case 'boolean':
                    $this->_lines[] = $line ? 'TRUE' : 'FALSE';
                    break;

                default:
                    throw $this->createInvalidCommentLineArgumentException($line);
            }
        }
    }

    /**
     * Add a blank line to the output.
     */
    public function addEmptyLine()
    {
        $this->_lines[] = '';
    }

    /**
     * @return string[]
     */
    public function getLines()
    {
        return $this->_lines;
    }

    /**
     * @param array $lines
     */
    public function addLines(array $lines)
    {
        foreach($lines as $line)
        {
            $this->addLine($line);
        }
    }

    /**
     * @param array $lines
     */
    public function setLines(array $lines)
    {
        $this->_lines = array();
        foreach($lines as $line)
        {
            $this->addLine($line);
        }
    }

    /**
     * @param string $line
     * @param bool|true $strict
     * @return integer Returns index of line or -1 if not found
     */
    public function hasLine($line, $strict = true)
    {
        return in_array($line, $this->_lines, (bool)$strict);
    }

    /**
     * @param string $line
     * @param bool|true $strict
     * @return integer Index of line or -1 if not found
     */
    public function getLineIndex($line, $strict = true)
    {
        return array_search($line, $this->_lines, (bool)$strict);
    }

    /**
     * @param int $idx
     */
    public function removeLineByIndex($idx)
    {
        if (isset($this->_lines[$idx]))
        {
            unset($this->_lines[$idx]);
            $this->_lines = array_values($this->_lines);
        }
    }

    /**
     * @param string $value
     * @param bool|true $strict
     */
    public function removeLineByValue($value, $strict = true)
    {
        $idx = $this->getLineIndex($value, (bool)$strict);
        if ($idx >= 0)
            $this->removeLineByIndex($idx);
    }

    /**
     * Clear out comment body
     */
    public function clear()
    {
        $this->_lines = array();
    }

    /**
     * @return array
     */
    public function getDefaultCompileOpts()
    {
        static $_defaults = array(
            CompileOpt::LEADING_SPACES => 4
        );

        return $_defaults;
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return count($this->_lines);
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->_lines);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->_lines);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->_lines);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     */
    public function valid()
    {
        return key($this->_lines) !== null;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->_lines);
    }

    /**
     * @param array $opts
     * @return array
     * @throws \DCarbone\PHPClassBuilder\Exception\InvalidCompileOptionValueException
     */
    protected function parseCompileOpts(array $opts)
    {
        $opts = $opts + $this->getDefaultCompileOpts();

        if (isset($opts[CompileOpt::LEADING_SPACES])
            && is_int($opts[CompileOpt::LEADING_SPACES])
            && $opts[CompileOpt::LEADING_SPACES] >= 0)
        {
            return array($opts[CompileOpt::LEADING_SPACES]);
        }

        throw $this->createInvalidCompileOptionValueException(
            'CompileOpt::LEADING_SPACES',
            'integer >= 0',
            (isset($opts[CompileOpt::LEADING_SPACES]) ? $opts[CompileOpt::LEADING_SPACES] : 'UNDEFINED')
        );
    }

    /**
     * @param string $line
     * @return bool
     */
    protected function testStringForNewlines($line)
    {
        return (bool)preg_match(self::NEWLINE_TEST, $line);
    }

    /**
     * @param string $line
     * @return array
     */
    protected function parseStringInput($line)
    {
        return preg_split(self::NEWLINE_TEST, $line);
    }
}