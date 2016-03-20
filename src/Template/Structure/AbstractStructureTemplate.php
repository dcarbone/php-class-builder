<?php namespace DCarbone\PHPClassBuilder\Template\Structure;

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
use DCarbone\PHPClassBuilder\Template\Comment\DoubleStarCommentTemplate;

/**
 * Class AbstractStructureTemplate
 * @package DCarbone\PHPClassBuilder\Template\Structure
 */
abstract class AbstractStructureTemplate extends AbstractTemplate
{
    /** @var DoubleStarCommentTemplate */
    protected $docBlockComment;

    /**
     * @return DoubleStarCommentTemplate
     */
    public function getDocBlockComment()
    {
        if (!isset($this->docBlockComment))
            $this->docBlockComment = new DoubleStarCommentTemplate();

        return $this->docBlockComment;
    }

    /**
     * @param DoubleStarCommentTemplate $docBlockComment
     */
    public function setDocBlockComment(DoubleStarCommentTemplate $docBlockComment)
    {
        $this->docBlockComment = $docBlockComment;
    }
}