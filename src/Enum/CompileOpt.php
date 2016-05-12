<?php namespace DCarbone\PHPClassBuilder\Enum;

/**
 * Class Options
 * @package DCarbone\PHPClassBuilder\Enum
 */
abstract class CompileOpt
{
    const LEADING_SPACES = 0;
    const COMPILE_TYPE = 1;
    const INC_COMMENT = 2;
    const INC_DEFAULT_VALUE = 3;
    const INC_BODY = 4;
    const IN_FILE = 5;
}