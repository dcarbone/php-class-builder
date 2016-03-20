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

/**
 * Class DoubleStarCommentTemplate
 * @package DCarbone\PHPClassBuilder\Template\Comment
 */
class DoubleStarCommentTemplate extends AbstractCommentTemplate
{
    /** @var bool */
    private $_useBang = false;

    /**
     * @return bool
     */
    public function usesBang()
    {
        return $this->_useBang;
    }

    /**
     * @param bool|true $useBang
     */
    public function setUseBang($useBang = true)
    {
        $this->_useBang = (bool)$useBang;
    }

    /**
     * @param array $args
     * @return string
     */
    public function compile(array $args = array())
    {
        list($leadingSpaces) = $this->parseCompileArgs($args);

        $spaces = str_repeat(' ', $leadingSpaces);
        $output = sprintf("%s/**%s\n", $spaces, $this->usesBang() ? '!' : '');
        foreach($this->getLines() as $line)
        {
            $output = sprintf("%s%s * %s\n", $output, $spaces, $line);
        }
        return sprintf("%s%s */\n", $output, $spaces);
    }
}