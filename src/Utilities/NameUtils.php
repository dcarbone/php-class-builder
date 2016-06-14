<?php namespace DCarbone\PHPClassBuilder\Utilities;

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
 * Class NameUtils
 * @package DCarbone\PHPClassBuilder\Utilities
 */
abstract class NameUtils
{
    // Based upon regex seen here: http://php.net/manual/en/language.oop5.basic.php
    const PHP_IDENTIFIER_REGEX = '{^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$}S';
    const NAMESPACE_NAME_REGEX = '{^[a-zA-Z_\x7f-\xff\\\][a-zA-Z0-9_\x7f-\xff\\\]*$}S';

    /**
     * @param string $name
     * @return bool
     */
    public static function isValidVariableName($name)
    {
        return is_string($name) && 1 === preg_match(self::PHP_IDENTIFIER_REGEX, $name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function isValidFunctionName($name)
    {
        return is_string($name) && 1 === preg_match(self::PHP_IDENTIFIER_REGEX, $name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function isValidClassName($name)
    {
        return is_string($name) && 1 === preg_match(self::PHP_IDENTIFIER_REGEX, $name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function isValidNamespaceName($name)
    {
        return is_string($name) && 1 === preg_match(self::NAMESPACE_NAME_REGEX, $name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function isValidInterfaceName($name)
    {
        return is_string($name) && 1 === preg_match(self::PHP_IDENTIFIER_REGEX, $name);
    }
}