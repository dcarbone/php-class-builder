<?php namespace DCarbone\PHPClassBuilder\Enum;

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
 * Class Options
 * @package DCarbone\PHPClassBuilder\Enum
 */
abstract class CompileOpt
{
    const COMPILE_TYPE = 0;
    const LEADING_SPACES = 1;
    const INC_COMMENT = 2;
    const INC_DEFAULT_VALUE = 3;
    const INC_BODY = 4;
    const IN_FILE = 5;
    const OUTPUT_BLANK_COMMENT = 6;
}