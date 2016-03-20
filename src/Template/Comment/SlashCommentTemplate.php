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
 * Class SlashCommentTemplate
 * @package DCarbone\PHPClassBuilder\Template\Comment
 */
class SlashCommentTemplate extends AbstractCommentTemplate
{
    /**
     * @param array $args
     * @return string
     */
    public function compile(array $args = array())
    {
        list($leadingSpaces) = $this->parseCompileArgs($args);

        $output = '';
        $leadingSpaces = str_repeat(' ', $leadingSpaces);
        foreach($this->getLines() as $line)
        {
            $output = sprintf("%s%s// %s\n", $output, $leadingSpaces, $line);
        }
        return $output;
    }
}