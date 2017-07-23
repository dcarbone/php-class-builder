<?php namespace DCarbone\PHPClassBuilder\Template\Comment;

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

use DCarbone\PHPClassBuilder\Template\AbstractTemplate;

/**
 * Class AbstractCommentTemplate
 * @package DCarbone\PHPClassBuilder\Template\Comment
 */
abstract class AbstractCommentTemplate extends AbstractTemplate implements \Countable, \Iterator {
    const NEWLINE_TEST = '{(\r\n)|\n|\r}S';

    /** @var string[] */
    private $lines = [];

    /** @var int */
    private $offset = 0;
    /** @var bool */
    private $blank = false;

    /**
     * AbstractCommentTemplate constructor.
     * @param null $text
     * @param int $offset
     * @param bool $blank
     */
    public function __construct($text = null, $offset = 0, $blank = false) {
        if (null !== $text) {
            $this->addLine($text);
        }
        $this->offset = (int)$offset;
        $this->blank = (bool)$blank;
    }

    /**
     * @param mixed $line
     * @throws \DCarbone\PHPClassBuilder\Exception\InvalidCommentLineArgumentException
     */
    public function addLine($line = '') {
        switch (gettype($line)) {
            case 'NULL':
                $this->addEmptyLine();
                break;

            case 'array':
                $this->addLines($line);
                break;

            case 'string':
                if ($this->testStringForNewlines($line)) {
                    $this->addLines($this->parseStringInput($line));
                } else {
                    $this->lines[] = $line;
                }
                break;

            case 'integer':
            case 'double':
                $this->lines[] = (string)$line;
                break;

            case 'boolean':
                $this->lines[] = $line ? 'TRUE' : 'FALSE';
                break;

            default:
                throw $this->createInvalidCommentLineArgumentException($line);
        }
    }

    /**
     * Add a blank line to the output.
     */
    public function addEmptyLine() {
        $this->lines[] = '';
    }

    /**
     * @return string[]
     */
    public function getLines() {
        return $this->lines;
    }

    /**
     * @param array $lines
     */
    public function addLines(array $lines) {
        foreach ($lines as $line) {
            $this->addLine($line);
        }
    }

    /**
     * @param array $lines
     */
    public function setLines(array $lines) {
        $this->lines = array();
        foreach ($lines as $line) {
            $this->addLine($line);
        }
    }

    /**
     * @param string $line
     * @param bool|true $strict
     * @return integer Returns index of line or -1 if not found
     */
    public function hasLine($line, $strict = true) {
        return in_array($line, $this->lines, (bool)$strict);
    }

    /**
     * @param string $line
     * @param bool|true $strict
     * @return integer Index of line or -1 if not found
     */
    public function getLineIndex($line, $strict = true) {
        return array_search($line, $this->lines, (bool)$strict);
    }

    /**
     * @param int $idx
     */
    public function removeLineByIndex($idx) {
        if (isset($this->lines[$idx])) {
            unset($this->lines[$idx]);
            $this->lines = array_values($this->lines);
        }
    }

    /**
     * @param string $value
     * @param bool|true $strict
     */
    public function removeLineByValue($value, $strict = true) {
        $idx = $this->getLineIndex($value, (bool)$strict);
        if ($idx >= 0) {
            $this->removeLineByIndex($idx);
        }
    }

    /**
     * Clear out comment body
     */
    public function clear() {
        $this->lines = array();
    }

    /**
     * @return int
     */
    public function getOffset() {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset) {
        $this->offset = $offset;
    }

    /**
     * @return bool
     */
    public function isBlank() {
        return $this->blank;
    }

    /**
     * @param bool $blank
     */
    public function setBlank($blank) {
        $this->blank = (bool)$blank;
    }

    /**
     * @return int
     */
    public function count() {
        return count($this->lines);
    }

    /**
     * @return mixed
     */
    public function current() {
        return current($this->lines);
    }

    public function next() {
        next($this->lines);
    }

    /**
     * @return int|null|string
     */
    public function key() {
        return key($this->lines);
    }

    /**
     * @return bool
     */
    public function valid() {
        return key($this->lines) !== null;
    }

    public function rewind() {
        reset($this->lines);
    }

    /**
     * @param string $line
     * @return bool
     */
    protected function testStringForNewlines($line) {
        return (bool)preg_match(self::NEWLINE_TEST, $line);
    }

    /**
     * @param string $line
     * @return array
     */
    protected function parseStringInput($line) {
        return preg_split(self::NEWLINE_TEST, $line);
    }
}