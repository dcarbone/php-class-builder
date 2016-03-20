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

use DCarbone\PHPClassBuilder\Template\AbstractTemplate;

/**
 * Class AbstractCommentTemplate
 * @package DCarbone\PHPClassBuilder\Template\Comment
 */
abstract class AbstractCommentTemplate extends AbstractTemplate implements \Countable
{
    const NEWLINE_TEST = '{(\r\n)|\n|\r}S';

    /** @var string[] */
    private $_lines = array();

    /**
     * @param string $line
     */
    public function addLine($line = '')
    {
        switch(gettype($line))
        {
            case 'string':
                foreach($this->parseStringInput($line) as $line)
                {
                    $this->_lines[] = $line;
                }
                break;

            case 'integer':
            case 'float':
                $this->_lines[] = (string)$line;
                break;

            case 'null':
                $this->_lines[] = '';
                break;

            case 'boolean':
                $this->_lines[] = $line ? 'TRUE' : 'FALSE';
                break;

            default:
                throw new \InvalidArgumentException(sprintf(
                    '%s::addLine - Comment lines must be scalar types, %s seen.',
                    get_class($this),
                    gettype($line)
                ));
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
            $this->addline($line);
        }
    }

    /**
     * Clear out comment body
     */
    public function clear()
    {
        $this->_lines = array();
    }

    /**
     * @param array $args
     * @return string
     */
    public function compile(array $args = array())
    {
        throw new \BadMethodCallException(sprintf(
            '%s::compile - This method must be overridden in extended class %s.',
            get_class($this),
            get_called_class()
        ));
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->_lines);
    }

    /**
     * @param array $args
     * @return array
     * @throws \DCarbone\PHPClassBuilder\Exception\InvalidCompileArgumentValueException
     */
    protected function parseCompileArgs(array $args)
    {
        static $defaults = array(4);

        if (0 === count($args))
            return $defaults;

        if (isset($args['leadingSpaces']) && is_int($args['leadingSpaces']) && $args['leadingSpaces'] >= 0)
            return array($args['leadingSpaces']);

        throw $this->createInvalidCompileArgumentValueException(
            'leadingSpaces',
            'integer >= 0',
            (isset($args['leadingSpaces']) ? $args['leadingSpaces'] : 'UNDEFINED')
        );
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