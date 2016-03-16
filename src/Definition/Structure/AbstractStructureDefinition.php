<?php namespace DCarbone\PHPClassBuilder\Definition\Structure;

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

use DCarbone\PHPClassBuilder\Definition\AbstractDefinition;
use DCarbone\PHPClassBuilder\Definition\Comment\DoubleStarCommentDefinition;

/**
 * Class AbstractStructureDefinition
 * @package DCarbone\PHPClassBuilder\Definition\Structure
 */
abstract class AbstractStructureDefinition extends AbstractDefinition
{
    /** @var DoubleStarCommentDefinition */
    protected $docBlockComment;

    /**
     * @return DoubleStarCommentDefinition
     */
    public function getDocBlockComment()
    {
        if (!isset($this->docBlockComment))
            $this->docBlockComment = new DoubleStarCommentDefinition();

        return $this->docBlockComment;
    }

    /**
     * @param DoubleStarCommentDefinition $docBlockComment
     */
    public function setDocBlockComment(DoubleStarCommentDefinition $docBlockComment)
    {
        $this->docBlockComment = $docBlockComment;
    }
}